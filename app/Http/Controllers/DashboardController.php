<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\LogActivity;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanRefund;
use App\Models\LoanTopup;
use App\Models\LoanRepayment;
use App\Models\SalaryPaid;
use App\Models\WeeklyAllowance;
use App\Models\Expense;
use App\Models\ExpensePayment;
use App\Models\Group;
use App\Models\GroupCenter;

class DashboardController extends Controller
{
public function workingside()
{
    try {

        /*
        |--------------------------------------------------------------------------
        | Loans
        |--------------------------------------------------------------------------
        */

        $activeLoans = Loan::where('Status', 'Active')
            ->where('LoanStatus', 'Active')
            ->count();

        $pendingLoanApproval = Loan::where('Status', 'Active')
            ->where('ApprovalStatus', 'Pending')
            ->count();

        $pendingRepayment = Loan::where('Status', 'Active')
            ->where('RepaymentStatus', 'Pending')
            ->count();

        $disbursedLoans = Loan::where('Status', 'Active')
            ->whereNotNull('disbursement_date')
            ->count();

        $completedLoans = Loan::where('Status', 'Active')
            ->where('CloseStatus', 'Closed')
            ->count();

        $refundedLoans = Loan::where('Status', 'Active')
            ->where('RefundStatus', 'Refunded')
            ->count();

        $loanPortfolio = Loan::where('Status', 'Active')
            ->sum('outstanding_balance');

        /*
        |--------------------------------------------------------------------------
        | Clients
        |--------------------------------------------------------------------------
        */

        $totalClients = Client::where('Status', 'Active')->count();

        /*
        |--------------------------------------------------------------------------
        | Groups & Centers
        |--------------------------------------------------------------------------
        */

        $totalGroups = Group::where('Status', 'Active')->count();

        $totalCenters = GroupCenter::where('Status', 'Active')->count();

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        $activeEmployees = Employee::where('Status', 'Active')
            ->where('is_active', 1)
            ->count();

        $inactiveEmployees = Employee::where(function ($q) {

                $q->where('Status', '!=', 'Active')
                  ->orWhere('is_active', 0);

            })->count();

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $activeUsers = User::where('Status', 'Active')->count();

        $inactiveUsers = User::where('Status', '!=', 'Active')->count();

        /*
        |--------------------------------------------------------------------------
        | Salary
        |--------------------------------------------------------------------------
        */

        $currentMonth = now()->format('Y-m');

        $monthlyPayroll = SalaryPaid::where('Status', 'Active')
            ->where('PaidMonth', $currentMonth)
            ->sum('NetPay');

        $pendingPayroll = SalaryPaid::where('Status', 'Active')
            ->where('ApprovalStatus', 'Pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Weekly Allowances
        |--------------------------------------------------------------------------
        */

        $currentWeek = now()->weekOfYear;

        $weeklyAllowance = WeeklyAllowance::where('Status', 'Active')
            ->where('WeekNumber', $currentWeek)
            ->sum('AllowanceAmount');

        $pendingWeeklyAllowance = WeeklyAllowance::where('Status', 'Active')
            ->where('ApprovalStatus', 'Pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $monthlyExpenses = Expense::where('Status', 'Active')
            ->whereMonth('expense_date', Carbon::now()->month)
            ->whereYear('expense_date', Carbon::now()->year)
            ->sum('total_amount');

        $pendingExpenses = Expense::where('Status', 'Active')
            ->where('AppStatus', 'Pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Activities
        |--------------------------------------------------------------------------
        */

        $todayCollections = LoanRepayment::whereDate('payment_date', today())
            ->sum('amount_paid');

        $todayDisbursement = Loan::whereDate('disbursement_date', today())
            ->sum('amount_disbursed');

        /*
        |--------------------------------------------------------------------------
        | Financial Summary
        |--------------------------------------------------------------------------
        | Temporary until Accounting Module is integrated
        |--------------------------------------------------------------------------
        */

        $grossProfit = 0;
        $netProfit = 0;

        return view('in.working.workingside', compact(

            'activeLoans',
            'pendingLoanApproval',
            'pendingRepayment',
            'disbursedLoans',
            'completedLoans',
            'refundedLoans',
            'loanPortfolio',

            'totalClients',
            'totalGroups',
            'totalCenters',

            'activeEmployees',
            'inactiveEmployees',

            'activeUsers',
            'inactiveUsers',

            'monthlyPayroll',
            'pendingPayroll',

            'weeklyAllowance',
            'pendingWeeklyAllowance',

            'monthlyExpenses',
            'pendingExpenses',

            'todayCollections',
            'todayDisbursement',

            'grossProfit',
            'netProfit'

        ));

    } catch (\Exception $e) {

        return back()->with('error', $e->getMessage());

    }
}


    public function configurationside()
    {
        return view('in.configuration.configurationside');
    }

    public function reportingside()
    {
        return view('in.reporting.reportingside');
    }
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        switch ($user->role) {
            case User::ROLE_ADMIN:
                return redirect()->route('admin.dashboard');

            case User::ROLE_DIRECTOR:
                return redirect()->route('director.dashboard');

            case User::ROLE_CEO:
                return redirect()->route('ceo.dashboard');

            case User::ROLE_SHAREHOLDERS:
                return redirect()->route('shareholders.dashboard');

            case User::ROLE_MANAGER:
                return redirect()->route('manager.dashboard');

            case User::ROLE_MARKETING_OFFICER:
                return redirect()->route('marketingofficer.dashboard');

            case User::ROLE_HR:
                return redirect()->route('hr.dashboard');

            case User::ROLE_ACCOUNTANT:
                return redirect()->route('accountant.dashboard');

            case User::ROLE_SECRETARY:
                return redirect()->route('secretary.dashboard');

            case User::ROLE_LOAN_OFFICER:
                return redirect()->route('loanofficer.dashboard');

            case User::ROLE_CLIENT:
                return redirect()->route('client.dashboard');

            case User::ROLE_USER:
            default:
                return redirect()->route('user.dashboard');
        }

        
    }
}
