<?php

namespace App\Exports;

use App\Models\ClientLoan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LoansExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $filters;
    protected $stats;

    public function __construct($filters, $stats)
    {
        $this->filters = $filters;
        $this->stats = $stats;
    }

    public function collection()
    {
        $query = ClientLoan::with(['client', 'creator']);

        // Apply filters
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['created_by'])) {
            $query->where('created_by', $this->filters['created_by']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Loan Number',
            'Client Name',
            'Amount Requested',
            'Amount Disbursed',
            'Interest Rate (%)',
            'Interest Amount',
            'Loan Fee',
            'Other Fee',
            'Total Payable',
            'Amount Paid',
            'Preclosure Fee',
            'Penalty Fee',
            'Total Amount Paid',
            'Outstanding Balance',
            'Profit/Loss',
            'Status',
            'Repayment Frequency',
            'Start Date',
            'End Date',
            'Days Left',
            'Created By',
            'Created At',
            'Remarks'
        ];
    }

    public function map($loan): array
    {
        $totalPayable = $loan->amount_disbursed + $loan->interest_amount + $loan->other_fee + $loan->loan_fee;
        $totalAmountPaid = $loan->amount_paid + $loan->penalty_fee + $loan->total_preclosure;
        $profitLoss = $totalAmountPaid - $loan->amount_disbursed;

        return [
            $loan->loan_number,
            $loan->client->name ?? 'N/A',
            $loan->amount_requested,
            $loan->amount_disbursed,
            $loan->interest_rate,
            $loan->interest_amount,
            $loan->loan_fee,
            $loan->other_fee,
            $totalPayable,
            $loan->amount_paid,
            $loan->total_preclosure,
            $loan->penalty_fee,
            $totalAmountPaid,
            $loan->outstanding_balance,
            $profitLoss,
            ucfirst($loan->status),
            ucfirst($loan->repayment_frequency),
            $loan->start_date ? $loan->start_date->format('Y-m-d') : '',
            $loan->end_date ? $loan->end_date->format('Y-m-d') : '',
            $loan->days_left,
            $loan->creator->name ?? 'N/A',
            $loan->created_at->format('Y-m-d H:i:s'),
            $loan->remarks ?? ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function title(): string
    {
        return 'Loans Report';
    }
}
