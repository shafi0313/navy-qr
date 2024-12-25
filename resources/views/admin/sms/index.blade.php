@extends('admin.layouts.app')
@section('title', 'SMS Report')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'SMS Report', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card widget-flat bg-success text-white">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="ri-user-voice-line widget-icon bg-white text-success"></i>
                                    </div>
                                    <h5 class="fw-normal mt-0">Send SMS of This Month</h5>
                                    <h3 class="my-3">{{ $thisMonth }}</h3>
                                </div>
                            </div>
                        </div> <!-- end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <div class="card widget-flat bg-primary text-white">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="ri-shopping-basket-line widget-icon bg-light-subtle rounded-circle text-primary"></i>
                                    </div>
                                    <h5 class="fw-normal mt-0">Send SMS All Time</h5>
                                    <h3 class="my-3 text-white">{{ $allTime }}</h3>
                                </div>
                            </div>
                        </div> <!-- end col-->
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Send SMS</h4>
                    </div>
                    <table id="data_table" class="table table-bordered bordered table-centered mb-0 w-100">
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        <script>
            $(function() {
                $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    // responsive: true,
                    scrollX: true,
                    scrollY: 400,
                    ajax: "{{ route('admin.sms.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: 'SL',
                            className: "text-center",
                            width: "17px",
                            searchable: false,
                            orderable: false,
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            title: 'Date',
                        },
                        {
                            data: 'message',
                            name: 'message',
                            title: 'message',
                        },
                        {
                            data: 'phone',
                            name: 'phone',
                            title: 'phone',
                        },
                        {
                            data: 'type',
                            name: 'type',
                            title: 'type',
                        },
                        {
                            data: 'user.name',
                            name: 'user.name',
                            title: 'Send By'
                        },
                    ],
                    // fixedColumns: false,
                    scroller: {
                        loadingIndicator: true
                    }
                });
            });
        </script>
    @endpush
@endsection
