@extends('layouts.workingside')
@section('title', 'Edit Salary Allowance')
@section('page-title', 'Edit Salary Allowance')

@section('content')
<div class="arbif-page-header">
    <h3><div class="page-icon"><i class="fas fa-edit"></i></div>Edit Salary Allowance</h3>
    <a href="{{ route('slrallwncs') }}" class="arbif-btn-cancel"><i class="fas fa-arrow-left"></i> Back to List</a>
</div>

<div class="arbif-card">
    <div class="arbif-card-body">
        <form method="POST" action="{{ route('updateslrallwncs', [encrypt($allowance->id)]) }}">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select style="width: 100%" name="Paid_id" data-searchable required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('Paid_id', $allowance->Paid_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="AllwAmount">Allowance Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="AllwAmount" name="AllwAmount" class="form-control" value="{{ old('AllwAmount', $allowance->AllwAmount) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="PayStaus">Payment Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="PayStaus" name="PayStaus" required>
                        <option value="Unpaid" {{ old('PayStaus', $allowance->PayStaus) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Processing" {{ old('PayStaus', $allowance->PayStaus) == 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Paid" {{ old('PayStaus', $allowance->PayStaus) == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('slrallwncs') }}" class="arbif-btn-cancel"><i class="bi bi-x"></i> Cancel</a>
                <button onclick="confirmSubmit()" type="submit" class="arbif-btn-submit"><i class="bi bi-check2"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection