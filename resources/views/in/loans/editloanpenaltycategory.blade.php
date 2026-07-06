@extends('layouts.workingside')

@section('title','Edit Loan Penalty Category')

@section('content')

<div class="arbif-card">

    <div class="arbif-card-body">

        <form method="POST"
              action="{{ route('updateloanpenaltycategory', encrypt($data->id)) }}">

            @csrf

            <div class="row g-3">

                <div class="col-md-12">

                    <label class="form-label">
                        Penalty Category Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $data->name }}"
                           class="form-control"
                           required>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        Conditions
                    </label>

                    <textarea name="conditions"
                              rows="5"
                              class="form-control">{{ $data->conditions }}</textarea>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="descriptions"
                              rows="5"
                              class="form-control">{{ $data->descriptions }}</textarea>

                </div>

            </div>

            <div class="mt-4">

                <button type="submit"
                        class="arbif-btn-submit">

                    <i class="fas fa-save"></i>
                    Update Category

                </button>

            </div>

        </form>

    </div>

</div>

@endsection