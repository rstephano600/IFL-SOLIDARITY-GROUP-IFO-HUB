<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\GroupCenter;
use App\Models\Group;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoanReportExport;

class LoanReportController extends Controller
{
    /**
     * Display loan report page with filters
     */
    public function index(Request $request)
    {
        $query = Loan::with([
            'groupCenter',
            'group',
            'client',
            'collectionOfficer',
            'loanCategory'
        ]);

        // Apply Filters
        if ($request->filled('group_center_id')) {
            $query->where('group_center_id', $request->group_center_id);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('collection_officer_id')) {
            $query->where('collection_officer_id', $request->collection_officer_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('disbursement_date_from')) {
            $query->whereDate('disbursement_date', '>=', $request->disbursement_date_from);
        }

        if ($request->filled('disbursement_date_to')) {
            $query->whereDate('disbursement_date', '<=', $request->disbursement_date_to);
        }

        // Get filtered loans
        $loans = $query->latest('disbursement_date')->paginate(50);

        // Calculate Summary
        $summary = $this->calculateSummary($query->get());

        // Get filter options
        $groupCenters = GroupCenter::where('is_active', true)->get();
        $groups = Group::where('is_active', true)->get();
        $clients = Client::whereIn('status', ['active', 'approved'])->get();
        $officers = Employee::where('is_active', true)->get();

        return view('in.loans.reports.reports.index', compact(
            'loans',
            'summary',
            'groupCenters',
            'groups',
            'clients',
            'officers'
        ));
    }

    /**
     * Calculate loan summary statistics
     */
    private function calculateSummary($loans)
    {
        return [
            'total_loans' => $loans->count(),
            'total_disbursed' => $loans->sum('amount_disbursed'),
            'total_interest' => $loans->sum('interest_amount'),
            'total_fees' => $loans->sum(function ($loan) {
                return $loan->total_fee;
            }),
            'total_repayable' => $loans->sum(function ($loan) {
                return $loan->repayable_amount;
            }),
            'total_paid' => $loans->sum('amount_paid'),
            'total_outstanding' => $loans->sum(function ($loan) {
                return $loan->outstanding_balance;
            }),
            'amount_with_refund' => $loans->sum(function ($loan) {
                return $loan->amount_with_refund;
            }),
            'amount_with_preclosure' => $loans->sum(function ($loan) {
                return $loan->amount_with_preclosure;
            }),
            'total_profit' => $loans->sum(function ($loan) {
                return $loan->total_profit;
            }),
            'fees_paid' => [
                'membership' => $loans->sum('membership_fee_paid'),
                'insurance' => $loans->sum('insurance_fee_paid'),
                'officer_visit' => $loans->sum('officer_visit_fee_paid'),
                'penalty' => $loans->sum('penalty_fee_paid'),
                'preclosure' => $loans->sum('preclosure_fee_paid'),
                'other' => $loans->sum('other_fee_paid'),
            ]
        ];
    }

    /**
     * Get trending data for graph
     */
    public function getTrendingData(Request $request)
    {
        $query = Loan::query();

        // Apply same filters
        if ($request->filled('group_center_id')) {
            $query->where('group_center_id', $request->group_center_id);
        }
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->filled('collection_officer_id')) {
            $query->where('collection_officer_id', $request->collection_officer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range (default last 12 months)
        $dateFrom = $request->input('disbursement_date_from', now()->subMonths(12));
        $dateTo = $request->input('disbursement_date_to', now());

        $query->whereBetween('disbursement_date', [$dateFrom, $dateTo]);

        // Group by month
        $trendingData = $query
            ->selectRaw('
                DATE_FORMAT(disbursement_date, "%Y-%m") as month,
                COUNT(*) as loan_count,
                SUM(amount_disbursed) as total_disbursed,
                SUM(interest_amount) as total_interest,
                SUM(amount_paid) as total_paid
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Calculate profit for each month
        $chartData = $trendingData->map(function ($item) {
            $monthLoans = Loan::whereRaw('DATE_FORMAT(disbursement_date, "%Y-%m") = ?', [$item->month])->get();
            
            return [
                'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M Y'),
                'loan_count' => $item->loan_count,
                'disbursed' => round($item->total_disbursed, 2),
                'interest' => round($item->total_interest, 2),
                'paid' => round($item->total_paid, 2),
                'profit' => round($monthLoans->sum(function ($loan) {
                    return $loan->profit_loss_amount;
                }), 2)
            ];
        });

        return response()->json($chartData);
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new LoanReportExport($filters), 'loan_report_' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Loan::with([
            'groupCenter',
            'group',
            'client',
            'collectionOfficer',
            'loanCategory'
        ]);

        // Apply same filters as index
        if ($request->filled('group_center_id')) {
            $query->where('group_center_id', $request->group_center_id);
        }
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('collection_officer_id')) {
            $query->where('collection_officer_id', $request->collection_officer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('disbursement_date_from')) {
            $query->whereDate('disbursement_date', '>=', $request->disbursement_date_from);
        }
        if ($request->filled('disbursement_date_to')) {
            $query->whereDate('disbursement_date', '<=', $request->disbursement_date_to);
        }

        $loans = $query->get();
        $summary = $this->calculateSummary($loans);

        $pdf = Pdf::loadView('in.loans.reports.reports.pdf', compact('loans', 'summary'));
        return $pdf->download('loan_report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Print view
     */
    public function print(Request $request)
    {
        $query = Loan::with([
            'groupCenter',
            'group',
            'client',
            'collectionOfficer',
            'loanCategory'
        ]);

        // Apply filters
        if ($request->filled('group_center_id')) {
            $query->where('group_center_id', $request->group_center_id);
        }
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('collection_officer_id')) {
            $query->where('collection_officer_id', $request->collection_officer_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('disbursement_date_from')) {
            $query->whereDate('disbursement_date', '>=', $request->disbursement_date_from);
        }
        if ($request->filled('disbursement_date_to')) {
            $query->whereDate('disbursement_date', '<=', $request->disbursement_date_to);
        }

        $loans = $query->get();
        $summary = $this->calculateSummary($loans);

        return view('in.loans.reports.reports.print', compact('loans', 'summary'));
    }

    /**
     * Get groups by center (AJAX)
     */
    public function getGroupsByCenter($centerId)
    {
        $groups = Group::where('group_center_id', $centerId)
            ->where('is_active', true)
            ->get(['id', 'group_name']);
        
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
}