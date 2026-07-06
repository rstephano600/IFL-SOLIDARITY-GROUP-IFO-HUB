@extends('layouts.workingside')

@section('title', 'Edit Loan Category')

@section('page-title', 'Edit Loan Category')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloancategory', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-12">
                    <h5 class="arbif-section-title">
                        Edit Loan Category
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Loan Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $data->name }}"
                           class="form-control">
                </div>


                <div class="col-md-3">
                    <label class="form-label">
                        Amount Disbursed
                    </label>

                    <input type="number"
                           step="0.01"
                           name="amount_disbursed"
                           id="amount_disbursed"
                           value="{{ $data->amount_disbursed }}"
                           class="form-control calc-input"
                           required>
                </div>


                <div class="col-md-3">
                    <label class="form-label">
                        Interest Rate (%)
                    </label>

                    <input type="number"
                           step="0.01"
                           name="interest_rate"
                           id="interest_rate"
                           value="{{ $data->interest_rate }}"
                           class="form-control calc-input">
                </div>


                <div class="col-md-4">
                    <label class="form-label">
                        Principal Due
                    </label>

                    <input type="number"
                           step="0.01"
                           name="principal_due"
                           id="principal_due"
                           value="{{ $data->principal_due }}"
                           class="form-control calc-input"
                           required>
                </div>


                <div class="col-md-4">
                    <label class="form-label">
                        Insurance Fee
                    </label>

                    <input type="number"
                           step="0.01"
                           name="insurance_fee"
                           id="insurance_fee"
                           value="{{ $data->insurance_fee }}"
                           class="form-control calc-input">
                </div>


                <div class="col-md-4">
                    <label class="form-label">
                        Officer Visit Fee
                    </label>

                    <input type="number"
                           step="0.01"
                           name="officer_visit_fee"
                           id="officer_visit_fee"
                           value="{{ $data->officer_visit_fee }}"
                           class="form-control calc-input">
                </div>


                <div class="col-md-4">
                    <label class="form-label">
                        Repayment Frequency
                    </label>

                    <select name="repayment_frequency"
                            class="form-control">

                        <option value="daily"
                            {{ $data->repayment_frequency == 'daily' ? 'selected' : '' }}>
                            Daily
                        </option>

                        <option value="weekly"
                            {{ $data->repayment_frequency == 'weekly' ? 'selected' : '' }}>
                            Weekly
                        </option>

                        <option value="bi_weekly"
                            {{ $data->repayment_frequency == 'bi_weekly' ? 'selected' : '' }}>
                            Bi Weekly
                        </option>

                        <option value="monthly"
                            {{ $data->repayment_frequency == 'monthly' ? 'selected' : '' }}>
                            Monthly
                        </option>

                        <option value="quarterly"
                            {{ $data->repayment_frequency == 'quarterly' ? 'selected' : '' }}>
                            Quarterly
                        </option>

                    </select>
                </div>


                <div class="col-md-4">
                    <label class="form-label">
                        Currency
                    </label>

                    <input type="text"
                           name="currency"
                           value="{{ $data->currency }}"
                           class="form-control">
                </div>


                <div class="col-md-4">
                    <label class="form-label">
                        New Client
                    </label>

                    <select name="is_new_client"
                            class="form-control">

                        <option value="1"
                            {{ $data->is_new_client ? 'selected' : '' }}>
                            YES
                        </option>

                        <option value="0"
                            {{ !$data->is_new_client ? 'selected' : '' }}>
                            NO
                        </option>

                    </select>
                </div>


                <div class="col-md-12">

                    <div class="alert alert-info">

                        <div class="row">

                            <div class="col-md-3">
                                <strong>Interest Amount</strong>
                                <div id="interest_preview">
                                    {{ number_format($data->interest_amount, 2) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <strong>Total Days</strong>
                                <div id="days_preview">
                                    {{ number_format($data->total_days_due, 0) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <strong>Interest Due</strong>
                                <div id="interest_due_preview">
                                    {{ number_format($data->interest_due, 2) }}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <strong>Total Repayable</strong>
                                <div id="repayable_preview">
                                    {{ number_format($data->repayable_amount, 2) }}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-12">
                    <label class="form-label">
                        Conditions
                    </label>

                    <textarea name="conditions"
                              rows="4"
                              class="form-control">{{ $data->conditions }}</textarea>
                </div>


                <div class="col-md-12">
                    <label class="form-label">
                        Descriptions
                    </label>

                    <textarea name="descriptions"
                              rows="4"
                              class="form-control">{{ $data->descriptions }}</textarea>
                </div>

            </div>


            <div class="mt-4">

                <button type="submit"
                        class="arbif-btn-submit">

                    <i class="fas fa-save"></i>
                    Update Loan Category

                </button>

            </div>

        </form>

    </div>

</div>

@endsection