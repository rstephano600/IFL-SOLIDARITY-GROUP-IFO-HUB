<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="{{ old('name', $salaryLevel->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Currency</label>
        <input type="text" name="currency" value="{{ old('currency', $salaryLevel->currency ?? 'TZS') }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Basic Amount</label>
        <input type="number" step="0.01" name="basic_amount" value="{{ old('basic_amount', $salaryLevel->basic_amount ?? 0) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Insurance Amount</label>
        <input type="number" step="0.01" name="insurance_amount" value="{{ old('insurance_amount', $salaryLevel->insurance_amount ?? 0) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">NSSF</label>
        <input type="number" step="0.01" name="nssf" value="{{ old('nssf', $salaryLevel->nssf ?? 0) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Tax</label>
        <input type="number" step="0.01" name="tax" value="{{ old('tax', $salaryLevel->tax ?? 0) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Net Amount Due</label>
        <input type="number" step="0.01" name="net_amount_due" value="{{ old('net_amount_due', $salaryLevel->net_amount_due ?? 0) }}" class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" {{ old('status', $salaryLevel->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $salaryLevel->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $salaryLevel->description ?? '') }}</textarea>
    </div>
</div>
