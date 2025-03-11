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
    @if (in_array(user()->role_id, [1, 2]))
    @endif
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-hover table-striped mb-0 w-100">
                            <thead class="table-primary">
                                <tr>
                                    <th>Team</th>
                                    <th>Today's Count</th>
                                    <th>All Count</th>
                                </tr>
                            </thead>

                            @foreach ($counts as $count)
                                <tr>
                                    <td>{{ $count->team }}</td>
                                    <td class="text-end">{{ nF($count->today_count) }}</td>
                                    <td class="text-end">{{ nF($count->count) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (user()->exam_type == 2)
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
