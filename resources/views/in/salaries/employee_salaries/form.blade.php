<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Employee</label>
        <select name="employee_id" class="form-select" id="employeeSelect" required>
            <option value="">-- Select Employee --</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" 
                    {{ old('employee_id', $employeeSalary->employee_id ?? '') == $emp->id ? 'selected' : '' }}>
                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_number ?? 'N/A' }})
                </option>
            @endforeach
        </select>
    </div>

    

    <div class="col-md-6">
        <label class="form-label">Salary Level</label>
        <select name="salary_level_id" class="form-select">
            <option value="">-- None --</option>
            @foreach($salaryLevels as $level)
                <option value="{{ $level->id }}" 
                    {{ old('salary_level_id', $employeeSalary->salary_level_id ?? '') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }} ( {{ $level->basic_amount }} )
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Basic Amount</label>
        <input type="number" name="basic_amount" step="0.01" class="form-control salary-field" value="{{ old('basic_amount', $employeeSalary->basic_amount ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Bonus</label>
        <input type="number" name="bonus" step="0.01" class="form-control salary-field" value="{{ old('bonus', $employeeSalary->bonus ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Insurance</label>
        <input type="number" name="insurance_amount" step="0.01" class="form-control salary-field" value="{{ old('insurance_amount', $employeeSalary->insurance_amount ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">NSSF</label>
        <input type="number" name="nssf" step="0.01" class="form-control salary-field" value="{{ old('nssf', $employeeSalary->nssf ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tax</label>
        <input type="number" name="tax" step="0.01" class="form-control salary-field" value="{{ old('tax', $employeeSalary->tax ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Net Amount (Auto)</label>
        <input type="number" name="net_amount_due" step="0.01" readonly id="netAmount" class="form-control text-success fw-bold" 
            value="{{ old('net_amount_due', $employeeSalary->net_amount_due ?? 0) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Effective From</label>
        <input type="date" name="effective_from" class="form-control" value="{{ old('effective_from', $employeeSalary->effective_from ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Effective To</label>
        <input type="date" name="effective_to" class="form-control" value="{{ old('effective_to', $employeeSalary->effective_to ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Attachment (Optional)</label>
        <input type="file" name="attachment" class="form-control">
        @if(isset($employeeSalary) && $employeeSalary->attachment)
            <a href="{{ asset('storage/'.$employeeSalary->attachment) }}" target="_blank" class="small text-primary">View existing file</a>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ old('status', $employeeSalary->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $employeeSalary->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>

<script>
document.querySelectorAll('.salary-field').forEach(input => {
    input.addEventListener('input', () => {
        const basic = parseFloat(document.querySelector('[name="basic_amount"]').value) || 0;
        const bonus = parseFloat(document.querySelector('[name="bonus"]').value) || 0;
        const insurance = parseFloat(document.querySelector('[name="insurance_amount"]').value) || 0;
        const nssf = parseFloat(document.querySelector('[name="nssf"]').value) || 0;
        const tax = parseFloat(document.querySelector('[name="tax"]').value) || 0;

        const net = (basic + bonus) - (insurance + nssf + tax);
        document.getElementById('netAmount').value = net.toFixed(2);
    });
});
</script>
