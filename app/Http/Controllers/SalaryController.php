<?php

namespace App\Http\Controllers;

use App\Facades\TenantDB; // adjust namespace to wherever your TenantDB facade actually lives
use App\Models\CompSlrAdvCost;
use App\Models\Department;
use App\Models\EmpSlrAdvCost;
use App\Models\OvertPay;
use App\Models\PayrollBatch;
use App\Models\SalaryCalcl;
use App\Models\SalaryPaid;
use App\Models\SlrAllwnc;
use App\Models\StaffAbsent;
use App\Models\StaffAbsentCalender;
use App\Models\StaffAdvncSlry;
use App\Models\StaffHeslb;
use App\Models\StaffOvertime;
use App\Models\TempSalary;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class SalaryController extends Controller
{
    /**
     * Employee-side NSSF contribution rate, deducted from gross before PAYE.
     */
    private const NSSF_EMPLOYEE_RATE = 0.10;

    /**
     * Tolerance (in TZS) used when solving gross-from-net via bisection.
     */
    private const NET_TO_GROSS_PRECISION = 0.01;

    /**
     * PAYE bands (Tanzania), ordered highest-to-lowest so the first
     * matching bracket wins. Each bracket is: everything above `min`
     * is taxed at `rate`, plus the flat `base` amount for lower bands
     * already accounted for.
     */
    private const PAYE_BRACKETS = [
        ['min' => 1_000_000, 'rate' => 0.30, 'base' => 128_000],
        ['min' => 760_000,   'rate' => 0.25, 'base' => 68_000],
        ['min' => 520_000,   'rate' => 0.20, 'base' => 20_000],
        ['min' => 270_000,   'rate' => 0.08, 'base' => 0],
    ];

    private const BATCH_STATUS_GENERATED  = 'Generated';
    private const BATCH_STATUS_ROLLED_BACK = 'RolledBack';

    private const GENERIC_ERROR_MESSAGE = 'Technical Error Exists, Please Contact IT Team For Support';

    private const STATUS_GENERATED        = 'Generated';
    private const STATUS_PENDING_APPROVAL = 'PendingApproval';
    private const STATUS_APPROVED         = 'Approved';

    /*
    |--------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------
    */

    /**
     * Payroll review/dashboard screen: current temp batch (if any),
     * the previous month's finalized totals, and reference lists.
     */
    public function salarypay()
    {
        // try {
            // \LogActivity::addToLog('Salary Review For Payments');

            // $budgetComponents = TenantDB::table('budget_components')->where('Status', 'Active')->get();
            // $departments = Department::where('DepartmentName', '!=', 'Administrator')
            //     ->where('Status', 'Active')
            //     ->get();

            $tempSalaries = TempSalary::where('Status', 'Active')->get();
            $month = $tempSalaries->isNotEmpty() ? $tempSalaries->first()->PaidMonth : date('Y-m');
            $previousMonth = date('Y-m', strtotime($month . '-01 -1 month'));

            $previousSalary = null;
            $previousSalaries = collect();
            $totalActiveTemp = 0;

            if ($tempSalaries->isNotEmpty()) {
                $previousSalary = SalaryPaid::where('PaidMonth', $previousMonth)->first();
                $previousSalaries = SalaryPaid::where('PaidMonth', $previousMonth)->get();
                $totalActiveTemp = TempSalary::where('Status', 'Active')->sum('AmountPaid');
            }

            $activeBatch = $tempSalaries->isNotEmpty()
                ? PayrollBatch::where('BatchCode', $tempSalaries->first()->BatchCode)->first()
                : null;

            return view('in.salary.salarypay', [
                // 'deprt'      => $departments,
                // 'bdt'        => $budgetComponents,
                'temp'       => $tempSalaries,
                'total'      => $totalActiveTemp,
                'prslr'      => $previousSalary,
                'prslr1'     => $previousSalaries,
                'month'      => $month,
                'activeBatch' => $activeBatch,
            ]);
        // } catch (\Throwable $th) {
        //     Log::error('Salary Dashboard Error: ' . $th->getMessage());
        //     Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        //     return back();
        // }
    }

    /*
    |--------------------------------------------------------------------
    | Payroll Generation
    |--------------------------------------------------------------------
    */

    /**
     * Generate a new payroll batch for the given month: pulls every
     * active salary setup, resolves each employee's deductions/
     * allowances for this run, and writes draft rows to temp_salaries.
     */
    public function generatePayrollByMonth(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Generate Payroll By Selected Month');

        $request->validate([
            'PayrollMonth' => 'required|date_format:Y-m',
        ]);

        $month = $request->PayrollMonth;

        if (TempSalary::where('Status', 'Active')->exists()) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'There is already an existing unsubmitted payroll on the temp salary table. Submit or roll it back first.');
            return redirect()->route('salarypay');
        }

        if (SalaryPaid::where('PaidMonth', $month)->exists()) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'Payroll for the selected month already exists.');
            return redirect()->route('salarypay');
        }

        $activeSalarySetups = SalaryCalcl::where('Status', 'Active')->get();

        if ($activeSalarySetups->isEmpty()) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'No active salary setup found.');
            return redirect()->route('salarypay');
        }

        DB::beginTransaction();

        // try {
            $batchCode = 'IFLSG/PAY/' . $month . '/' . strtoupper(Str::random(8));

            $batch = new PayrollBatch();
            $batch->BatchCode    = $batchCode;
            $batch->PayrollMonth = $month;
            $batch->BatchStatus  = self::BATCH_STATUS_GENERATED;
            $batch->SnapshotJson = json_encode($this->buildPayrollSnapshot());
            $batch->User_id      = auth()->user()->id;
            $batch->save();

            foreach ($activeSalarySetups as $setup) {
                $this->generateSalaryRowFor($setup, $month, $batchCode);
            }

            DB::commit();

            Alert::success('Congrats ' . auth()->user()->FirstName, 'Payroll generated successfully for ' . $month);
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     Log::error('Generate Payroll Error: ' . $th->getMessage());
        //     Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        // }

        return redirect()->route('salarypay');
    }

    /**
     * Resolve one employee's overtime, allowances, deductions and
     * statutory costs for the current run, then persist the draft row.
     */
    private function generateSalaryRowFor(SalaryCalcl $setup, string $month, string $batchCode): void
    {
        $overtime           = $this->processOvertime($setup->Paid_id, $month);
        $overtimeInAdvance   = $this->processOvertimeInAdvance($setup->Paid_id);
        $allowance           = $this->getAllowance($setup->Paid_id);
        $heslbDeduction      = $this->processHeslb($setup->Paid_id);
        $staffLoanDeduction  = $this->processBcabd($setup->Paid_id, $month);
        $advanceDeduction    = $this->processAdvance($setup->Paid_id);
        $absentDeduction     = $this->processAbsent($setup->Paid_id);

        $netSalary   = $this->calculateNetSalary($setup->GrsAmount, $allowance, $absentDeduction, $overtime, $overtimeInAdvance);
        $grossSalary = $this->calculateGrossFromNet($netSalary);

        $this->saveSalaryWithBatch(
            $setup,
            $month,
            $grossSalary,
            $allowance,
            $overtime,
            $heslbDeduction,
            $absentDeduction,
            $staffLoanDeduction,
            $advanceDeduction,
            $overtimeInAdvance,
            $batchCode
        );
    }

    /**
     * Persist one draft payroll row plus every derived statutory figure.
     */
    private function saveSalaryWithBatch(
        SalaryCalcl $setup,
        string $month,
        float $grossSalary,
        float $allowance,
        float $overtime,
        float $heslbDeduction,
        float $absentDeduction,
        float $staffLoanDeduction,
        float $advanceDeduction,
        float $overtimeInAdvance,
        string $batchCode
    ): TempSalary {
        $employeeNssf = $grossSalary * self::NSSF_EMPLOYEE_RATE;
        $paye = $this->calculatePaye($grossSalary - $employeeNssf);

        $salary               = new TempSalary();
        $salary->ActualGross  = $setup->GrsAmount;
        $salary->Allowance    = $allowance;
        $salary->Overtime     = $overtime;
        $salary->OvtmAdvn     = $overtimeInAdvance;
        $salary->Heslb        = $heslbDeduction;
        $salary->Absent       = $absentDeduction;
        $salary->Bcabd        = $staffLoanDeduction;
        $salary->Advance      = $advanceDeduction;
        $salary->AmountPaid   = $grossSalary;
        $salary->EmpNssf      = $employeeNssf;
        $salary->Paye         = $paye;
        $salary->NetPay       = $grossSalary - ($employeeNssf + $paye) - ($heslbDeduction + $staffLoanDeduction + $advanceDeduction + $overtimeInAdvance);
        $salary->SdlPay       = $grossSalary * ($this->statutoryRate('SDL') / 100);
        $salary->NssfPay      = $grossSalary * ($this->statutoryRate("NSSF Employer's") / 100);
        $salary->WcfPay       = $grossSalary * ($this->statutoryRate('WCF') / 100);
        $salary->Emply_id     = $setup->Paid_id;
        $salary->PaidMonth    = $month;
        $salary->BatchCode    = $batchCode;
        $salary->User_id      = auth()->user()->id;
        $salary->Condition    = 'None';
        $salary->Status       = 'Active';
        $salary->save();

        return $salary;
    }

    /**
     * Roll back a not-yet-submitted batch: restore every source record
     * this run touched (from the snapshot) and discard the draft rows.
     */
    public function rollbackGeneratedPayroll(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Rollback Generated Payroll');

        $request->validate([
            'BatchCode' => 'required',
        ]);

        $batch = PayrollBatch::where('BatchCode', $request->BatchCode)
            ->where('BatchStatus', self::BATCH_STATUS_GENERATED)
            ->first();

        if (!$batch) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'No generated batch found for rollback.');
            return redirect()->route('salarypay');
        }

        DB::beginTransaction();

        try {
            $snapshot = json_decode($batch->SnapshotJson, true);

            if (is_array($snapshot)) {
                $this->restorePayrollSnapshot($snapshot);
            }

            TempSalary::where('BatchCode', $batch->BatchCode)->delete();

            $batch->BatchStatus = self::BATCH_STATUS_ROLLED_BACK;
            $batch->save();

            DB::commit();

            Alert::success('Congrats ' . auth()->user()->FirstName, 'Generated payroll rolled back successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Rollback Payroll Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        }

        return redirect()->route('salarypay');
    }

    /**
     * Move an already-generated (not yet submitted) batch to a
     * different payroll month.
     */
    public function changeGeneratedPayrollMonth(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Change Generated Payroll Month');

        $request->validate([
            'BatchCode'    => 'required',
            'PayrollMonth' => 'required|date_format:Y-m',
        ]);

        $batch = PayrollBatch::where('BatchCode', $request->BatchCode)
            ->where('BatchStatus', self::BATCH_STATUS_GENERATED)
            ->first();

        if (!$batch) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'Generated batch not found.');
            return redirect()->route('salarypay');
        }

        if (SalaryPaid::where('PaidMonth', $request->PayrollMonth)->exists()) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'Selected month already submitted in the payroll paid table.');
            return redirect()->route('salarypay');
        }

        try {
            TempSalary::where('BatchCode', $batch->BatchCode)->update([
                'PaidMonth' => $request->PayrollMonth,
            ]);

            $batch->PayrollMonth = $request->PayrollMonth;
            $batch->save();

            Alert::success('Congrats ' . auth()->user()->FirstName, 'Payroll month changed successfully.');
        } catch (\Throwable $th) {
            Log::error('Change Payroll Month Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        }

        return redirect()->route('salarypay');
    }

    /*
    |--------------------------------------------------------------------
    | Snapshot / Rollback support
    |--------------------------------------------------------------------
    */

    /**
     * Capture the "before" state of every source table a payroll run
     * will mutate, so a rollback can restore it exactly.
     */
    private function buildPayrollSnapshot(): array
    {
        return [
            'staff_overtimes' => StaffOvertime::where('AppStatus', 'Approved')
                ->where(function ($q) {
                    $q->where('PayStatus', 'Pending')
                        ->orWhere(function ($q2) {
                            $q2->where('PayStatus', 'Paid')
                                ->where('AdvanceStatus', 'Paid')
                                ->where('AdvanceCondition', 'Pending');
                        });
                })
                ->select('id', 'PayStatus', 'AdvanceStatus', 'AdvanceCondition')
                ->get()
                ->toArray(),

            'staff_heslbs' => StaffHeslb::whereIn('LoanStatus', ['Pending', 'Active', 'Complete'])
                ->select('id', 'PaidAmount', 'LoanStatus')
                ->get()
                ->toArray(),

            'staff_advnc_slries' => StaffAdvncSlry::whereIn('AdvStatus', ['Pending', 'Complete'])
                ->select('id', 'PaidAmount', 'AdvStatus')
                ->get()
                ->toArray(),

            'staff_absents' => StaffAbsent::whereIn('AbsentStatus', ['Pending', 'Dedacted/Complete'])
                ->select('id', 'AbsentStatus')
                ->get()
                ->toArray(),

            'staff_absent_calenders' => StaffAbsentCalender::whereIn('AbsentStatus', ['Submited', 'Dedacted/Complete'])
                ->select('id', 'AbsentStatus')
                ->get()
                ->toArray(),

            'emp_slr_adv_costs' => EmpSlrAdvCost::whereIn('Status', ['Active', 'Paid'])
                ->select('id', 'OutsAmount', 'PaidAmount', 'Status')
                ->get()
                ->toArray(),
        ];
    }

    /**
     * Reverse a payroll run by writing every snapshotted row back to
     * its pre-generation values.
     */
    private function restorePayrollSnapshot(array $snapshot): void
    {
        foreach ($snapshot['staff_overtimes'] ?? [] as $row) {
            StaffOvertime::where('id', $row['id'])->update([
                'PayStatus'        => $row['PayStatus'] ?? null,
                'AdvanceStatus'    => $row['AdvanceStatus'] ?? null,
                'AdvanceCondition' => $row['AdvanceCondition'] ?? null,
            ]);
        }

        foreach ($snapshot['staff_heslbs'] ?? [] as $row) {
            StaffHeslb::where('id', $row['id'])->update([
                'PaidAmount' => $row['PaidAmount'] ?? 0,
                'LoanStatus' => $row['LoanStatus'] ?? null,
            ]);
        }

        foreach ($snapshot['staff_advnc_slries'] ?? [] as $row) {
            StaffAdvncSlry::where('id', $row['id'])->update([
                'PaidAmount' => $row['PaidAmount'] ?? 0,
                'AdvStatus'  => $row['AdvStatus'] ?? null,
            ]);
        }

        foreach ($snapshot['staff_absents'] ?? [] as $row) {
            StaffAbsent::where('id', $row['id'])->update([
                'AbsentStatus' => $row['AbsentStatus'] ?? null,
            ]);
        }

        foreach ($snapshot['staff_absent_calenders'] ?? [] as $row) {
            StaffAbsentCalender::where('id', $row['id'])->update([
                'AbsentStatus' => $row['AbsentStatus'] ?? null,
            ]);
        }

        foreach ($snapshot['emp_slr_adv_costs'] ?? [] as $row) {
            EmpSlrAdvCost::where('id', $row['id'])->update([
                'OutsAmount' => $row['OutsAmount'] ?? 0,
                'PaidAmount' => $row['PaidAmount'] ?? 0,
                'Status'     => $row['Status'] ?? null,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------
    | Per-employee deduction / allowance resolvers
    |--------------------------------------------------------------------
    | Each of these resolves and settles ONE deduction/allowance type
    | for one employee, mutating the source record and returning the
    | amount to fold into this run's payroll row. They intentionally
    | do NOT catch exceptions — a failure here should abort the whole
    | run via the DB transaction in generatePayrollByMonth(), not
    | silently return a redirect in place of a number.
    */

    private function processOvertime(int $employeeId, string $month): float
    {
        $pendingOvertime = StaffOvertime::where('Emp_id', $employeeId)
            ->where('AppStatus', 'Approved')
            ->where('PayStatus', 'Pending')
            ->get();

        if ($pendingOvertime->isEmpty()) {
            return 0;
        }

        foreach ($pendingOvertime as $overtime) {
            $overtime->PayStatus        = 'Paid';
            $overtime->PaidMonth        = $month;
            $overtime->AdvanceCondition = 'Not In Advance';
            $overtime->AdvanceStatus    = 'Not In Advance';
            $overtime->save();

            OvertPay::create([
                'User_id'       => auth()->user()->id,
                'Ovrt_id'       => $overtime->id,
                'Emp_id'        => $overtime->Emp_id,
                'AmntPay'       => $overtime->PayAmnt,
                'DatePaid'      => Carbon::now()->toDateString(),
                'CashOutStatus' => 'Cashed-Out',
                'Condition'     => 'Cleared',
            ]);
        }

        return (float) $pendingOvertime->sum('PayAmnt');
    }

    private function processOvertimeInAdvance(int $employeeId): float
    {
        $overtimeInAdvance = StaffOvertime::where('Emp_id', $employeeId)
            ->where('AppStatus', 'Approved')
            ->where('PayStatus', 'Paid')
            ->where('AdvanceStatus', 'Paid')
            ->where('AdvanceCondition', 'Pending')
            ->get();

        if ($overtimeInAdvance->isEmpty()) {
            return 0;
        }

        StaffOvertime::whereIn('id', $overtimeInAdvance->pluck('id'))
            ->update(['AdvanceCondition' => 'Cleared']);

        return (float) $overtimeInAdvance->sum('PayAmnt');
    }

    private function getAllowance(int $employeeId): float
    {
        $allowance = SlrAllwnc::where('PayStaus', 'Pending')
            ->where('Paid_id', $employeeId)
            ->first();

        if (!$allowance) {
            return 0;
        }

        $allowance->update(['PayStaus' => 'Paid']);

        return (float) $allowance->AllwAmount;
    }

    private function processHeslb(int $employeeId): float
    {
        $loan = StaffHeslb::where('LoanStatus', 'Pending')
            ->where('Emp_id', $employeeId)
            ->first();

        if (!$loan) {
            return 0;
        }

        $remaining = $loan->LoanAmount - $loan->PaidAmount;
        $installment = min($remaining, $loan->InstallAmount);

        $loan->PaidAmount += $installment;
        if ($installment >= $remaining) {
            $loan->LoanStatus = 'Complete';
        }
        $loan->save();

        return (float) $installment;
    }

    private function processBcabd(int $employeeId, string $month): float
    {
        $loan = EmpSlrAdvCost::where('Paid_id', $employeeId)
            ->where('Status', 'Active')
            ->where('OutsAmount', '>', 0)
            ->orderBy('id')
            ->first();

        if (!$loan) {
            return 0;
        }

        $installment  = (float) $loan->Amount;
        $outstanding  = (float) $loan->OutsAmount;
        $deductAmount = min($installment, $outstanding);

        $loan->PaidMonth  = $month;
        $loan->PaidAmount = (float) $loan->PaidAmount + $deductAmount;
        $loan->OutsAmount = $outstanding - $deductAmount;

        if ($loan->OutsAmount <= 0) {
            $loan->OutsAmount = 0;
            $loan->Status     = 'Paid';
        }

        $loan->save();

        return $deductAmount;
    }

    private function processAdvance(int $employeeId): float
    {
        $advance = StaffAdvncSlry::where('AdvStatus', 'Pending')
            ->where('Emp_id', $employeeId)
            ->first();

        if (!$advance) {
            return 0;
        }

        $remaining = $advance->AdvanceAmount - $advance->PaidAmount;
        $installment = min($remaining, $advance->InstallAmount);

        $advance->PaidAmount += $installment;
        if ($installment >= $remaining) {
            $advance->AdvStatus = 'Complete';
        }
        $advance->save();

        return (float) $installment;
    }

    private function processAbsent(int $employeeId): float
    {
        $absence = StaffAbsent::where('AbsentStatus', 'Submited')
            ->where('Emp_id', $employeeId)
            ->first();

        if (!$absence) {
            return 0;
        }

        $absence->update(['AbsentStatus' => 'Dedacted/Complete']);

        StaffAbsentCalender::where('AbsentStatus', 'Submited')
            ->where('Emp_id', $employeeId)
            ->update(['AbsentStatus' => 'Dedacted/Complete']);

        return (float) $absence->DedactAmount;
    }

    /*
    |--------------------------------------------------------------------
    | Calculation helpers
    |--------------------------------------------------------------------
    */

    private function calculateNetSalary(float $grossAmount, float $allowance, float $absentDeduct, float $overtime, float $overtimeInAdvance): float
    {
        $salary        = $grossAmount + $allowance + $overtime + $overtimeInAdvance - $absentDeduct;
        $taxableIncome = $salary - ($salary * self::NSSF_EMPLOYEE_RATE);
        $paye          = $this->calculatePaye($taxableIncome);

        return $salary - (($salary * self::NSSF_EMPLOYEE_RATE) + $paye);
    }

    private function calculatePaye(float $taxableIncome): float
    {
        foreach (self::PAYE_BRACKETS as $bracket) {
            if ($taxableIncome >= $bracket['min']) {
                return ($taxableIncome - $bracket['min']) * $bracket['rate'] + $bracket['base'];
            }
        }

        return 0;
    }

    /**
     * Solve for the gross salary that produces the given net pay,
     * via bisection (PAYE bands make this non-invertible algebraically).
     */
    private function calculateGrossFromNet(float $netSalary): float
    {
        $low  = $netSalary;
        $high = $netSalary * 2;

        while (abs($high - $low) > self::NET_TO_GROSS_PRECISION) {
            $mid = ($low + $high) / 2;

            if ($this->calculateNetFromGross($mid) > $netSalary) {
                $high = $mid;
            } else {
                $low = $mid;
            }
        }

        return ($low + $high) / 2;
    }

    private function calculateNetFromGross(float $gross): float
    {
        $nssf          = $gross * self::NSSF_EMPLOYEE_RATE;
        $taxableIncome = $gross - $nssf;
        $paye          = $this->calculatePaye($taxableIncome);

        return $gross - ($nssf + $paye);
    }

    /**
     * Current statutory rate (%) for the given company-side cost, e.g.
     * 'SDL', "NSSF Employer's", 'WCF'.
     */
    private function statutoryRate(string $advName): float
    {
        $rate = CompSlrAdvCost::where('AdvName', $advName)
            ->where('Status', 'Active')
            ->first();

        if (!$rate) {
            throw new \RuntimeException("Missing active statutory rate configuration for \"{$advName}\".");
        }

        return (float) $rate->Amount;
    }

    /*
    |--------------------------------------------------------------------
    | Manual adjustments (per-employee, within the active batch)
    |--------------------------------------------------------------------
    */

    public function editsalary($id)
    {
        // \LogActivity::addToLog('Edit Staff Gross Salary');

        $salary = TempSalary::where('Emply_id', decrypt($id))->first();

        return view('in.salary.editsalary', compact('salary'));
    }

    public function tempsalaryreg(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Update Staff Gross Salary');

        $request->validate([
            'AmountPaid' => 'required|numeric|min:0',
            'Condition'  => 'required',
        ]);

        try {
            $grossSalary   = (float) $request->AmountPaid;
            $employeeNssf  = $grossSalary * self::NSSF_EMPLOYEE_RATE;
            $paye          = $this->calculatePaye($grossSalary - $employeeNssf);

            $salary = TempSalary::findOrFail($request->Salary_id);
            $salary->AmountPaid = $grossSalary;
            $salary->EmpNssf    = $employeeNssf;
            $salary->Paye       = $paye;
            $salary->NetPay     = $grossSalary - ($employeeNssf + $paye);
            $salary->SdlPay     = $grossSalary * ($this->statutoryRate('SDL') / 100);
            $salary->NssfPay    = $grossSalary * ($this->statutoryRate("NSSF Employer's") / 100);
            $salary->WcfPay     = $grossSalary * ($this->statutoryRate('WCF') / 100);
            $salary->Emply_id   = $request->Emply_id;
            $salary->User_id    = auth()->user()->id;
            $salary->Condition  = $request->Condition;
            $salary->save();

            Alert::success('Congrats ' . auth()->user()->FirstName, "You've updated the staff gross salary details successfully");
        } catch (\Throwable $th) {
            Log::error('Update Gross Salary Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        }

        return redirect()->route('salarypay');
    }

    public function editnetsalary($id)
    {
        // \LogActivity::addToLog('Edit Staff Net Salary');

        $salary = TempSalary::where('Emply_id', decrypt($id))->first();

        return view('in.salary.editnetsalary', compact('salary'));
    }

    public function tempnetsalaryreg(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Submit Updated Staff Net Salary');

        $request->validate([
            'AmountPaid' => 'required|numeric|min:0',
            'Condition'  => 'required',
        ]);

        try {
            $salary = TempSalary::findOrFail($request->Salary_id);
            $salary->NetPay    = $request->AmountPaid;
            $salary->Emply_id  = $request->Emply_id;
            $salary->User_id   = auth()->user()->id;
            $salary->Condition = $request->Condition;
            $salary->save();

            Alert::success('Congrats ' . auth()->user()->FirstName, "You've updated the staff net salary successfully");
        } catch (\Throwable $th) {
            Log::error('Update Net Salary Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        }

        return redirect()->route('salarypay');
    }

    public function editsalarydetails($id): RedirectResponse
    {
        // \LogActivity::addToLog('Edit Staff Gross Salary Details');

        try {
            $salary = TempSalary::where('Emply_id', decrypt($id))->first();

            if (!$salary) {
                Alert::error('Sorry! ' . auth()->user()->FirstName, 'Salary details not found for the selected employee.');
                return redirect()->route('salarypay');
            }

            return view('in.salary.editsalarydetails', compact('salary'));
        } catch (\Throwable $th) {
            Log::error('Edit Gross Salary Details Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
            return redirect()->route('salarypay');
        }
    }

    public function tempsalarydetailsreg(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Update Staff Gross Salary Details');

        $request->validate([
            'Salary_id' => 'required',
            'Condition' => 'required',
            'Allowance' => 'nullable|numeric|min:0',
            'Overtime'  => 'nullable|numeric|min:0',
            'Absent'    => 'nullable|numeric|min:0',
        ]);

        try {
            $salary = TempSalary::find($request->Salary_id);

            if (!$salary) {
                Alert::error('Sorry! ' . auth()->user()->FirstName, 'Salary record not found.');
                return redirect()->route('salarypay');
            }

            $allowance = (float) ($request->Allowance ?? 0);
            $overtime  = (float) ($request->Overtime ?? 0);
            $absent    = (float) ($request->Absent ?? 0);

            $gross = max(0, (float) $request->GrossAmmount + $allowance + $overtime - $absent);

            $employeeNssf  = $gross * self::NSSF_EMPLOYEE_RATE;
            $taxableIncome = $gross - $employeeNssf;
            $paye          = $this->calculatePaye($taxableIncome);

            $otherDeductions = (float) ($salary->Heslb ?? 0)
                + (float) ($salary->Bcabd ?? 0)
                + (float) ($salary->Advance ?? 0)
                + (float) ($salary->OvtmAdvn ?? 0);

            $netPay = max(0, $gross - ($employeeNssf + $paye + $otherDeductions));

            $salary->Allowance  = $allowance;
            $salary->Overtime   = $overtime;
            $salary->Absent     = $absent;
            $salary->AmountPaid = $gross;
            $salary->EmpNssf    = $employeeNssf;
            $salary->Paye       = $paye;
            $salary->NetPay     = $netPay;
            $salary->SdlPay     = $gross * ($this->statutoryRate('SDL') / 100);
            $salary->NssfPay    = $gross * ($this->statutoryRate("NSSF Employer's") / 100);
            $salary->WcfPay     = $gross * ($this->statutoryRate('WCF') / 100);
            $salary->Condition  = $request->Condition;
            $salary->User_id    = auth()->user()->id;
            $salary->save();

            Alert::success('Congrats ' . auth()->user()->FirstName, 'Gross payroll details updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Update Gross Salary Details Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        }

        return redirect()->route('salarypay');
    }








    /*
    |--------------------------------------------------------------------
    | Queue / history
    |--------------------------------------------------------------------
    */

    /**
     * Approval queue: batches awaiting a decision, plus a recent
     * history of approved/rejected batches for audit reference.
     */
    public function payrollApprovalIndex()
    {
        try {
            // \LogActivity::addToLog('View Payroll Approval Queue');

            $pending = PayrollBatch::where('BatchStatus', self::STATUS_PENDING_APPROVAL)
                ->with('submittedBy')
                ->orderBy('SubmittedAt')
                ->get();

            $history = PayrollBatch::whereIn('BatchStatus', [self::STATUS_APPROVED, self::STATUS_GENERATED])
                ->whereNotNull('SubmittedAt')
                ->with(['submittedBy', 'approvedBy'])
                ->orderByDesc('id')
                ->limit(30)
                ->get();

            return view('in.salary.payrollApprovalIndex', compact('pending', 'history'));
        } catch (\Throwable $th) {
            Log::error('Payroll Approval Queue Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
            return back();
        }
    }

    /**
     * Detail view of a single batch — the draft rows if still pending,
     * or the finalized rows from salary_paids if already approved.
     */
    public function payrollBatchReview(string $batchCode)
    {
        // try {
            // \LogActivity::addToLog('Review Payroll Batch: ' . $batchCode);

            $batch = PayrollBatch::where('BatchCode', $batchCode)
                ->with(['submittedBy', 'approvedBy', 'rejectedBy'])
                ->first();

            if (!$batch) {
                Alert::error('Sorry! ' . auth()->user()->FirstName, 'Payroll batch not found.');
                return redirect()->route('payrollApprovalIndex');
            }

            $rows = $batch->BatchStatus === self::STATUS_APPROVED
                ? SalaryPaid::where('BatchCode', $batchCode)->with('employee.user')->get()
                : TempSalary::where('BatchCode', $batchCode)->with('employee.user')->get();

            return view('in.salary.payrollBatchReview', compact('batch', 'rows'));
        // } catch (\Throwable $th) {
        //     Log::error('Payroll Batch Review Error: ' . $th->getMessage());
        //     Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        //     return redirect()->route('payrollApprovalIndex');
        // }
    }

    /*
    |--------------------------------------------------------------------
    | Lifecycle actions
    |--------------------------------------------------------------------
    */

    /**
     * HR sends a fully-drafted batch for approval. No further edits
     * should happen to it until it's rejected back to Generated.
     */
    public function submitPayrollForApproval(Request $request)
    {
        // \LogActivity::addToLog('Submit Payroll Batch For Approval');

        $request->validate([
            'BatchCode' => 'required|exists:payroll_batches,BatchCode',
        ]);

        $batch = PayrollBatch::where('BatchCode', $request->BatchCode)
            ->where('BatchStatus', self::STATUS_GENERATED)
            ->first();

        if (!$batch) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'Only a freshly generated batch can be submitted for approval.');
            return redirect()->route('salarypay');
        }

        // try {
            $batch->BatchStatus = self::STATUS_PENDING_APPROVAL;
            $batch->SubmittedBy = auth()->user()->id;
            $batch->SubmittedAt = now();
            $batch->save();

            Alert::success('Submitted ' . auth()->user()->FirstName, 'Payroll batch ' . $batch->BatchCode . ' has been sent for approval.');
        // } catch (\Throwable $th) {
        //     Log::error('Submit Payroll Batch Error: ' . $th->getMessage());
        //     Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        // }

        return redirect()->route('salarypay');
    }

    /**
     * Approve a pending batch: migrate every draft row into
     * salary_paids, then clear it out of temp_salaries.
     */
    public function approvePayroll(Request $request)
    {
        // \LogActivity::addToLog('Approve Payroll Batch');

        $request->validate([
            'BatchCode' => 'required|exists:payroll_batches,BatchCode',
        ]);

        $batch = PayrollBatch::where('BatchCode', $request->BatchCode)
            ->where('BatchStatus', self::STATUS_PENDING_APPROVAL)
            ->first();

        if (!$batch) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'Only a batch awaiting approval can be approved.');
            return redirect()->route('payrollApprovalIndex');
        }

        DB::beginTransaction();

        // try {
            $draftRows = TempSalary::where('BatchCode', $batch->BatchCode)->get();

            if ($draftRows->isEmpty()) {
                throw new \RuntimeException('No draft salary rows found for this batch — nothing to approve.');
            }

            foreach ($draftRows as $row) {
                SalaryPaid::create([
                    'Employee_id'    => $row->Emply_id,
                    'ActualGross' => $row->ActualGross,
                    'Allowance'   => $row->Allowance,
                    'Overtime'    => $row->Overtime,
                    'OvtmAdvn'    => $row->OvtmAdvn,
                    'Heslb'       => $row->Heslb,
                    'Absent'      => $row->Absent,
                    'Bcabd'       => $row->Bcabd,
                    'Advance'     => $row->Advance,
                    'AmountPaid'  => $row->AmountPaid,
                    'EmpNssf'     => $row->EmpNssf,
                    'Paye'        => $row->Paye,
                    'NetPay'      => $row->NetPay,
                    'SdlPay'      => $row->SdlPay,
                    'NssfPay'     => $row->NssfPay,
                    'WcfPay'      => $row->WcfPay,
                    'PaidMonth'   => $row->PaidMonth,
                    'BatchCode'   => $row->BatchCode,
                    'DatePaid'    => now()->toDateString(),
                    'User_id'     => auth()->user()->id,
                    'Condition'   => $row->Condition,
                    'Status'      => 'Active',
                ]);
            }

            TempSalary::where('BatchCode', $batch->BatchCode)->delete();

            $batch->BatchStatus = self::STATUS_APPROVED;
            $batch->ApprovedBy  = auth()->user()->id;
            $batch->ApprovedAt  = now();
            $batch->save();

            DB::commit();

            Alert::success('Approved ' . auth()->user()->FirstName, 'Payroll batch ' . $batch->BatchCode . ' has been approved and submitted to payroll records.');
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     Log::error('Approve Payroll Batch Error: ' . $th->getMessage());
        //     Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        // }

        return redirect()->route('payrollApprovalIndex');
    }

    /**
     * Reject a pending batch back to Generated so HR can correct and
     * resubmit it. Draft rows are left untouched — nothing is lost.
     */
    public function rejectPayroll(Request $request): RedirectResponse
    {
        // \LogActivity::addToLog('Reject Payroll Batch');

        $request->validate([
            'BatchCode'       => 'required|exists:payroll_batches,BatchCode',
            'RejectionReason' => 'required|string|max:500',
        ]);

        $batch = PayrollBatch::where('BatchCode', $request->BatchCode)
            ->where('BatchStatus', self::STATUS_PENDING_APPROVAL)
            ->first();

        if (!$batch) {
            Alert::warning('Sorry! ' . auth()->user()->FirstName, 'Only a batch awaiting approval can be rejected.');
            return redirect()->route('payrollApprovalIndex');
        }

        try {
            $batch->BatchStatus     = self::STATUS_GENERATED;
            $batch->RejectedBy      = auth()->user()->id;
            $batch->RejectedAt      = now();
            $batch->RejectionReason = $request->RejectionReason;
            $batch->save();

            Alert::warning('Rejected ' . auth()->user()->FirstName, 'Payroll batch ' . $batch->BatchCode . ' has been sent back to HR for correction.');
        } catch (\Throwable $th) {
            Log::error('Reject Payroll Batch Error: ' . $th->getMessage());
            Alert::error('Sorry! ' . auth()->user()->FirstName, self::GENERIC_ERROR_MESSAGE);
        }

        return redirect()->route('payrollApprovalIndex');
    }




    // STAFF ADVANCE SALARIES INFORMATION
    public function staffadvncslries()
    {
        // try {
            $advances = StaffAdvncSlry::with(['employee'])->latest()->get();
            $employees = Employee::where('Status', 'Active')->get();
            return view('in.salary.staffadvncslries.staffadvncslries', compact('advances', 'employees'));

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! ' . auth()->user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back();
        // }
    }

    public function storestaffadvncslries(Request $request)
    {
        try {

            $request->validate([
                'Emp_id'        => 'required|exists:employees,id',
                'AdvanceAmount' => 'required|numeric|min:0',
                'InstallAmount' => 'required|numeric|min:0',
            ]);

            StaffAdvncSlry::create([
                'Emp_id'        => $request->Emp_id,
                'AdvanceAmount' => $request->AdvanceAmount,
                'PaidAmount'    => 0, // Default paid amount on creation
                'InstallAmount' => $request->InstallAmount,
                'AdvStatus'     => 'Pending',
                'created_by'    => auth()->id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff advance salary recorded successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function viewstaffadvncslries($id)
    {
        // try {
            $id = Crypt::decrypt($id);

            $advance = StaffAdvncSlry::with(['employee'])->findOrFail($id);

            return view('in.salary.staffadvncslries.viewstaffadvncslries', compact('advance'));

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! ' . auth()->user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back();
        // }
    }

    public function editstaffadvncslries($id)
    {
        // try {
            $id = Crypt::decrypt($id);

            $advance = StaffAdvncSlry::with(['employee'])->findOrFail($id);
            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffadvncslries.editstaffadvncslries', compact('advance', 'employees'));

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! ' . auth()->user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back();
        // }
    }

    public function updatestaffadvncslries(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'Emp_id'        => 'required|exists:employees,id',
                'AdvanceAmount' => 'required|numeric|min:0',
                'PaidAmount'    => 'required|numeric|min:0',
                'InstallAmount' => 'required|numeric|min:0',
                'AdvStatus'     => 'required|string',
            ]);

            $advance = StaffAdvncSlry::findOrFail($id);

            $advance->update([
                'Emp_id'        => $request->Emp_id,
                'AdvanceAmount' => $request->AdvanceAmount,
                'PaidAmount'    => $request->PaidAmount,
                'InstallAmount' => $request->InstallAmount,
                'AdvStatus'     => $request->AdvStatus,
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff advance salary updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deletestaffadvncslries($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $advance = StaffAdvncSlry::findOrFail($id);

            $advance->update([
                'AdvStatus' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff advance salary deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restorestaffadvncslries($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $advance = StaffAdvncSlry::findOrFail($id);

            $advance->update([
                'AdvStatus' => 'Pending',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff advance salary restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }


    // STAFF OVERTIMES INFORMATION
    public function staffovertimes()
    {
        try {
            $overtimes = StaffOvertime::with(['employee', 'overtPay'])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffovertimes.staffovertimes', compact('overtimes', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storestaffovertimes(Request $request)
    {
        try {

            $request->validate([
                'Emp_id'           => 'required|exists:employees,id',
                'PayAmnt'          => 'required|numeric|min:0',
                'AppStatus'        => 'nullable|string|max:50',
                'PayStatus'        => 'nullable|string|max:50',
                'AdvanceStatus'    => 'nullable|string|max:50',
                'AdvanceCondition' => 'nullable|string|max:255',
                'PaidMonth'        => 'required|string|max:20',
            ]);

            StaffOvertime::create([
                'Emp_id'           => $request->Emp_id,
                'PayAmnt'          => $request->PayAmnt,
                'AppStatus'        => $request->AppStatus ?? 'Pending',
                'PayStatus'        => $request->PayStatus ?? 'Unpaid',
                'AdvanceStatus'    => $request->AdvanceStatus ?? 'None',
                'AdvanceCondition' => $request->AdvanceCondition,
                'PaidMonth'        => $request->PaidMonth,
                'created_by'       => auth()->id(),
                'Status'           => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff overtime recorded successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function viewstaffovertimes($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $overtime = StaffOvertime::with(['employee', 'overtPay'])->findOrFail($id);

            return view('in.salary.viewstaffovertimes', compact('overtime'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editstaffovertimes($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $overtime = StaffOvertime::with(['employee', 'overtPay'])->findOrFail($id);
            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffovertimes.editstaffovertimes', compact('overtime', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updatestaffovertimes(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'Emp_id'           => 'required|exists:employees,id',
                'PayAmnt'          => 'required|numeric|min:0',
                'AppStatus'        => 'required|string|max:50',
                'PayStatus'        => 'required|string|max:50',
                'AdvanceStatus'    => 'nullable|string|max:50',
                'AdvanceCondition' => 'nullable|string|max:255',
                'PaidMonth'        => 'required|string|max:20',
            ]);

            $overtime = StaffOvertime::findOrFail($id);

            $overtime->update([
                'Emp_id'           => $request->Emp_id,
                'PayAmnt'          => $request->PayAmnt,
                'AppStatus'        => $request->AppStatus,
                'PayStatus'        => $request->PayStatus,
                'AdvanceStatus'    => $request->AdvanceStatus,
                'AdvanceCondition' => $request->AdvanceCondition,
                'PaidMonth'        => $request->PaidMonth,
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff overtime updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deletestaffovertimes($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $overtime = StaffOvertime::findOrFail($id);

            $overtime->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff overtime record deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restorestaffovertimes($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $overtime = StaffOvertime::findOrFail($id);

            $overtime->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff overtime record restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }


    // SALARY ALLOWANCES INFORMATION
    public function slrallwncs()
    {
        try {
            $allowances = SlrAllwnc::with(['employee'])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.slrallwncs.slrallwncs', compact('allowances', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storeslrallwncs(Request $request)
    {
        try {

            $request->validate([
                'Paid_id'    => 'required|exists:employees,id',
                'AllwAmount' => 'required|numeric|min:0',
                'PayStaus'   => 'nullable|string|max:50',
            ]);

            SlrAllwnc::create([
                'Paid_id'    => $request->Paid_id,
                'AllwAmount' => $request->AllwAmount,
                'PayStaus'   => $request->PayStaus ?? 'Unpaid',
                'created_by' => auth()->id(),
                'Status'     => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Salary allowance recorded successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function viewslrallwncs($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $allowance = SlrAllwnc::with(['employee'])->findOrFail($id);

            return view('in.salary.slrallwncs.viewslrallwncs', compact('allowance'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editslrallwncs($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $allowance = SlrAllwnc::with(['employee'])->findOrFail($id);
            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.slrallwncs.editslrallwncs', compact('allowance', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updateslrallwncs(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'Paid_id'    => 'required|exists:employees,id',
                'AllwAmount' => 'required|numeric|min:0',
                'PayStaus'   => 'required|string|max:50',
            ]);

            $allowance = SlrAllwnc::findOrFail($id);

            $allowance->update([
                'Paid_id'    => $request->Paid_id,
                'AllwAmount' => $request->AllwAmount,
                'PayStaus'   => $request->PayStaus,
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Salary allowance updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deleteslrallwncs($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $allowance = SlrAllwnc::findOrFail($id);

            $allowance->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Salary allowance record deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restoreslrallwncs($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $allowance = SlrAllwnc::findOrFail($id);

            $allowance->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Salary allowance record restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }



    // STAFF ABSENT CALENDERS INFORMATION
    public function staffabsentcalenders()
    {
        try {
            $absentCalenders = StaffAbsentCalender::with(['employee'])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffabsentcalenders.staffabsentcalenders', compact('absentCalenders', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storestaffabsentcalenders(Request $request)
    {
        try {

            $request->validate([
                'Emp_id'       => 'required|exists:employees,id',
                'AbsentDate'   => 'required|date',
                'AbsentStatus' => 'nullable|string|max:50',
            ]);

            StaffAbsentCalender::create([
                'Emp_id'       => $request->Emp_id,
                'AbsentDate'   => $request->AbsentDate,
                'AbsentStatus' => $request->AbsentStatus ?? 'Pending',
                'created_by'   => auth()->id(),
                'Status'       => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff absent calendar record added successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function viewstaffabsentcalenders($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $absentCalender = StaffAbsentCalender::with(['employee'])->findOrFail($id);

            return view('in.salary.staffabsentcalenders.viewstaffabsentcalenders', compact('absentCalender'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editstaffabsentcalenders($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $absentCalender = StaffAbsentCalender::with(['employee'])->findOrFail($id);
            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffabsentcalenders.editstaffabsentcalenders', compact('absentCalender', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updatestaffabsentcalenders(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'Emp_id'       => 'required|exists:employees,id',
                'AbsentDate'   => 'required|date',
                'AbsentStatus' => 'required|string|max:50',
            ]);

            $absentCalender = StaffAbsentCalender::findOrFail($id);

            $absentCalender->update([
                'Emp_id'       => $request->Emp_id,
                'AbsentDate'   => $request->AbsentDate,
                'AbsentStatus' => $request->AbsentStatus,
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff absent calendar record updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deletestaffabsentcalenders($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $absentCalender = StaffAbsentCalender::findOrFail($id);

            $absentCalender->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff absent calendar record deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restorestaffabsentcalenders($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $absentCalender = StaffAbsentCalender::findOrFail($id);

            $absentCalender->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Staff absent calendar record restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }


    // LIST ALL ABSENT DEDUCTIONS
    public function staffabsent()
    {
        try {
            $absents = StaffAbsent::with(['employee'])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffabsent.staffabsent', compact('absents', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    // CALCULATE & STORE ABSENT DEDUCTION BASED ON ABSENT CALENDAR DATES
    public function storestaffabsent(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'Emp_id' => 'required|exists:employees,id',
            ]);

            $employee = Employee::findOrFail($request->Emp_id);

            // 1. Calculate Payroll Cycle Window (16th of previous month to 15th of current month)
            $now = Carbon::now();
            if ($now->day >= 16) {
                $fromDate = Carbon::create($now->year, $now->month, 16)->startOfDay();
                $toDate   = Carbon::create($now->year, $now->month, 15)->addMonth()->endOfDay();
            } else {
                $fromDate = Carbon::create($now->year, $now->month, 16)->subMonth()->startOfDay();
                $toDate   = Carbon::create($now->year, $now->month, 15)->endOfDay();
            }

            // 2. Fetch pending absent calendar records within payroll cycle
            $absentDates = StaffAbsentCalender::where('Emp_id', $employee->id)
                ->where('Status', 'Active')
                ->whereIn('AbsentStatus', ['Pending', 'Approved'])
                ->whereBetween('AbsentDate', [$fromDate->format('Y-m-d'), $toDate->format('Y-m-d')])
                ->get();

            if ($absentDates->isEmpty()) {
                Alert::warning(
                    'No Absent Records',
                    'No pending absent dates found for this employee in the current payroll cycle (' . $fromDate->format('d M Y') . ' - ' . $toDate->format('d M Y') . ').'
                );
                return back();
            }

            // 3. Compute deductible hours based on day of week
            $totalAbsentHours = 0;
            $totalAbsentDays  = 0;

            foreach ($absentDates as $record) {
                $date = Carbon::parse($record->AbsentDate);
                
                if ($date->isSaturday()) {
                    $totalAbsentHours += 5;
                    $totalAbsentDays += 1;
                } elseif (!$date->isSunday()) { // Monday to Friday
                    $totalAbsentHours += 8;
                    $totalAbsentDays += 1;
                }
                // Sundays count as 0 deductible hours
            }

            if ($totalAbsentHours <= 0) {
                Alert::warning('Warning', 'Selected absent dates fall on non-deductible days (e.g., Sundays).');
                return back();
            }

            // 4. Deduction Amount Calculation
            $grossSalary   = $employee->gross_salary ?? $employee->salary ?? 0;
            $legalBaseHours = 194.985;
            
            $hourlyRate = $grossSalary / $legalBaseHours;
            $rawDeduction = $totalAbsentHours * $hourlyRate;

            // Minimum salary protection check (If remaining pay <= 5 hours of rate, deduct total gross)
            $remainingSalary = $grossSalary - $rawDeduction;
            $minimumPayable  = 5 * $hourlyRate;

            if ($remainingSalary <= $minimumPayable) {
                $finalDeduction = $grossSalary;
            } else {
                $finalDeduction = round($rawDeduction, 2);
            }

            // 5. Save StaffAbsent Record
            $staffAbsent = StaffAbsent::create([
                'Emp_id'       => $employee->id,
                'DedactAmount' => $finalDeduction,
                'AbsentStatus' => 'Submited',
                'created_by'   => auth()->id(),
                'Status'       => 'Active',
            ]);

            // 6. Update StaffAbsentCalender status
            StaffAbsentCalender::whereIn('id', $absentDates->pluck('id'))
                ->update(['AbsentStatus' => 'Submited']);

            DB::commit();

            Alert::success(
                'Success ' . auth()->user()->name,
                'Absent deduction of TZS ' . number_format($finalDeduction, 2) . ' calculated and saved successfully for ' . $totalAbsentDays . ' days (' . $totalAbsentHours . ' hours).'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    // VIEW ABSENT DEDUCTION DETAILS
    public function viewstaffabsent($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $absent = StaffAbsent::with(['employee'])->findOrFail($id);

            return view('in.salary.staffabsent.viewstaffabsent', compact('absent'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    // EDIT ABSENT DEDUCTION
    public function editstaffabsent($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $absent = StaffAbsent::with(['employee'])->findOrFail($id);
            $employees = Employee::where('Status', 'Active')->get();

            return view('in.salary.staffabsent.editstaffabsent', compact('absent', 'employees'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    // UPDATE ABSENT DEDUCTION
    public function updatestaffabsent(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'DedactAmount' => 'required|numeric|min:0',
                'AbsentStatus' => 'required|string|max:50',
            ]);

            $absent = StaffAbsent::findOrFail($id);

            $absent->update([
                'DedactAmount' => $request->DedactAmount,
                'AbsentStatus' => $request->AbsentStatus,
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Absent deduction record updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    // DELETE ABSENT DEDUCTION
    public function deletestaffabsent($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $absent = StaffAbsent::findOrFail($id);

            $absent->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Absent deduction record deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    // RESTORE ABSENT DEDUCTION
    public function restorestaffabsent($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $absent = StaffAbsent::findOrFail($id);

            $absent->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Absent deduction record restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

}