@extends('admin.layouts.app')
@section('title', 'Applicant Present by District & Rank')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Applicant Present by District & Rank', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Send SMS</h4>
                    </div> --}}
                    <table class="table table-bordered bordered table-centered mb-0 w-100">
                        <tr>
                            <td>District</td>
                        </tr>
                        @foreach ($applicants->groupBy('eligible_district') as $applicant)
                            @php
                                $byDistrict = $applicant->first();
                            @endphp
                            <tr>
                                <td>{{ $byDistrict->eligible_district }}</td>
                                <td>
                                    <table class="table table-bordered bordered table-centered mb-0 w-100">
                                        @foreach ($applicant->groupBy('candidate_designation') as $item)
                                            @php
                                                $byDesignation = $item->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $byDesignation->candidate_designation }}</td>
                                                <td>{{ $item->count() }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-light">
                                            <td class="text-end" colspan="1">Total: </td>
                                            <td>{{ $applicant->count() }}</td>
                                        </tr>
                                    </table>
                                </td>
                                
                            </tr>
                            
                        @endforeach
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
