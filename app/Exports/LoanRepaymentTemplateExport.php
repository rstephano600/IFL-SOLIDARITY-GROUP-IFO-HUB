<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LoanRepaymentTemplateExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected array $rows;

    public function __construct()
    {
        // ✅ Fetch 10 active ongoing loans with client relationship
        $loans = Loan::with(['client.client'])
            ->where('Status', 'Active')
            ->where('RepaymentStatus', 'ONGOING')
            ->latest()
            ->take(10)
            ->get();

        $this->rows = $loans->map(function ($loan) {
            return [
                $loan->loan_number,
                date('Y-m-d'),                                          // today as default payment date
                number_format($loan->principal_due, 2, '.', ''),        // installment amount as hint
                'Cash',                                                  // default method
                '',                                                      // reference — left blank
                optional($loan->client)->client->name ?? 'Unknown',     // client name as remark hint
            ];
        })->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Sheet Title
    |--------------------------------------------------------------------------
    */
    public function title(): string
    {
        return 'Loan Repayments';
    }

    /*
    |--------------------------------------------------------------------------
    | Column Headers — must match import keys exactly
    |--------------------------------------------------------------------------
    */
    public function headings(): array
    {
        return [
            'loan_number',
            'payment_date',
            'amount_paid',
            'payment_method',
            'reference_number',
            'remarks',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Real Loan Rows
    |--------------------------------------------------------------------------
    */
    public function array(): array
    {
        return $this->rows;
    }

    /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */
    public function columnWidths(): array
    {
        return [
            'A' => 30,  // loan_number
            'B' => 18,  // payment_date
            'C' => 18,  // amount_paid
            'D' => 20,  // payment_method
            'E' => 22,  // reference_number
            'F' => 30,  // remarks
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = max(2, count($this->rows) + 1); // at least row 2 even if no data

        // Header row — green background, white bold text
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E7D32'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ✅ Data rows — light blue tint to distinguish real data from blank template
        if (count($this->rows) > 0) {
            $sheet->getStyle("A2:F{$lastRow}")->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD'],
                ],
                'font' => [
                    'color' => ['rgb' => '1A237E'],
                ],
            ]);
        }

        // Border around all used cells
        $sheet->getStyle("A1:F{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Freeze header row
        $sheet->freezePane('A2');

        // Instruction comment on A1
        $sheet->getComment('A1')->getText()->createTextRun(
            'Do not change column headers. Date format: YYYY-MM-DD. Edit amount_paid and payment_method before importing.'
        );

        return [];
    }
}