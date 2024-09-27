@extends('admin.layouts.app')
@php
    $pageTitle = 'Viva Mark';
    $folder = 'viva-mark';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', $pageTitle, 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <form onsubmit="ajaxStoreModal(event, this, 'createModal')" action="{{ route('admin.' . $route . '.store') }}"
                    method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row gy-2 mb-3">
                            <div class="col-md-6">
                                <label for="application_id" class="form-label required">Applicant </label>
                                <select name="application_id" id="application_id" class="form-select"></select>
                            </div>
                        </div>
                        <div class="row gy-2">
                            <div class="col-md-2">
                                <label for="viva" class="form-label">Viva/Final Selection </label>
                                <input type="number" step="any" name="viva" id="viva" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div> <!-- end card-body -->
                </form>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        <script>
            $('#application_id').select2({
                width: '100%',
                placeholder: 'Select...',
                allowClear: true,
                ajax: {
                    url: window.location.origin + '/dashboard/select-2-ajax',
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function(params) {
                        return {
                            q: $.trim(params.term),
                            type: 'getApplicant',
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            })
        </script>
    @endpush
@endsection
