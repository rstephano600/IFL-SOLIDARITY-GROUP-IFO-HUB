@extends('layouts.workingside')
@section('title', 'Manage Salary Batch Breakdowns')
@section('page-title', 'Manage Salary Batch Breakdowns')

@section('content')
<div class="arbif-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3>
            <div class="page-icon"><i class="fas fa-file-invoice-dollar text-primary"></i></div>
            Salary Batch: {{ \Carbon\Carbon::parse($data->PaidMonth)->format('F') }}, {{ $data->PayrollYear }}
        </h3>
        <p class="text-muted small mb-0">Reviewing and adjusting individual items inside this payroll execution workflow cycle.</p>
    </div>
    
    <div class="d-flex gap-2">
        <a href="{{ route('salaryinformations') }}" class="btn btn-sm btn-secondary d-flex align-items-center text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Registry Dashboard
        </a>
        <button type="button" class="btn btn-sm btn-danger d-flex align-items-center" onclick="confirmBatchDeletion('delete-batch-form')">
            <i class="fas fa-trash-alt me-1"></i> Discard Entire Batch
        </button>
    </div>
</div>

<!-- Hidden Safe Form for Contextual Deletion Strategy Tasks -->
<form id="delete-batch-form" action="{{ route('deletesalary', Crypt::encrypt($data->id)) }}" method="POST" class="d-none">
    @csrf
    @method('POST')
</form>

<div class="card shadow-sm border rounded mb-4">
    <form action="{{ route('updatesalary') }}" method="POST">
        @csrf
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-hover align-middle mb-0 text-nowrap" id="payrollSpreadsheet">
                <thead class="bg-dark text-white sticky-top text-center small align-middle" style="z-index: 5;">
                    <tr>
                        <th rowspan="2" class="align-middle bg-dark text-start ps-3" style="position: sticky; left: 0; z-index: 6; min-width: 220px;">Staff Member Detail</th>
                        <th colspan="3" class="bg-primary text-white py-1">Earnings & Additions</th>
                        <th colspan="5" class="bg-danger text-white py-1">Ad-Hoc Reductions / Deductions</th>
                        <th colspan="5" class="bg-secondary text-white py-1">Statutory Deductions & Taxes</th>
                        <th rowspan="2" class="align-middle bg-dark text-end pe-3" style="position: sticky; right: 0; z-index: 6; min-width: 140px;">Final Net Pay</th>
                    </tr>
                    <tr class="small font-monospace" style="font-size: 11px;">
                        <!-- Earnings -->
                        <th class="bg-primary-subtle text-dark">Actual Gross</th>
                        <th class="bg-primary-subtle text-dark">Allowance</th>
                        <th class="bg-primary-subtle text-dark">Overtime</th>
                        
                        <!-- Deductions -->
                        <th class="bg-danger-subtle text-dark">Salary Adv</th>
                        <th class="bg-danger-subtle text-dark">Ovtm Adv</th>
                        <th class="bg-danger-subtle text-dark">HESLB</th>
                        <th class="bg-danger-subtle text-dark">Absenteeism</th>
                        <th class="bg-danger-subtle text-dark">BCABD</th>

                        <!-- Statutory -->
                        <th class="bg-light text-dark">Emp NSSF</th>
                        <th class="bg-light text-dark">NSSF Pay</th>
                        <th class="bg-light text-dark">PAYE Tax</th>
                        <th class="bg-light text-dark">SDL Fee</th>
                        <th class="bg-light text-dark">WCF Ins.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $index => $item)
                    <tr class="payroll-row">
                        <!-- Sticky Identifier Details Profile Component -->
                        <td class="bg-white" style="position: sticky; left: 0; z-index: 2; box-shadow: 2px 0 5px rgba(0,0,0,0.05);">
                            <input type="hidden" name="ids[{{ $index }}]" value="{{ $item->id }}">
                            <div class="font-weight-bold text-dark">{{ $item->employee->user->FirstName ?? '' }} {{ $item->employee->user->LastName ?? '' }}</div>
                            <small class="text-muted font-monospace d-block" style="font-size: 11px;">{{ $item->employee->EmployeeID ?? 'N/A' }}</small>
                        </td>

                        <!-- Earnings Input Sequences -->
                        <td class="bg-light-primary"><input type="number" step="0.01" name="ActualGross[{{ $index }}]" class="form-control form-control-sm calc-input text-end font-weight-bold" value="{{ old('ActualGross.'.$index, $item->ActualGross) }}" required style="width: 110px;"></td>
                        <td><input type="number" step="0.01" name="Allowance[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('Allowance.'.$index, $item->Allowance ?? 0) }}" style="width: 100px;"></td>
                        <td><input type="number" step="0.01" name="Overtime[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('Overtime.'.$index, $item->Overtime ?? 0) }}" style="width: 100px;"></td>

                        <!-- Ad-Hoc Reductions Input Sequences -->
                        <td><input type="number" step="0.01" name="Advance[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('Advance.'.$index, $item->Advance ?? 0) }}" style="width: 95px;"></td>
                        <td><input type="number" step="0.01" name="OvtmAdvn[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('OvtmAdvn.'.$index, $item->OvtmAdvn ?? 0) }}" style="width: 95px;"></td>
                        <td><input type="number" step="0.01" name="Heslb[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('Heslb.'.$index, $item->Heslb ?? 0) }}" style="width: 95px;"></td>
                        <td><input type="number" step="0.01" name="Absent[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('Absent.'.$index, $item->Absent ?? 0) }}" style="width: 95px;"></td>
                        <td><input type="number" step="0.01" name="Bcabd[{{ $index }}]" class="form-control form-control-sm calc-input text-end" value="{{ old('Bcabd.'.$index, $item->Bcabd ?? 0) }}" style="width: 95px;"></td>

                        <!-- Statutory Inputs -->
                        <td><input type="number" step="0.01" name="EmpNssf[{{ $index }}]" class="form-control form-control-sm calc-input text-end text-muted" value="{{ old('EmpNssf.'.$index, $item->EmpNssf ?? 0) }}" style="width: 100px;"></td>
                        <td><input type="number" step="0.01" name="NssfPay[{{ $index }}]" class="form-control form-control-sm calc-input text-end text-muted" value="{{ old('NssfPay.'.$index, $item->NssfPay ?? 0) }}" style="width: 100px;"></td>
                        <td><input type="number" step="0.01" name="Paye[{{ $index }}]" class="form-control form-control-sm calc-input text-end text-muted" value="{{ old('Paye.'.$index, $item->Paye ?? 0) }}" style="width: 100px;"></td>
                        <td><input type="number" step="0.01" name="SdlPay[{{ $index }}]" class="form-control form-control-sm calc-input text-end text-muted" value="{{ old('SdlPay.'.$index, $item->SdlPay ?? 0) }}" style="width: 95px;"></td>
                        <td><input type="number" step="0.01" name="WcfPay[{{ $index }}]" class="form-control form-control-sm calc-input text-end text-muted" value="{{ old('WcfPay.'.$index, $item->WcfPay ?? 0) }}" style="width: 95px;"></td>

                        <!-- Sticky Dynamically Calculated Final Net Target Result Panel -->
                        <td class="bg-white text-end font-weight-bold fs-6 pe-3 net-pay-display text-success" style="position: sticky; right: 0; z-index: 2; box-shadow: -2px 0 5px rgba(0,0,0,0.05); min-width: 140px;">
                            {{ number_format($item->NetPay, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white p-3 d-flex justify-content-between align-items-center border-top">
            <span class="small text-muted"><i class="fas fa-info-circle text-primary me-1"></i> Row calculations update live before form submission.</span>
            <div class="d-flex gap-2">
                <a href="{{ route('salaryinformations') }}" class="btn btn-sm btn-light border px-4">Cancel</a>
                <button type="submit" class="btn btn-sm btn-success px-5 font-weight-bold"><i class="fas fa-save me-1"></i> Commit Structural Adjustments</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const spreadsheet = document.getElementById("payrollSpreadsheet");

    function computeRowNetPay(row) {
        // Utility closure mapping target references
        const getVal = (name) => {
            const element = row.querySelector(`[name^="${name}["]`);
            return element ? parseFloat(element.value) || 0 : 0;
        };

        // Extraction
        const gross = getVal('ActualGross');
        const allowance = getVal('Allowance');
        const overtime = getVal('Overtime');

        const advance = getVal('Advance');
        const ovtmadvn = getVal('OvtmAdvn');
        const heslb = getVal('Heslb');
        const absent = getVal('Absent');
        const bcabd = getVal('Bcabd');

        const empnssf = getVal('EmpNssf');
        const nssf = getVal('NssfPay');
        const paye = getVal('Paye');
        const sdl = getVal('SdlPay');
        const wcf = getVal('WcfPay');

        // Business Math Formula matching your update Controller exactly
        const totalAdditions = gross + allowance + overtime;
        const totalDeductions = advance + ovtmadvn + heslb + absent + bcabd + empnssf + nssf + paye + sdl + wcf;
        const netPay = totalAdditions - totalDeductions;

        // Display update formatting matching currency requirements
        const displayNode = row.querySelector('.net-pay-display');
        if (displayNode) {
            displayNode.textContent = netPay.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            // Highlight negative balance warnings if deductions outpace income
            if (netPay < 0) {
                displayNode.className = "bg-white text-end font-weight-bold fs-6 pe-3 net-pay-display text-danger";
            } else {
                displayNode.className = "bg-white text-end font-weight-bold fs-6 pe-3 net-pay-display text-success";
            }
        }
    }

    // Attach listeners on input events across the workspace grid
    spreadsheet.addEventListener('input', function (e) {
        if (e.target.classList.contains('calc-input')) {
            const targetRow = e.target.closest('tr.payroll-row');
            if (targetRow) {
                computeRowNetPay(targetRow);
            }
        }
    });
});

function confirmBatchDeletion(formId) {
    if (confirm("🚨 WARNING: Are you sure you want to discard this entire salary batch? This will mark all internal records as deleted and drop them from current processing views.")) {
        document.getElementById(formId).submit();
    }
}
</script>

<style>
/* Clean spreadsheet design improvements */
.bg-light-primary { background-color: #f0f7ff !important; }
.bg-primary-subtle { background-color: #cfe2ff !important; }
.bg-danger-subtle { background-color: #f8d7da !important; }
.table-responsive::-webkit-scrollbar { width: 8px; height: 8px; }
.table-responsive::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.table-responsive::-webkit-scrollbar-track { background: #f1f5f9; }
#payrollSpreadsheet input.form-control:focus {
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    border-color: #0d6efd;
}
</style>
@endsection