@extends('admin.layouts.app')
@php
    $pageTitle = 're';
    $folder = 're';
    $route = 'reset-data';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 3])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center gy-2 mb-3">
                        <div class="col-md-6">
                            <label for="application_id" class="form-label required">Applicant </label>
                            <select name="application_id" id="application_id" class="form-select"></select>
                        </div>
                        <div class="col-md-3" style="margin-top: 35px">
                            <button type="submit" class="btn btn-primary" id="filter_btn">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Search</button>
                        </div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <form action="{{ route('admin.reset-data.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                            <thead>
                                <tr>
                                    <th>Exam Date</th>
                                    <th>Roll No</th>
                                    <th>Branch</th>
                                    <th>Name</th>
                                    <th>District</th>
                                    <th>Exam Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="scanned_at" class="form-check-input" id="scanned_at">
                                    <label class="form-check-label" for="scanned_at">Scan</label>
                                </div>
                            </div>
                            {{-- <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="is_gate_entry" class="form-check-input" id="is_gate_entry">
                                    <label class="form-check-label" for="is_gate_entry">Gate Entry</label>
                                </div>
                            </div> --}}
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="is_medical_pass" class="form-check-input"
                                        id="is_medical_pass">
                                    <label class="form-check-label" for="is_medical_pass">Primary Medical</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="is_final_pass" class="form-check-input" id="is_final_pass">
                                    <label class="form-check-label" for="is_final_pass">Final Medical</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="is_important" class="form-check-input" id="is_important">
                                    <label class="form-check-label" for="is_important">Important</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="written_exam" class="form-check-input" id="written_exam">
                                    <label class="form-check-label" for="written_exam">Written Exam</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="viva" class="form-check-input" id="viva">
                                    <label class="form-check-label" for="viva">Viva</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="dup_test" class="form-check-input" id="dup_test">
                                    <label class="form-check-label" for="dup_test">Dop Test</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </form>

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

            function getDataById(id) {
                $.ajax({
                    url: "{{ route('admin.reset_data.show', ['id' => ':id']) }}".replace(':id',
                        id),
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            $('#data_table tbody').empty();
                            $('#data_table tbody').html(response.modal);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            }

            $('#application_id').on('select2:select', function(e) {
                $('#data_table tbody').empty();
            });

            $(document).ready(function() {
                $('#filter_btn').click(function() {
                    const id = $('#application_id').val();
                    getDataById(id);
                });
            });


            // $(document).on('click', '.ok_btn', function(e) {
            //     e.preventDefault();
            //     let application_id = $('#application_id').find(":selected").val();
            //     let yes_no = $('input[name="yes_no"]:checked').val();
            //     showLoadingAnimation();
            //     $.ajax({
            //         url: "{{ route('admin.application-search.store') }}",
            //         type: "POST",
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             application_id: application_id,
            //             yes_no: yes_no,
            //         },
            //         success: function(res) {
            //             hideLoadingAnimation();
            //             swal({
            //                 icon: "success",
            //                 title: "Success",
            //                 text: res.message,
            //             }).then((confirm) => {
            //                 if (confirm) {
            //                     $('.modal').modal('hide');
            //                     $('#data_table tbody').empty();
            //                 }
            //             });
            //         },
            //         error: function(err) {
            //             hideLoadingAnimation();
            //             swal({
            //                 icon: "error",
            //                 title: "Oops...",
            //                 text: err.responseJSON ? err.responseJSON.message :
            //                     'An error occurred.',
            //             });
            //         }
            //     });
            // });
        </script>
    @endpush
@endsection
