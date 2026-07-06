<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectionSummaryExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    ShouldAutoSize,
    WithTitle
{
    protected $collections;
    protected $summary;

    public function __construct($collections, $summary = null)
    {
        $this->collections = $collections;
        $this->summary = $summary;
    }

    public function collection()
    {
        return $this->collections;
    }

    public function headings(): array
    {
        return [
            'Payment Date',
            'Installment #',
            'Loan Number',
            'Client Name',
            'Group',
            'Center',
            'Collection Officer',
            'Principal Paid',
            'Interest Paid',
            'Penalty Paid',
            'Total Paid',
            'Payment Method',
            'Paid By',
            'Status',
        ];
    }

    public function map($schedule): array
    {
        return [
            $schedule->paid_date ? $schedule->paid_date->format('Y-m-d') : '',
            $schedule->installment_number ?? '',
            $schedule->loan->loan_number ?? '',
            ($schedule->loan->client->first_name ?? '') . ' ' . ($schedule->loan->client->last_name ?? ''),
            $schedule->loan->group->group_name ?? 'N/A',
            $schedule->loan->groupCenter->center_name ?? 'N/A',
            $schedule->loan->collectionOfficer->full_name ?? 'N/A',
            number_format($schedule->principal_paid, 2),
            number_format($schedule->interest_paid, 2),
            number_format($schedule->penalty_paid, 2),
            number_format($schedule->total_paid, 2),
            ucfirst($schedule->payment_method ?? 'N/A'),
            $schedule->payer->full_name ?? 'N/A',
            $schedule->status ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]
            ],
        ];
    }

    public function title(): string
    {
        return 'Collection Summary';
    }
}