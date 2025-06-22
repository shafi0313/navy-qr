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

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered table-hover table-striped mb-0 w-100">
                            <thead class="table-primary">
                                <tr>
                                    <th>Team</th>
                                    <th>Today's applicant</th>
                                    <th>Today's Presence</th>
                                    <th>Total Presence</th>
                                </tr>
                            </thead>
                            @if (user()->exam_type == 2)
                                @foreach ($counts as $count)
                                    <tr>
                                        <td>{{ $count->team }}</td>
                                        <td class="text-end">{{ nF($count->count) }}</td>
                                        <td class="text-end">{{ nF($count->today_count) }}</td>
                                        <td class="text-end">{{ nF($count->count) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($data as $team)
                                    <tr>
                                        <td class="text-center">{{ $team['team'] }}</td>
                                        <td class="text-end">{{ nF($team['stats']->todayApplicants ?? 0) }}</td>
                                        <td class="text-end">{{ nF($team['stats']->today ?? 0) }}</td>
                                        <td class="text-end">{{ nF($team['stats']->present ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Officer Data --}}
    @if (user()->exam_type == 2)
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
    {{-- /Officer Data --}}

    {{-- App Instructions --}}
    <div>
        <h4 class="p-1 mt-3">App Instructions</h4>
    </div>
    @foreach ($appInstructions as $instruction)
        <div class="row">
            <div class="col-12">
                <div class="card p-2">
                    <h4 class="card-header">{{ config('var.menuNames')[$instruction->menu_name] }}</h4>
                    <div class="card-body">
                        {!! $instruction->instruction !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- /App Instructions --}}

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
