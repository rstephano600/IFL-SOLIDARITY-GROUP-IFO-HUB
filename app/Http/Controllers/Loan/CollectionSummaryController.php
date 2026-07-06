<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\RepaymentSchedule;
use App\Models\GroupCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

use Illuminate\Support\Facades\DB;


class CollectionSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filters
        $search     = $request->input('search');
        $filter     = $request->input('filter', 'today');
        $centerId   = $request->input('center_id');
        $groupId    = $request->input('group_id');
        $officerId  = $request->input('officer_id');
        $paidDate   = $request->input('paid_date');

        // load centers for dropdowns
        $centersQuery = GroupCenter::query();
        if ($user->role === 'loanofficer') {
            $centersQuery->whereHas('groups', fn($q) => $q->where('credit_officer_id', $user->id));
        }
        $centers = $centersQuery->orderBy('center_name')->get();

        // main query builder
        $baseQuery = $this->getFilteredCollectionsQuery(
            $user,
            $search,
            $filter,
            $centerId,
            $groupId,
            $officerId,
            $paidDate
        );

        $collections = $baseQuery->orderBy('paid_date', 'desc')->paginate(30);

        // summary totals
        $summary = [
            'today'     => $this->getTotal($user, 'today', $centerId),
            'yesterday' => $this->getTotal($user, 'yesterday', $centerId),
            'week'      => $this->getTotal($user, 'week', $centerId),
            'month'     => $this->getTotal($user, 'month', $centerId),
            'year'      => $this->getTotal($user, 'year', $centerId),
            'total'     => $this->getTotal($user, 'total', $centerId),
        ];

        return view('in.loans.collections.summary.index', compact(
            'collections',
            'summary',
            'filter',
            'search',
            'centers',
            'centerId',
            'groupId',
            'officerId',
            'paidDate'
        ));
    }

    /**
     * Build filtered query (not executed)
     */
    private function getFilteredCollectionsQuery($user, $search, $filter, $centerId = null, $groupId = null, $officerId = null, $paidDate = null)
    {
        $query = RepaymentSchedule::with([
            'loan.client',
            'loan.group.groupCenter',
            'loan.collectionOfficer',
            'loan.loanCategory'
        ])->where('is_paid', true);

        // Restrict for loan officers
        if ($user->role === 'loanofficer') {
            $query->whereHas('loan', fn($q) => $q->where('collection_officer_id', $user->id));
        }

        // Additional filters
        if ($centerId) {
            $query->whereHas('loan.group.groupCenter', fn($q) => $q->where('id', $centerId));
        }

        if ($groupId) {
            $query->whereHas('loan.group', fn($q) => $q->where('id', $groupId));
        }

        if ($officerId) {
            $query->whereHas('loan', fn($q) => $q->where('collection_officer_id', $officerId));
        }

        // Specific paid date
        if ($paidDate) {
            $query->whereDate('paid_date', $paidDate);
        } else {
            $now = Carbon::now();
            match ($filter) {
                'today'     => $query->whereDate('paid_date', $now),
                'yesterday' => $query->whereDate('paid_date', $now->copy()->subDay()),
                'week'      => $query->whereBetween('paid_date', [$now->startOfWeek(), $now->endOfWeek()]),
                'month'     => $query->whereMonth('paid_date', $now->month)->whereYear('paid_date', $now->year),
                'year'      => $query->whereYear('paid_date', $now->year),
                default     => null,
            };
        }

        // Search by client, group, loan number, officer name
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('loan.client', function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhereHas('loan.group', function ($sub) use ($search) {
                    $sub->where('group_name', 'like', "%{$search}%");
                })
                ->orWhereHas('loan.collectionOfficer', function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhereHas('loan', function ($sub) use ($search) {
                    $sub->where('loan_number', 'like', "%{$search}%");
                });
            });
        }

        return $query;
    }

    /**
     * Summary Totals
     */
    /**
 * Summary Totals - Reflects all active filters (search, date, center, group, officer)
 */
private function getTotal($user, $range = null, $centerId = null, $groupId = null, $officerId = null, $paidDate = null, $search = null)
{
    $query = RepaymentSchedule::with([
        'loan.client',
        'loan.group.groupCenter',
        'loan.collectionOfficer',
        'loan.loanCategory'
    ])->where('is_paid', true);

    // Restrict for loan officers
    if ($user->role === 'loanofficer') {
        $query->whereHas('loan', fn($q) => $q->where('collection_officer_id', $user->id));
    }

    // Center filter
    if ($centerId) {
        $query->whereHas('loan.group.groupCenter', fn($q) => $q->where('id', $centerId));
    }

    // Group filter
    if ($groupId) {
        $query->whereHas('loan.group', fn($q) => $q->where('id', $groupId));
    }

    // Officer filter
    if ($officerId) {
        $query->whereHas('loan', fn($q) => $q->where('collection_officer_id', $officerId));
    }

    // Paid date or range filter
    $now = Carbon::now();
    if ($paidDate) {
        $query->whereDate('paid_date', $paidDate);
    } else {
        match ($range) {
            'today'     => $query->whereDate('paid_date', $now),
            'yesterday' => $query->whereDate('paid_date', $now->copy()->subDay()),
            'week'      => $query->whereBetween('paid_date', [$now->startOfWeek(), $now->endOfWeek()]),
            'month'     => $query->whereMonth('paid_date', $now->month)->whereYear('paid_date', $now->year),
            'year'      => $query->whereYear('paid_date', $now->year),
            default     => null,
        };
    }

    // Search filter
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('loan.client', function ($sub) use ($search) {
                $sub->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('loan.group', function ($sub) use ($search) {
                $sub->where('group_name', 'like', "%{$search}%");
            })
            ->orWhereHas('loan.collectionOfficer', function ($sub) use ($search) {
                $sub->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('loan', function ($sub) use ($search) {
                $sub->where('loan_number', 'like', "%{$search}%");
            });
        });
    }

    // Return total principal paid
    return $query->sum(DB::raw('principal_paid'));
}

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $query = $this->getFilteredCollectionsQuery(
            $user,
            $request->search,
            $request->filter,
            $request->center_id,
            $request->group_id,
            $request->officer_id,
            $request->paid_date
        );
        $collections = $query->orderBy('paid_date', 'desc')->get();

        return Excel::download(new \App\Exports\CollectionSummaryExport($collections), 'collections_summary.xlsx');
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $query = $this->getFilteredCollectionsQuery(
            $user,
            $request->search,
            $request->filter,
            $request->center_id,
            $request->group_id,
            $request->officer_id,
            $request->paid_date
        );
        $collections = $query->orderBy('paid_date', 'desc')->get();

        $pdf = PDF::loadView('in.loans.collections.summary.pdf', compact('collections'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('collections_summary.pdf');
    }

    public function exportPdfWithNoData(Request $request)
    {
        $user = Auth::user();
        $query = $this->getFilteredCollectionsQuery(
            $user,
            $request->search,
            $request->filter,
            $request->center_id,
            $request->group_id,
            $request->officer_id,
            $request->paid_date
        );
        $collections = $query->orderBy('paid_date', 'desc')->get();

        $pdf = PDF::loadView('in.loans.collections.summary.pdfwithnodata', compact('collections'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('collections_summary.pdf');
    }
}
