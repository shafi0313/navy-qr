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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-centered mb-0 w-100">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
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
                            // data: function(d) {
                            //     return $.extend(d, {
                            //         district: $('.district').val(),
                            //         exam_date: $('.exam_date').val()
                            //     });
                            // },
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

                    // $(".filter").find('select').on('change', function() {
                    //     table.draw();
                    // });

                    // $(".filter").find('a').on('click', function(e) {
                    //     e.preventDefault();
                    //     $(".filter").find('select').val('').trigger('change');
                    //     table.draw();
                    // });
                });
            </script>
        @endif

    @endpush
@endsection
