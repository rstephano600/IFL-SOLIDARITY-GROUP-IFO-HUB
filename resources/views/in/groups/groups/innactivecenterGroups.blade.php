@extends('layouts.workingside')
@section('title', 'Center Groups')
@section('page-title', 'Groups Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-users"></i>
        </div>
     Innactive   Groups Belonging to Centers
    </h3>

</div>


<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="employeeTable">

                <thead>

                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Group Code</th>
                        <th class="sortable">Group Name</th>
                        <th class="sortable">Center</th>
                        <th class="sortable">Group Type</th>
                        <th class="sortable">Location</th>
                        <th class="sortable">Credit Officer</th>
                        <th class="sortable">Registration Date</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->group_code ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            {{ $item->group_name ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->groupCenter)->center_name ?? '—' }}
                        </td>

                        <td>
                            {{ $item->group_type ?? '—' }}
                        </td>

                        <td>
                            {{ $item->location ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->creditOfficer)->employee->name ?? '' }}
                        </td>

                        <td>
                            {{ $item->registration_date ? \Carbon\Carbon::parse($item->registration_date)->format('d M Y') : '—' }}
                        </td>

                        <td>
                            <a href="{{ route('activatecenterGroups', encrypt($item->id)) }}"
                                onclick="return confirm('Are you sure you want to ACTIVATE this RECORD?');"
                               class="arbif-btn-edit">

                                <i class="fas fa-pencil"></i>
                                Activate

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="10" class="text-center">
                            No Groups Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
