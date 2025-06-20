@extends('admin.layouts.app')
@section('title', 'Daily State Report')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Daily State Report', 'insId' => 11])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('admin.reports.daily_state.export_excel', [$startDate, $endDate, $team]) }}"
                        class="btn btn-primary">
                        Export to Excel
                    </a>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3>DAILY RECRUITMENT STATE</h3>
                        <h3>EXAMINATION CENTER: {{ $team }}</h3>
                        <h3>Date: {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} to
                            {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</h3>
                    </div>

                    <div class="table-responsive">
                        @include('admin.report.daily-state.table')
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
