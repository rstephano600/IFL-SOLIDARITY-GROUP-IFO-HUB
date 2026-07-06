<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\ClientLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoansExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Build base query for filtering
        $baseQuery = ClientLoan::query();

        // Apply filters to base query
        if ($request->filled('date_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('created_by')) {
            $baseQuery->where('created_by', $request->created_by);
        }

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        // Clone query for statistics (without pagination)
        $statsQuery = clone $baseQuery;
        
        // Calculate statistics based on filtered data
        $stats = $statsQuery->selectRaw('
            COUNT(*) as total_loans,
            COALESCE(SUM(amount_requested), 0) as total_requested,
            COALESCE(SUM(amount_disbursed), 0) as total_disbursed,
            COALESCE(SUM(interest_amount), 0) as total_interest,
            COALESCE(SUM(loan_fee), 0) as total_loan_fees,
            COALESCE(SUM(other_fee), 0) as total_other_fees,
            COALESCE(SUM(total_preclosure), 0) as total_preclosure,
            COALESCE(SUM(amount_paid + penalty_fee + total_preclosure), 0) as total_paid,
            COALESCE(SUM(outstanding_balance), 0) as total_outstanding,
            COALESCE(SUM(amount_paid + penalty_fee + total_preclosure - amount_disbursed), 0) as total_profit_loss
        ')->first();

        // Get filtered loans for list with relationships
        $loans = $baseQuery->with(['client', 'creator'])
            ->latest()
            ->paginate(15)
            ->appends($request->all());

        // Get creators for filter dropdown
        $creators = DB::table('users')
            ->join('client_loans', 'users.id', '=', 'client_loans.created_by')
            ->select('users.id', 'users.username')
            ->distinct()
            ->orderBy('users.username')
            ->get();

        return view('in.loans.dashboard.dashboard', compact('loans', 'stats', 'creators'));
    }

     public function exportExcel(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'created_by', 'status']);
        
        // Get stats for the export
        $query = ClientLoan::query();
        $this->applyFilters($query, $filters);
        
        $stats = $query->selectRaw('
            COUNT(*) as total_loans,
            COALESCE(SUM(amount_requested), 0) as total_requested,
            COALESCE(SUM(amount_disbursed), 0) as total_disbursed,
            COALESCE(SUM(interest_amount), 0) as total_interest,
            COALESCE(SUM(loan_fee), 0) as total_loan_fees,
            COALESCE(SUM(other_fee), 0) as total_other_fees,
            COALESCE(SUM(total_preclosure), 0) as total_preclosure,
            COALESCE(SUM(amount_paid + penalty_fee + total_preclosure), 0) as total_paid,
            COALESCE(SUM(outstanding_balance), 0) as total_outstanding,
            COALESCE(SUM(amount_paid + penalty_fee + total_preclosure - amount_disbursed), 0) as total_profit_loss
        ')->first();

        $filename = 'loans_report_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new LoansExport($filters, $stats), $filename);
    }

    public function exportPdf(Request $request)
    {
        $query = ClientLoan::with(['client', 'creator']);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->latest()->get();

        // Calculate statistics
        $statsQuery = clone $query;
        $stats = ClientLoan::query();
        $this->applyFilters($stats, $request->only(['date_from', 'date_to', 'created_by', 'status']));
        
        $stats = $stats->selectRaw('
            COUNT(*) as total_loans,
            COALESCE(SUM(amount_requested), 0) as total_requested,
            COALESCE(SUM(amount_disbursed), 0) as total_disbursed,
            COALESCE(SUM(interest_amount), 0) as total_interest,
            COALESCE(SUM(loan_fee), 0) as total_loan_fees,
            COALESCE(SUM(other_fee), 0) as total_other_fees,
            COALESCE(SUM(total_preclosure), 0) as total_preclosure,
            COALESCE(SUM(amount_paid + penalty_fee + total_preclosure), 0) as total_paid,
            COALESCE(SUM(outstanding_balance), 0) as total_outstanding,
            COALESCE(SUM(amount_paid + penalty_fee + total_preclosure - amount_disbursed), 0) as total_profit_loss
        ')->first();

        $pdf = Pdf::loadView('in.loans.dashboard.pdf', compact('loans', 'stats', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('loans_report_' . date('Y-m-d_His') . '.pdf');
    }

    private function applyFilters($query, $filters)
    {
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['created_by'])) {
            $query->where('created_by', $filters['created_by']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }


    public function export(Request $request)
    {
        $query = ClientLoan::with(['client', 'creator']);

        // Apply same filters as index
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $loans = $query->get();

        $filename = 'loans_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($loans) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Loan Number',
                'Client Name',
                'Amount Requested',
                'Amount Disbursed',
                'Interest Rate',
                'Interest Amount',
                'Loan Fee',
                'Other Fee',
                'Total Payable',
                'Amount Paid',
                'Preclosure',
                'Penalty Fee',
                'Total Paid',
                'Outstanding Balance',
                'Profit/Loss',
                'Status',
                'Start Date',
                'End Date',
                'Created By',
                'Created At'
            ]);

            // Data rows
            foreach ($loans as $loan) {
                fputcsv($file, [
                    $loan->loan_number,
                    $loan->client->name ?? 'N/A',
                    number_format($loan->amount_requested, 2),
                    number_format($loan->amount_disbursed, 2),
                    $loan->interest_rate . '%',
                    number_format($loan->interest_amount, 2),
                    number_format($loan->loan_fee, 2),
                    number_format($loan->other_fee, 2),
                    number_format($loan->amount_disbursed + $loan->interest_amount + $loan->other_fee + $loan->loan_fee, 2),
                    number_format($loan->amount_paid, 2),
                    number_format($loan->total_preclosure, 2),
                    number_format($loan->penalty_fee, 2),
                    number_format($loan->amount_paid + $loan->penalty_fee + $loan->total_preclosure, 2),
                    number_format($loan->outstanding_balance, 2),
                    number_format($loan->amount_paid + $loan->penalty_fee + $loan->total_preclosure - $loan->amount_disbursed, 2),
                    ucfirst($loan->status),
                    $loan->start_date ? $loan->start_date->format('Y-m-d') : '',
                    $loan->end_date ? $loan->end_date->format('Y-m-d') : '',
                    $loan->creator->name ?? 'N/A',
                    $loan->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}