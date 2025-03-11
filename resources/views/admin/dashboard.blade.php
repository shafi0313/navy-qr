@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    {{-- 2 = Officer --}}
    @if (user()->exam_type == 2)
        <div class="row row-cols-1 row-cols-xxl-6 row-cols-lg-3 row-cols-md-2">
            <div class="col">
                <div class="card widget-icon-box">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted text-uppercase fs-13 mt-0" title="Number of Customers">Today's Count
                                </h5>
                                <h3 class="my-3">{{ $todayCount }}</h3>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span
                                    class="avatar-title text-bg-primary rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card widget-icon-box">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-muted text-uppercase fs-13 mt-0" title="Number of Orders">All Count</h5>
                                <h3 class="my-3">{{ $allCount }}</h3>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span
                                    class="avatar-title text-bg-success rounded rounded-3 fs-3 widget-icon-box-avatar shadow">
                                    <i class="fa-solid fa-users"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Data Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        {{-- 2 = Officer --}}
        @if (user()->exam_type == 2)
            <script>
                $(function() {
                    let table = $('#data_table').DataTable({
                        processing: true,
                        serverSide: true,
                        deferRender: true,
                        ordering: true,
                        responsive: true,
                        scrollY: 400,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('admin.application-urls.index') }}",
                            type: "get",
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                className: 'text-center',
                                width: '60px',
                                title: 'SL',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'url',
                                name: 'url',
                                title: 'URL',
                                orderable: false,
                                searchable: false,
                            },
                            {
                                data: 'user.name',
                                name: 'user.name',
                                title: 'Scanned By',
                            },
                            {
                                data: 'scanned_at',
                                name: 'scanned_at',
                                title: 'Scan Date',
                            },
                        ],
                        scroller: {
                            loadingIndicator: true
                        },
                        order: [
                            [3, 'desc']
                        ]
                    });
                });
            </script>
        @endif

    @endpush
@endsection
