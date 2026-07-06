<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\RepaymentSchedule;
use App\Models\Loan;
use App\Models\GroupCenter;
use App\Models\Group;
use App\Models\Client;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CollectionSummaryExport;

class CollectionSummaryController2 extends Controller
{
    /**
     * Display collection summary with filters
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get filter data for dropdowns
        $filterData = $this->getFilterData($user);

        // Build query with filters
        $query = $this->buildCollectionQuery($user, $request);

        // Paginate collections
        $collections = $query->orderBy('paid_date', 'desc')->paginate(50);

        // Calculate summaries
        $summary = $this->calculateSummary($user, $request);

        // Get trending data for chart
        $trendingData = $this->getTrendingData($user, $request);

        return view('in.loans.collections.summary2.index', compact(
            'collections',
            'summary',
            'filterData',
            'trendingData'
        ));
    }

    /**
     * Build collection query with all filters
     */
    private function buildCollectionQuery($user, Request $request)
    {
        $query = RepaymentSchedule::query()
            ->with([
                'loan' => function($query) {
                    $query->with([
                        'client',
                        'group',
                        'groupCenter',
                        'collectionOfficer',
                        'loanCategory'
                    ]);
                },
                'payer',
                'creator'
            ])
            ->where('is_paid', true);

        // Role-based filtering
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $query->whereHas('loan', function($q) use ($employeeId) {
                    $q->where('collection_officer_id', $employeeId);
                });
            }
        }

        // Apply filters
        if ($request->filled('group_center_id')) {
            $query->whereHas('loan', function($q) use ($request) {
                $q->where('group_center_id', $request->group_center_id);
            });
        }

        if ($request->filled('group_id')) {
            $query->whereHas('loan', function($q) use ($request) {
                $q->where('group_id', $request->group_id);
            });
        }

        if ($request->filled('client_id')) {
            $query->whereHas('loan', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        if ($request->filled('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }

        if ($request->filled('collection_officer_id')) {
            $query->whereHas('loan', function($q) use ($request) {
                $q->where('collection_officer_id', $request->collection_officer_id);
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->whereHas('loan', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Date filtering
        if ($request->filled('paid_date_from')) {
            $query->whereDate('paid_date', '>=', $request->paid_date_from);
        }

        if ($request->filled('paid_date_to')) {
            $query->whereDate('paid_date', '<=', $request->paid_date_to);
        }

        // Quick date filters
        if ($request->filled('date_filter')) {
            $now = Carbon::now();
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('paid_date', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('paid_date', $now->subDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('paid_date', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'last_week':
                    $query->whereBetween('paid_date', [
                        $now->subWeek()->startOfWeek()->toDateString(),
                        $now->subWeek()->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('paid_date', $now->month)
                          ->whereYear('paid_date', $now->year);
                    break;
                case 'last_month':
                    $query->whereMonth('paid_date', $now->subMonth()->month)
                          ->whereYear('paid_date', $now->subMonth()->year);
                    break;
                case 'this_year':
                    $query->whereYear('paid_date', $now->year);
                    break;
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('loan.client', function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('loan', function ($sub) use ($search) {
                    $sub->where('loan_number', 'like', "%{$search}%");
                })
                ->orWhereHas('loan.group', function ($sub) use ($search) {
                    $sub->where('group_name', 'like', "%{$search}%");
                })
                ->orWhereHas('loan.groupCenter', function ($sub) use ($search) {
                    $sub->where('center_name', 'like', "%{$search}%");
                })
                ->orWhereHas('loan.collectionOfficer', function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        return $query;
    }

    /**
     * Get filter dropdown data based on user role
     */
    private function getFilterData($user)
    {
        $data = [];

        // Group Centers
        $centersQuery = GroupCenter::where('is_active', true);
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $centersQuery->whereHas('groups', function($q) use ($employeeId) {
                    $q->where('credit_officer_id', $employeeId);
                });
            }
        }
        $data['groupCenters'] = $centersQuery->orderBy('center_name')->get();

        // Groups
        $groupsQuery = Group::where('is_active', true);
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $groupsQuery->where('credit_officer_id', $employeeId);
            }
        }
        $data['groups'] = $groupsQuery->orderBy('group_name')->get();

        // Clients
        $clientsQuery = Client::whereIn('status', ['active', 'approved']);
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $clientsQuery->where('credit_officer_id', $employeeId);
            }
        }
        $data['clients'] = $clientsQuery->orderBy('first_name')->get();

        // Collection Officers
        if ($user->role === 'admin' || $user->role === 'manager') {
            $data['officers'] = Employee::where('is_active', true)
                ->orderBy('first_name')
                ->get();
        } else {
            $data['officers'] = collect([$user->employee]);
        }

        // Loans
        $loansQuery = Loan::whereNotNull('disbursement_date');
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $loansQuery->where('collection_officer_id', $employeeId);
            }
        }
        $data['loans'] = $loansQuery->orderBy('loan_number')->get();

        return $data;
    }

    /**
     * Calculate summary statistics
     */
    private function calculateSummary($user, Request $request)
    {
        $baseQuery = RepaymentSchedule::where('is_paid', true);

        // Apply role-based filtering
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $baseQuery->whereHas('loan', function($q) use ($employeeId) {
                    $q->where('collection_officer_id', $employeeId);
                });
            }
        }

        // Apply center filter if exists
        if ($request->filled('group_center_id')) {
            $baseQuery->whereHas('loan', function($q) use ($request) {
                $q->where('group_center_id', $request->group_center_id);
            });
        }

        $now = Carbon::now();

        $summary = [
            'today' => $this->getSummaryForPeriod(clone $baseQuery, 'today', $now),
            'week' => $this->getSummaryForPeriod(clone $baseQuery, 'week', $now),
            'month' => $this->getSummaryForPeriod(clone $baseQuery, 'month', $now),
            'year' => $this->getSummaryForPeriod(clone $baseQuery, 'year', $now),
            'total' => $this->getSummaryForPeriod(clone $baseQuery, 'total', $now),
            'filtered' => $this->getFilteredSummary($user, $request),
        ];

        return $summary;
    }

    /**
     * Get summary for specific period
     */
    private function getSummaryForPeriod($query, $period, $now)
    {
        switch ($period) {
            case 'today':
                $query->whereDate('paid_date', $now->toDateString());
                break;
            case 'week':
                $query->whereBetween('paid_date', [
                    $now->copy()->startOfWeek()->toDateString(),
                    $now->copy()->endOfWeek()->toDateString()
                ]);
                break;
            case 'month':
                $query->whereMonth('paid_date', $now->month)
                      ->whereYear('paid_date', $now->year);
                break;
            case 'year':
                $query->whereYear('paid_date', $now->year);
                break;
            case 'total':
                // No additional filter
                break;
        }

        return [
            'count' => $query->count(),
            'principal' => $query->sum('principal_paid'),
            'interest' => $query->sum('interest_paid'),
            'penalty' => $query->sum('penalty_paid'),
            'total' => $query->sum('total_paid'),
        ];
    }

    /**
     * Get filtered summary
     */
    private function getFilteredSummary($user, Request $request)
    {
        $query = $this->buildCollectionQuery($user, $request);

        return [
            'count' => $query->count(),
            'principal' => $query->sum('principal_paid'),
            'interest' => $query->sum('interest_paid'),
            'penalty' => $query->sum('penalty_paid'),
            'total' => $query->sum('total_paid'),
        ];
    }

    /**
     * Get trending data for chart
     */
    private function getTrendingData($user, Request $request)
    {
        $query = RepaymentSchedule::where('is_paid', true);

        // Apply role-based filtering
        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $query->whereHas('loan', function($q) use ($employeeId) {
                    $q->where('collection_officer_id', $employeeId);
                });
            }
        }

        // Apply center filter
        if ($request->filled('group_center_id')) {
            $query->whereHas('loan', function($q) use ($request) {
                $q->where('group_center_id', $request->group_center_id);
            });
        }

        // Date range for trending (last 12 months by default)
        $dateFrom = $request->input('paid_date_from', now()->subMonths(12));
        $dateTo = $request->input('paid_date_to', now());

        $query->whereBetween('paid_date', [$dateFrom, $dateTo]);

        // Group by month
        $trendingData = $query
            ->select(
                DB::raw('DATE_FORMAT(paid_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as collection_count'),
                DB::raw('SUM(principal_paid) as total_principal'),
                DB::raw('SUM(interest_paid) as total_interest'),
                DB::raw('SUM(penalty_paid) as total_penalty'),
                DB::raw('SUM(total_paid) as total_collected')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $trendingData->map(function ($item) {
            return [
                'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M Y'),
                'collection_count' => $item->collection_count,
                'principal' => round($item->total_principal, 2),
                'interest' => round($item->total_interest, 2),
                'penalty' => round($item->total_penalty, 2),
                'total' => round($item->total_collected, 2),
            ];
        });
    }

    /**
     * Get trending data API (for AJAX)
     */
    public function getTrendingDataApi(Request $request)
    {
        $user = Auth::user();
        $trendingData = $this->getTrendingData($user, $request);
        return response()->json($trendingData);
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $query = $this->buildCollectionQuery($user, $request);
        $collections = $query->orderBy('paid_date', 'desc')->get();
        $summary = $this->calculateSummary($user, $request);

        return Excel::download(
            new CollectionSummaryExport($collections, $summary),
            'collection_summary_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $query = $this->buildCollectionQuery($user, $request);
        $collections = $query->orderBy('paid_date', 'desc')->get();
        $summary = $this->calculateSummary($user, $request);

        $pdf = Pdf::loadView('in.loans.collections.summary2.pdf', compact('collections', 'summary'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download('collection_summary_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Print view
     */
    public function print(Request $request)
    {
        $user = Auth::user();
        $query = $this->buildCollectionQuery($user, $request);
        $collections = $query->orderBy('paid_date', 'desc')->get();
        $summary = $this->calculateSummary($user, $request);

        return view('in.loans.collections.summary2.print', compact('collections', 'summary'));
    }

    /**
     * Get groups by center (AJAX)
     */
    public function getGroupsByCenter($centerId)
    {
        $user = Auth::user();
        $query = Group::where('group_center_id', $centerId)->where('is_active', true);

        if ($user->role === 'loanofficer' || $user->role === 'loan_officer') {
            $employeeId = $user->employee ? $user->employee->id : null;
            if ($employeeId) {
                $query->where('credit_officer_id', $employeeId);
            }
        }

        $groups = $query->get(['id', 'group_name']);
        return response()->json($groups);
    }

    /**
     * Get clients by group (AJAX)
     */
    public function getClientsByGroup($groupId)
    {
        $clients = Client::where('group_id', $groupId)
            ->whereIn('status', ['active', 'approved'])
            ->get(['id', 'first_name', 'last_name']);

        return response()->json($clients);
    }

    /**
     * Get loans by client (AJAX)
     */
    public function getLoansByClient($clientId)
    {
        $loans = Loan::where('client_id', $clientId)
            ->whereNotNull('disbursement_date')
            ->get(['id', 'loan_number']);

        return response()->json($loans);
    }
}