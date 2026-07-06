<?php

namespace App\Imports;

use App\Models\Loan;
use App\Models\LoanRepayment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class LoanRepaymentImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new Exception("The uploaded file contains no data rows.");
        }

        foreach ($rows as $index => $row) {

            $rowNumber = $index + 2;

            // Skip fully empty rows
            if (empty(trim($row['loan_number'] ?? ''))) {
                continue;
            }

            // Validate required fields per row
            if (empty($row['payment_date']) || empty($row['amount_paid'])) {
                $this->errors[] = "Row {$rowNumber}: 'payment_date' and 'amount_paid' are required.";
                continue;
            }

            if (!is_numeric($row['amount_paid']) || $row['amount_paid'] <= 0) {
                $this->errors[] = "Row {$rowNumber}: 'amount_paid' must be a positive number.";
                continue;
            }

            // Find loan
            $loan = Loan::where('loan_number', trim($row['loan_number']))->first();

            if (!$loan) {
                $this->errors[] = "Row {$rowNumber}: Loan number '{$row['loan_number']}' not found.";
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Allocation — mirrors storeloanrepayment exactly
            |--------------------------------------------------------------------------
            */

            $amountPaid    = (float) $row['amount_paid'];
            $principalPaid = min($loan->principal_due, $amountPaid);
            $interestPaid  = min($loan->interest_due, max(0, $amountPaid - $principalPaid));

            LoanRepayment::create([
                'loan_id'          => $loan->id,
                'client_id'        => $loan->client_id,
                'payment_date'     => $row['payment_date'],
                'amount_paid'      => $amountPaid,
                'principal_paid'   => $principalPaid,
                'interest_paid'    => $interestPaid,
                'penalty_paid'     => 0,
                'payment_method'   => $row['payment_method']   ?? null,
                'reference_number' => $row['reference_number'] ?? null,
                'remarks'          => $row['remarks']          ?? null,
                'received_by'      => Auth::id(),
                'User_id'          => Auth::id(),
                'Status'           => 'Active',
                'AuditingStatus'   => 'Pending',
                'ReportStatus'     => 'Pending',
            ]);

            $loan->increment('amount_paid', $amountPaid);
            $loan->refresh();

            /*
            |--------------------------------------------------------------------------
            | Loan Status Check — mirrors storeloanrepayment exactly
            |--------------------------------------------------------------------------
            */

            if (
                $loan->total_amount_paid >= $loan->repayable_amount || // ✅ correct attribute
                $loan->outstanding_balance <= 0                         // ✅ second condition
            ) {
                $loan->update([
                    'RepaymentStatus' => 'COMPLETE', // ✅ correct field name
                    'closed_at'       => now(),
                ]);
            } else {
                $loan->update([                      // ✅ reset if not yet complete
                    'RepaymentStatus' => 'ONGOING',
                    'closed_at'       => null,
                ]);
            }
        }
    }
}