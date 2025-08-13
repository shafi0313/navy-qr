@extends('admin.layouts.app')
@section('title', 'Encl-2 Nominal Roll Non-DEUC Sailors')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Encl 2'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <form action="{{ route('admin.reports.daily_state.report') }}">
                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center my-3">
                                <h4>Daily Recruitment State Selection Page</h4>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 mx-1 row">
                                    <div class="col-sm-4">
                                        <label for="start_date" class="col-sm-2 col-form-label required text-nowrap">Date
                                            Form </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" id="start_date"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3 mx-1 row">
                                    <div class="col-sm-4">
                                        <label for="end_date" class="col-sm-2 col-form-label required text-nowrap">Date To
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" id="end_date" required>
                                    </div>
                                </div>
                            </div>
                            @if (user()->role_id == 1)
                                <div class="col-md-3">
                                    <div class="mb-3 mx-1 row">
                                        <div class="col-sm-4">
                                            <label for="date" class="col-sm-2 col-form-label required text-nowrap">Team
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select name="team" class="form-select" required>
                                                <option value="">Select Team</option>
                                                <option value="all" @selected($team=='all')>All</option>
                                                <option value="A" @selected($team=='A')>A</option>
                                                <option value="B" @selected($team=='B')>B</option>
                                                <option value="C" @selected($team=='C')>C</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-2 text-center">
                                <div class="mb-3 row justify-content-center">
                                    <label for="date" class="visually-hidden">Team</label>
                                    <button type="submit" class="btn btn-primary" style="width: 100px">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form> --}}
                    {{-- <hr> --}}
                    {{-- <div class="card-header">
                        <a href="{{ route('admin.reports.daily_state.export_excel', [$startDate, $endDate, $team]) }}"
                            class="btn btn-primary">
                            Export to Excel
                        </a>
                    </div> --}}
                    <div class="card-header">
                        <a href="{{ route('admin.team_f.encl2_non_deuc_sailor.report', 'pdf') }}" class="btn btn-primary">
                            Export to PDF
                        </a>
                    </div>
                    <hr>

                    
                    @include('admin.team-f.encl2-non-deuc-sailor.table', ['applications' => $applications])

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
