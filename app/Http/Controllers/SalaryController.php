<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SalaryPaid;
use App\Models\Employee;
use App\Models\WeeklyAllowance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class SalaryController extends Controller
{
    /**
     * Display Salary Information
     */
    public function salaryinformations()
    {
        try {

            $datas = SalaryPaid::with(['employee.user'])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            return view('in.salaries.salaryinformations', compact('datas'));

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    /**
     * Generate Salary Page
     */
    public function registersalary()
    {
        try {

            return view('in.salaries.registersalary');

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    /**
     * Generate Monthly Salary
     */
    public function storesalary(Request $request)
    {
        $request->validate([
            'PaidMonth' => 'required|date_format:Y-m',
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Payroll
            |--------------------------------------------------------------------------
            */

            $exists = SalaryPaid::where('PaidMonth', $request->PaidMonth)
                ->where('Status', 'Active')
                ->exists();

            if ($exists) {

                Alert::warning('Warning', 'Salary for selected month has already been generated.');

                return back()->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | Get Active Employees Only
            |--------------------------------------------------------------------------
            */

            $employees = Employee::where('Status', 'Active')
                ->where('is_active', true)
                ->get();

            if ($employees->count() == 0) {

                Alert::warning('Warning', 'No active employees found.');

                return back();
            }

            /*
            |--------------------------------------------------------------------------
            | Generate Salary
            |--------------------------------------------------------------------------
            */

            foreach ($employees as $employee) {

                SalaryPaid::create([

                    'Employee_id'     => $employee->id,
                    'User_id'         => Auth::id(),

                    'ActualGross'     => $employee->basic_salary,
                    'AmountPaid'      => $employee->basic_salary,
                    'NetPay'          => $employee->basic_salary,

                    'Allowance'       => 0,
                    'Overtime'        => 0,

                    'Advance'         => 0,
                    'OvtmAdvn'        => 0,
                    'Heslb'           => 0,
                    'Absent'          => 0,
                    'Bcabd'           => 0,

                    'EmpNssf'         => 0,
                    'NssfPay'         => 0,
                    'Paye'            => 0,
                    'SdlPay'          => 0,
                    'WcfPay'          => 0,

                    'PayMode'         => null,

                    'PaidMonth'       => $request->PaidMonth,
                    'PayrollYear'     => date('Y', strtotime($request->PaidMonth)),

                    'PaidDate'        => null,
                    'NextPaidDate'    => null,

                    'Status'          => 'Active',
                    'Condition'       => 'Pending',
                    'ActionPay'       => 'Pending',

                    'ApprovalStatus'  => 'Pending',
                    'PaymentStatus'   => 'Pending',

                    'HrManager'       => 'Pending',
                    'HrDirector'      => 'Pending',
                    'FinManger'       => 'Pending',
                    'DafComnt'        => 'Pending',
                    'MdComnt'         => 'Pending',

                    'HrManagerComnt'  => null,
                    'HrDirectorComnt' => null,
                    'FinMangerComnt'  => null,
                    'DafComntComnt'   => null,
                    'MdComntComnt'    => null,

                    'PayrollComment'  => null,

                    'AuditingStatus'  => 'Pending',
                    'ReportStatus'    => 'Pending',
                ]);
            }

            DB::commit();

            Alert::success('Success', 'Monthly salary generated successfully.');

            return redirect()->route('salaryinformations');

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back()->withInput();
        }
    }

    public function viewsalary($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = SalaryPaid::findOrFail($id);

            $datas = SalaryPaid::with(['employee.user'])
                ->where('PaidMonth', $data->PaidMonth)
                ->where('PayrollYear', $data->PayrollYear)
                ->where('Status', 'Active')
                ->orderBy('Employee_id')
                ->get();

            return view(
                'in.salaries.viewsalary',
                compact('datas', 'data')
            );

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function updatesalary(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ActualGross' => 'required|array',
        ]);

        DB::beginTransaction();

        try {

            foreach ($request->ids as $key => $id) {

                $salary = SalaryPaid::findOrFail($id);

                $gross = $request->ActualGross[$key];
                $allowance = $request->Allowance[$key] ?? 0;
                $overtime = $request->Overtime[$key] ?? 0;

                $advance = $request->Advance[$key] ?? 0;
                $ovtmadvn = $request->OvtmAdvn[$key] ?? 0;
                $heslb = $request->Heslb[$key] ?? 0;
                $absent = $request->Absent[$key] ?? 0;
                $bcabd = $request->Bcabd[$key] ?? 0;

                $empnssf = $request->EmpNssf[$key] ?? 0;
                $nssf = $request->NssfPay[$key] ?? 0;
                $paye = $request->Paye[$key] ?? 0;
                $sdl = $request->SdlPay[$key] ?? 0;
                $wcf = $request->WcfPay[$key] ?? 0;

                $netPay =
                    ($gross + $allowance + $overtime)
                    - (
                        $advance +
                        $ovtmadvn +
                        $heslb +
                        $absent +
                        $bcabd +
                        $empnssf +
                        $nssf +
                        $paye +
                        $sdl +
                        $wcf
                    );

                $salary->update([

                    'ActualGross' => $gross,
                    'AmountPaid' => $gross,

                    'Allowance' => $allowance,
                    'Overtime' => $overtime,

                    'Advance' => $advance,
                    'OvtmAdvn' => $ovtmadvn,
                    'Heslb' => $heslb,
                    'Absent' => $absent,
                    'Bcabd' => $bcabd,

                    'EmpNssf' => $empnssf,
                    'NssfPay' => $nssf,
                    'Paye' => $paye,
                    'SdlPay' => $sdl,
                    'WcfPay' => $wcf,

                    'NetPay' => $netPay,

                    'User_id' => Auth::id(),

                ]);

            }

            DB::commit();

            Alert::success('Success', 'Salary updated successfully.');

            return back();

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back()->withInput();

        }
    }

    public function deletesalary($id)
    {
        DB::beginTransaction();

        try {

            $id = Crypt::decrypt($id);

            $salary = SalaryPaid::findOrFail($id);

            SalaryPaid::where('PaidMonth', $salary->PaidMonth)
                ->where('PayrollYear', $salary->PayrollYear)
                ->update([

                    'Status' => 'Deleted',
                    'User_id' => Auth::id(),

                ]);

            DB::commit();

            Alert::success('Success', 'Salary batch deleted successfully.');

            return back();

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function pendingsalary()
    {
        try {

            $datas = SalaryPaid::with(['employee.user'])
                ->where('Status', 'Active')
                ->where('ApprovalStatus', 'Pending')
                ->orderBy('PayrollYear', 'desc')
                ->orderBy('PaidMonth', 'desc')
                ->get()
                ->groupBy(function ($item) {
                    return $item->PayrollYear.'-'.$item->PaidMonth;
                });

            return view('in.salaries.pendingsalary', compact('datas'));

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function approvesalary($id)
    {
        DB::beginTransaction();

        try {

            $id = Crypt::decrypt($id);

            $salary = SalaryPaid::findOrFail($id);

            SalaryPaid::where('PaidMonth', $salary->PaidMonth)
                ->where('PayrollYear', $salary->PayrollYear)
                ->update([

                    'ApprovalStatus' => 'Approved',
                    'HrManager' => 'Approved',
                    'User_id' => Auth::id(),

                ]);

            DB::commit();

            Alert::success('Success', 'Salary approved successfully.');

            return back();

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function rejectsalary(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $id = Crypt::decrypt($id);

            $salary = SalaryPaid::findOrFail($id);

            SalaryPaid::where('PaidMonth', $salary->PaidMonth)
                ->where('PayrollYear', $salary->PayrollYear)
                ->update([

                    'ApprovalStatus' => 'Rejected',
                    'HrManager' => 'Rejected',
                    'HrManagerComnt' => $request->HrManagerComnt,
                    'User_id' => Auth::id(),

                ]);

            DB::commit();

            Alert::success('Success', 'Salary rejected successfully.');

            return back();

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function pendingpaysalary()
    {
        try {

            $datas = SalaryPaid::with(['employee.user'])
                ->where('Status', 'Active')
                ->where('PaymentStatus', 'Pending')
                ->where('ApprovalStatus', 'Approved')
                ->orderBy('PayrollYear', 'desc')
                ->orderBy('PaidMonth', 'desc')
                ->get()
                ->groupBy(function ($item) {
                    return $item->PayrollYear.'-'.$item->PaidMonth;
                });

            return view('in.salaries.pendingpaysalary', compact('datas'));

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function paysalary(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'PayMode' => 'required'
            ]);

            $id = Crypt::decrypt($id);

            $salary = SalaryPaid::findOrFail($id);

            SalaryPaid::where('PaidMonth', $salary->PaidMonth)
                ->where('PayrollYear', $salary->PayrollYear)
                ->update([

                    'PaymentStatus' => 'Paid',
                    'ActionPay' => 'Paid',
                    'PaidDate' => now(),
                    'PayMode' => $request->PayMode,
                    'User_id' => Auth::id(),

                ]);

            DB::commit();

            Alert::success('Success', 'Salary paid successfully.');

            return back();

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    public function salaryhistory()
    {
        try {

            $datas = SalaryPaid::with(['employee.user'])
                ->where('Status', 'Active')
                ->where('PaymentStatus', 'Paid')
                ->orderBy('PayrollYear', 'desc')
                ->orderBy('PaidMonth', 'desc')
                ->get()
                ->groupBy(function ($item) {
                    return $item->PayrollYear.'-'.$item->PaidMonth;
                });

            return view('in.salaries.salaryhistory', compact('datas'));

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();

        }
    }

    /**
     * Display Weekly Allowances
     */
    public function weeklyallowanceinformations()
    {
        try {

            $datas = WeeklyAllowance::with(['employee.user'])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            return view('in.weeklyallowance.weeklyallowanceinformations', compact('datas'));

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    /**
     * Weekly Allowance Generation Page
     */
    public function registerweeklyallowance()
    {
        try {

            return view('in.weeklyallowance.registerweeklyallowance');

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }

    /**
     * Generate Weekly Allowances
     */
    public function storeweeklyallowance(Request $request)
    {
        $request->validate([
            'WeekNumber' => 'required|integer|min:1|max:53',
            'AllowanceMonth' => 'required|date_format:Y-m',
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Generation
            |--------------------------------------------------------------------------
            */

            $exists = WeeklyAllowance::where('WeekNumber', $request->WeekNumber)
                ->where('AllowanceMonth', $request->AllowanceMonth)
                ->where('Status', 'Active')
                ->exists();

            if ($exists) {

                Alert::warning('Warning', 'Weekly allowance for the selected week has already been generated.');

                return back()->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | Active Employees Only
            |--------------------------------------------------------------------------
            */

            $employees = Employee::where('Status', 'Active')
                ->where('is_active', true)
                ->where('weekly_allowance_amount', '>', 0)
                ->get();

            if ($employees->isEmpty()) {

                Alert::warning('Warning', 'No employees with weekly allowance found.');

                return back();
            }

            /*
            |--------------------------------------------------------------------------
            | Generate Weekly Allowances
            |--------------------------------------------------------------------------
            */

            foreach ($employees as $employee) {

                WeeklyAllowance::create([

                    'Employee_id' => $employee->id,
                    'User_id' => Auth::id(),

                    'AllowanceAmount' => $employee->weekly_allowance_amount,
                    'AmountPaid' => $employee->weekly_allowance_amount,

                    'WeekNumber' => $request->WeekNumber,
                    'AllowanceMonth' => $request->AllowanceMonth,
                    'AllowanceYear' => date('Y', strtotime($request->AllowanceMonth)),

                    'GeneratedDate' => now(),
                    'PaidDate' => null,
                    'NextAllowanceDate' => null,

                    'PayMode' => null,

                    'Status' => 'Active',
                    'Condition' => 'Pending',
                    'ActionPay' => 'Pending',

                    'ApprovalStatus' => 'Pending',
                    'PaymentStatus' => 'Pending',

                    'HrManager' => 'Pending',
                    'HrDirector' => 'Pending',
                    'FinManger' => 'Pending',


                    'HrManagerComnt' => null,
                    'HrDirectorComnt' => null,
                    'FinMangerComnt' => null,


                    'AllowanceComment' => null,

                    'AuditingStatus' => 'Pending',
                    'ReportStatus' => 'Pending',
                ]);
            }

            DB::commit();

            Alert::success('Success', 'Weekly allowances generated successfully.');

            return redirect()->route('weeklyallowanceinformations');

        } catch (\Exception $e) {

            DB::rollBack();

            Alert::error('Error', $e->getMessage());

            return back()->withInput();
        }
    }


    public function viewweeklyallowance($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = WeeklyAllowance::findOrFail($id);

            $datas = WeeklyAllowance::with(['employee.user'])
                ->where('WeekNumber', $data->WeekNumber)
                ->where('AllowanceMonth', $data->AllowanceMonth)
                ->where('AllowanceYear', $data->AllowanceYear)
                ->where('Status', 'Active')
                ->orderBy('Employee_id')
                ->get();

            return view(
                'in.weeklyallowance.viewweeklyallowance',
                compact('datas', 'data')
            );

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return back();
        }
    }
/**
 * Update Weekly Allowance Batch
 */
public function updateweeklyallowance(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'AllowanceAmount' => 'required|array',
    ]);

    DB::beginTransaction();

    try {

        foreach ($request->ids as $key => $id) {

            $weekly = WeeklyAllowance::findOrFail($id);

            $weekly->update([

                'AllowanceAmount' => $request->AllowanceAmount[$key],
                'AmountPaid'      => $request->AllowanceAmount[$key],

                'AllowanceComment' => $request->AllowanceComment[$key] ?? null,

                'User_id' => Auth::id(),

            ]);

        }

        DB::commit();

        Alert::success('Success', 'Weekly allowances updated successfully.');

        return back();

    } catch (\Exception $e) {

        DB::rollBack();

        Alert::error('Error', $e->getMessage());

        return back()->withInput();
    }
}

/**
 * Delete Weekly Allowance Batch
 */
public function deleteweeklyallowance($id)
{
    DB::beginTransaction();

    try {

        $id = Crypt::decrypt($id);

        $weekly = WeeklyAllowance::findOrFail($id);

        WeeklyAllowance::where('WeekNumber', $weekly->WeekNumber)
            ->where('AllowanceMonth', $weekly->AllowanceMonth)
            ->where('AllowanceYear', $weekly->AllowanceYear)
            ->update([

                'Status' => 'Deleted',

                'User_id' => Auth::id(),

            ]);

        DB::commit();

        Alert::success('Success', 'Weekly allowance batch deleted successfully.');

        return back();

    } catch (\Exception $e) {

        DB::rollBack();

        Alert::error('Error', $e->getMessage());

        return back();
    }
}


public function pendingweeklyallowance()
{
    try {

        $datas = WeeklyAllowance::with(['employee.user'])
            ->where('Status', 'Active')
            ->where('ApprovalStatus', 'Pending')
            ->orderBy('AllowanceYear', 'desc')
            ->orderBy('WeekNumber', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->AllowanceYear.'-'.$item->AllowanceMonth.'-Week '.$item->WeekNumber;
            });

        return view('in.weeklyallowance.pendingweeklyallowance', compact('datas'));

    } catch (\Exception $e) {

        Alert::error('Error', $e->getMessage());

        return back();

    }
}

public function approveweeklyallowance($id)
{
    DB::beginTransaction();

    try {

        $id = Crypt::decrypt($id);

        $weekly = WeeklyAllowance::findOrFail($id);

        WeeklyAllowance::where('WeekNumber', $weekly->WeekNumber)
            ->where('AllowanceMonth', $weekly->AllowanceMonth)
            ->where('AllowanceYear', $weekly->AllowanceYear)
            ->where('Status', 'Active')
            ->update([

                'ApprovalStatus' => 'Approved',
                'HrManager'      => 'Approved',
                'User_id'        => Auth::id(),

            ]);

        DB::commit();

        Alert::success('Success', 'Weekly allowance approved successfully.');

        return back();

    } catch (\Exception $e) {

        DB::rollBack();

        Alert::error('Error', $e->getMessage());

        return back();

    }
}

public function rejectweeklyallowance(Request $request, $id)
{
    DB::beginTransaction();

    try {

        $id = Crypt::decrypt($id);

        $weekly = WeeklyAllowance::findOrFail($id);

        WeeklyAllowance::where('WeekNumber', $weekly->WeekNumber)
            ->where('AllowanceMonth', $weekly->AllowanceMonth)
            ->where('AllowanceYear', $weekly->AllowanceYear)
            ->where('Status', 'Active')
            ->update([

                'ApprovalStatus'  => 'Rejected',
                'HrManager'       => 'Rejected',
                'HrManagerComnt'  => $request->HrManagerComnt,
                'User_id'         => Auth::id(),

            ]);

        DB::commit();

        Alert::success('Success', 'Weekly allowance rejected successfully.');

        return back();

    } catch (\Exception $e) {

        DB::rollBack();

        Alert::error('Error', $e->getMessage());

        return back();

    }
}
public function payweeklyallowances()
{
    try {

        $datas = WeeklyAllowance::with(['employee.user'])
            ->where('Status', 'Active')
            ->where('PaymentStatus', 'Pending')
            ->where('ApprovalStatus', 'Approved')
            ->orderBy('AllowanceYear', 'desc')
            ->orderBy('WeekNumber', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->AllowanceYear.'-'.$item->AllowanceMonth.'-Week '.$item->WeekNumber;
            });

        return view('in.weeklyallowance.payweeklyallowances', compact('datas'));

    } catch (\Exception $e) {

        Alert::error('Error', $e->getMessage());

        return back();

    }
}
public function payweeklyallowance(Request $request, $id)
{
    DB::beginTransaction();

    try {

        $request->validate([
            'PayMode' => 'required'
        ]);

        $id = Crypt::decrypt($id);

        $weekly = WeeklyAllowance::findOrFail($id);

        WeeklyAllowance::where('WeekNumber', $weekly->WeekNumber)
            ->where('AllowanceMonth', $weekly->AllowanceMonth)
            ->where('AllowanceYear', $weekly->AllowanceYear)
            ->where('Status', 'Active')
            ->update([

                'PaymentStatus' => 'Paid',
                'ActionPay'     => 'Paid',
                'PaidDate'      => now(),
                'PayMode'       => $request->PayMode,
                'User_id'       => Auth::id(),

            ]);

        DB::commit();

        Alert::success('Success', 'Weekly allowance paid successfully.');

        return back();

    } catch (\Exception $e) {

        DB::rollBack();

        Alert::error('Error', $e->getMessage());

        return back();

    }
}

public function weeklyallowancehistory()
{
    try {

        $datas = WeeklyAllowance::with(['employee.user'])
            ->where('Status', 'Active')
            ->where('PaymentStatus', 'Paid')
            ->orderBy('AllowanceYear', 'desc')
            ->orderBy('WeekNumber', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->AllowanceYear.'-'.$item->AllowanceMonth.'-Week '.$item->WeekNumber;
            });

        return view('in.weeklyallowance.weeklyallowancehistory', compact('datas'));

    } catch (\Exception $e) {

        Alert::error('Error', $e->getMessage());

        return back();

    }
}
}