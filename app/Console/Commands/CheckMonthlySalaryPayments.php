<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeSalary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SalaryDueNotification;

class CheckMonthlySalaryPayments extends Command
{
    protected $signature = 'salary:check-monthly';
    protected $description = 'Check and alert for salaries that are due for monthly payment';

    public function handle()
    {
        $today = Carbon::today();

        $salaries = EmployeeSalary::with('lastPayment', 'employee')
            ->where('status', 'active')
            ->get();

        foreach ($salaries as $salary) {
            $lastPaid = $salary->lastPayment ? Carbon::parse($salary->lastPayment->payment_date) : null;

            if (!$lastPaid || $lastPaid->diffInDays($today) >= 30) {
                // Salary is due — trigger alert
                Log::info("Salary due alert for Employee ID {$salary->employee_id}");

                // Option 1: Send notification (if using Laravel Notifications)
                if ($salary->employee && $salary->employee->user) {
                    Notification::send($salary->employee->user, new SalaryDueNotification($salary));
                }

                // Option 2: Or store an internal alert in DB (optional)
            }
        }

        $this->info('Monthly salary check completed.');
    }
}
