@extends('layouts.workingside')
@section('title', 'Group Centers')
@section('page-title', 'Group Collection Centers')

@section('content')

<div class="arbif-page-header">
    <h3>
        <div class="page-icon">
            <i class="fas fa-building"></i>
        </div>
        Innactive Group Collection Centers
    </h3>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">

        <div class="arbif-table-wrap">
            <table class="arbif-table" id="employeeTable">
                <thead>
                    <tr>
                        <th class="sortable">#</th>
                        <th class="sortable">Center Code</th>
                        <th class="sortable">Center Name</th>
                        <th class="sortable">Location</th>
                        <th class="sortable">Area</th>
                        <th class="sortable">Collection Officer</th>
                        <th class="sortable">Established Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <span class="arbif-badge arbif-badge-navy">
                                {{ $item->center_code ?? 'N/A' }}
                            </span>
                        </td>

                        <td>{{ $item->center_name ?? '—' }}</td>

                        <td>{{ $item->location ?? '—' }}</td>

                        <td>{{ $item->area ?? '—' }}</td>

                        <td>
                            {{ optional($item->collectionOfficer)->employee->name ?? '—' }}
                        </td>

                        <td>
                            {{ $item->established_date ? \Carbon\Carbon::parse($item->established_date)->format('d M Y') : '—' }}
                        </td>

                        <td>
                            <a href="{{ route('activategroupCenter', encrypt($item->id)) }}" class="arbif-btn-edit" onclick="return confirm('Are you sure you want to ACTIVATE this RECORD?');">
                                <i class="fas fa-success"></i> Activate
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No Group Centers Found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
