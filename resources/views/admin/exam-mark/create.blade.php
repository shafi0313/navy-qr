@extends('admin.layouts.app')
@php
    $pageTitle = 'Exam Mark';
    $folder = 'exam-mark';
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
                            @if (in_array(user()->role_id, [1,4]))
                                <div class="col-md-2">
                                    <label for="bangla" class="form-label required">Bangla </label>
                                    <input type="number" step="any" name="bangla" id="bangla" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="english" class="form-label required">English </label>
                                    <input type="number" step="any" name="english" id="english" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="math" class="form-label required">math </label>
                                    <input type="number" step="any" name="math" id="math" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="science" class="form-label required">science </label>
                                    <input type="number" step="any" name="science" id="science" class="form-control"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="general_knowledge" class="form-label required">general knowledge </label>
                                    <input type="number" step="any" name="general_knowledge" id="general_knowledge"
                                        class="form-control" required>
                                </div>
                            @endif

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
