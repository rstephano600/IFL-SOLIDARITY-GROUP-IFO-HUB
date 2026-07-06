@extends('layouts.workingside')
@section('title', 'Innactive Client Informations')
@section('page-title', 'Innactive Clients Management')

@section('content')

<div class="arbif-page-header">

    <h3>
        <div class="page-icon">
            <i class="fas fa-user-friends"></i>
        </div>
        Innactive Clients Informations
    </h3>

</div>




<div class="arbif-card">

    <div class="arbif-card-body">

        <div class="arbif-table-wrap">

            <table class="arbif-table" id="employeeTable">

                <thead>

                    <tr>

                        <th class="sortable">#</th>
                        <th class="sortable">Client Code</th>
                        <th class="sortable">Full Name</th>
                        <th class="sortable">Phone</th>
                        <th class="sortable">Group</th>
                        <th class="sortable">Center</th>
                        <th class="sortable">Credit Officer</th>
                        <th class="sortable">Business</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $index => $item)

                    <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>

                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->client_code ?? 'N/A' }}
                            </span>

                        </td>

                        <td>

                            {{ optional($item->client)->name ?? '' }}

                        </td>

                        <td>
                            {{ optional($item->client)->phone ?? '—' }}
                        </td>
                        <td>
                            {{ optional($item->groupCenter)->center_name ?? '—' }}
                        </td>

                        <td>
                            {{ optional($item->group)->group_name ?? '—' }}
                        </td>


                        <td>

                            {{ optional($item->loanOfficer)->employee->name ?? '' }}
                        </td>

                        <td>
                            {{ $item->business_name ?? '—' }}
                        </td>

                        <td>
                            <a href="{{ route('showclientinformations', encrypt($item->id)) }}"
                            class="arbif-btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="11" class="text-center">
                            No Client Informations Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
