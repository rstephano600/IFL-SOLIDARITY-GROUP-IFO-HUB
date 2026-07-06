@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Add Member to {{ $group->group_name }}</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('group_members.store', $group->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="employee_id" class="form-label">Select Employee</label>
            <input type="text" class="form-control mb-2" id="search" placeholder="Search employee...">
            <select class="form-select" name="employee_id" id="employeeList" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->position }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="role_in_group" class="form-label">Role in Group</label>
            <input type="text" name="role_in_group" class="form-control" placeholder="e.g. Treasurer, Chairperson">
        </div>

        <button type="submit" class="btn btn-success">Add Member</button>
        <a href="{{ route('groups.show', $group->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
document.getElementById('search').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const options = document.querySelectorAll('#employeeList option');

    options.forEach(option => {
        const text = option.textContent.toLowerCase();
        option.style.display = text.includes(searchValue) ? 'block' : 'none';
    });
});
</script>
@endsection
