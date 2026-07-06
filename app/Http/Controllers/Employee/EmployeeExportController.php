<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use Illuminate\Support\Facades\Response;

class EmployeeExportController extends Controller
{
    /**
     * Show export options page
     */
    public function exportOptions()
    {
        $departments = Employee::whereNotNull('department')
                               ->distinct()
                               ->pluck('department');
        
        $positions = Employee::whereNotNull('position')
                             ->distinct()
                             ->pluck('position');
        
        // Available fields for export
        $availableFields = [
            'basic_info' => [
                'employee_id' => 'Namba ya Mfanyakazi',
                'full_name' => 'Jina Kamili',
                'first_name' => 'Jina la Kwanza',
                'middle_name' => 'Jina la Kati',
                'last_name' => 'Jina la Mwisho',
                'gender' => 'Jinsia',
                'date_of_birth' => 'Tarehe ya Kuzaliwa',
                'age' => 'Umri',
            ],
            'contact_info' => [
                'email' => 'Barua Pepe',
                'phone' => 'Namba ya Simu',
                'address' => 'Anwani',
            ],
            'personal_info' => [
                'nida' => 'NIDA',
                'marital_status' => 'Hali ya Ndoa',
                'tribe' => 'Kabila',
                'religion' => 'Dini',
                'education_level' => 'Kiwango cha Elimu',
            ],
            'employment_info' => [
                'position' => 'Nafasi',
                'department' => 'Idara',
                'date_of_hire' => 'Tarehe ya Kuajiriwa',
                'years_of_service' => 'Miaka ya Huduma',
                'role' => 'Cheo',
                'is_active' => 'Hali ya Kazi',
            ],
            'next_of_kin' => [
                'nok_name' => 'Jina la Jamaa wa Karibu',
                'nok_phone' => 'Simu ya Jamaa',
                'nok_email' => 'Email ya Jamaa',
                'nok_address' => 'Anwani ya Jamaa',
            ],
            'system_info' => [
                'created_at' => 'Tarehe ya Kusajili',
                'updated_at' => 'Tarehe ya Kusasisha',
            ],
        ];

        return view('in.employees.export', compact('departments', 'positions', 'availableFields'));
    }

    /**
     * Export employees to PDF
     */
    public function exportPdf(Request $request)
    {
        $employees = $this->getFilteredEmployees($request);
        $fields = $request->input('fields', []);
        $title = $request->input('report_title', 'Ripoti ya Wafanyakazi');
        
        // Get field labels
        $fieldLabels = $this->getFieldLabels($fields);
        
        $data = [
            'employees' => $employees,
            'fields' => $fields,
            'fieldLabels' => $fieldLabels,
            'title' => $title,
            'filters' => $this->getAppliedFilters($request),
            'generated_at' => now()->format('d/m/Y H:i'),
            'generated_by' => auth()->user()->username,
        ];

        $pdf = Pdf::loadView('in.employees.exports.pdf', $data);
        $pdf->setPaper('a4', $request->input('orientation', 'landscape'));
        
        $filename = 'wafanyakazi_' . now()->format('YmdHis') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export employees to Excel
     */
    public function exportExcel(Request $request)
    {
        $employees = $this->getFilteredEmployees($request);
        $fields = $request->input('fields', []);
        
        $filename = 'wafanyakazi_' . now()->format('YmdHis') . '.xlsx';
        
        return Excel::download(
            new EmployeesExport($employees, $fields), 
            $filename
        );
    }

    /**
     * Export employees to CSV
     */
    public function exportCsv(Request $request)
    {
        $employees = $this->getFilteredEmployees($request);
        $fields = $request->input('fields', []);
        
        $filename = 'wafanyakazi_' . now()->format('YmdHis') . '.csv';
        
        return Excel::download(
            new EmployeesExport($employees, $fields), 
            $filename,
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    /**
     * Get filtered employees based on request
     */
    private function getFilteredEmployees(Request $request)
    {
        $query = Employee::with(['user', 'nextOfKin', 'referees', 'creator']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nida', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active' ? 1 : 0);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_of_hire', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_of_hire', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        return $query->get();
    }

    /**
     * Get field labels for selected fields
     */
    private function getFieldLabels($fields)
    {
        $allFields = [
            'employee_id' => 'Namba ya Mfanyakazi',
            'full_name' => 'Jina Kamili',
            'first_name' => 'Jina la Kwanza',
            'middle_name' => 'Jina la Kati',
            'last_name' => 'Jina la Mwisho',
            'gender' => 'Jinsia',
            'date_of_birth' => 'Tarehe ya Kuzaliwa',
            'age' => 'Umri',
            'email' => 'Barua Pepe',
            'phone' => 'Namba ya Simu',
            'address' => 'Anwani',
            'nida' => 'NIDA',
            'marital_status' => 'Hali ya Ndoa',
            'tribe' => 'Kabila',
            'religion' => 'Dini',
            'education_level' => 'Kiwango cha Elimu',
            'position' => 'Nafasi',
            'department' => 'Idara',
            'date_of_hire' => 'Tarehe ya Kuajiriwa',
            'years_of_service' => 'Miaka ya Huduma',
            'role' => 'Cheo',
            'is_active' => 'Hali ya Kazi',
            'nok_name' => 'Jina la Jamaa wa Karibu',
            'nok_phone' => 'Simu ya Jamaa',
            'nok_email' => 'Email ya Jamaa',
            'nok_address' => 'Anwani ya Jamaa',
            'created_at' => 'Tarehe ya Kusajili',
            'updated_at' => 'Tarehe ya Kusasisha',
        ];

        return array_intersect_key($allFields, array_flip($fields));
    }

    /**
     * Get applied filters for display
     */
    private function getAppliedFilters(Request $request)
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['Utafutaji'] = $request->search;
        }
        if ($request->filled('department')) {
            $filters['Idara'] = $request->department;
        }
        if ($request->filled('position')) {
            $filters['Nafasi'] = $request->position;
        }
        if ($request->filled('status')) {
            $filters['Hali'] = $request->status == 'active' ? 'Hai' : 'Hayupo Kazini';
        }
        if ($request->filled('gender')) {
            $filters['Jinsia'] = ucfirst($request->gender);
        }
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $dateRange = '';
            if ($request->filled('date_from')) {
                $dateRange .= $request->date_from;
            }
            if ($request->filled('date_to')) {
                $dateRange .= ' - ' . $request->date_to;
            }
            $filters['Muda wa Kuajiriwa'] = $dateRange;
        }

        return $filters;
    }
}


