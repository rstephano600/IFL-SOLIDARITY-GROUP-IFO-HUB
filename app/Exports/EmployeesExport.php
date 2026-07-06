<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $employees;
    protected $fields;

    public function __construct($employees, $fields)
    {
        $this->employees = $employees;
        $this->fields = $fields;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        $headings = [];
        $fieldLabels = [
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

        foreach ($this->fields as $field) {
            $headings[] = $fieldLabels[$field] ?? $field;
        }

        return $headings;
    }

    public function map($employee): array
    {
        $row = [];

        foreach ($this->fields as $field) {
            switch ($field) {
                case 'employee_id':
                    $row[] = $employee->employee_id;
                    break;
                case 'full_name':
                    $row[] = $employee->full_name;
                    break;
                case 'first_name':
                    $row[] = $employee->first_name;
                    break;
                case 'middle_name':
                    $row[] = $employee->middle_name;
                    break;
                case 'last_name':
                    $row[] = $employee->last_name;
                    break;
                case 'gender':
                    $row[] = ucfirst($employee->gender);
                    break;
                case 'date_of_birth':
                    $row[] = $employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : '';
                    break;
                case 'age':
                    $row[] = $employee->age;
                    break;
                case 'email':
                    $row[] = $employee->user->email ?? '';
                    break;
                case 'phone':
                    $row[] = $employee->user->phone ?? '';
                    break;
                case 'address':
                    $row[] = $employee->address;
                    break;
                case 'nida':
                    $row[] = $employee->nida;
                    break;
                case 'marital_status':
                    $row[] = $employee->marital_status ? ucfirst($employee->marital_status) : '';
                    break;
                case 'tribe':
                    $row[] = $employee->tribe;
                    break;
                case 'religion':
                    $row[] = $employee->religion;
                    break;
                case 'education_level':
                    $row[] = $employee->education_level;
                    break;
                case 'position':
                    $row[] = $employee->position;
                    break;
                case 'department':
                    $row[] = $employee->department;
                    break;
                case 'date_of_hire':
                    $row[] = $employee->date_of_hire ? $employee->date_of_hire->format('d/m/Y') : '';
                    break;
                case 'years_of_service':
                    $row[] = $employee->years_of_service;
                    break;
                case 'role':
                    $row[] = ucfirst(str_replace('_', ' ', $employee->user->role ?? ''));
                    break;
                case 'is_active':
                    $row[] = $employee->is_active ? 'Hai' : 'Hayupo Kazini';
                    break;
                case 'nok_name':
                    $row[] = $employee->nextOfKin ? $employee->nextOfKin->full_name : '';
                    break;
                case 'nok_phone':
                    $row[] = $employee->nextOfKin ? $employee->nextOfKin->phone : '';
                    break;
                case 'nok_email':
                    $row[] = $employee->nextOfKin ? $employee->nextOfKin->email : '';
                    break;
                case 'nok_address':
                    $row[] = $employee->nextOfKin ? $employee->nextOfKin->address : '';
                    break;
                case 'created_at':
                    $row[] = $employee->created_at->format('d/m/Y H:i');
                    break;
                case 'updated_at':
                    $row[] = $employee->updated_at->format('d/m/Y H:i');
                    break;
                default:
                    $row[] = '';
            }
        }

        return $row;
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
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 20,
            'G' => 15,
            'H' => 30,
            'I' => 20,
            'J' => 25,
        ];
    }
}
