@extends('admin.layouts.app')
@section('title', 'Encl-1 Nominal Roll DEUC Sailors')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Daily State Report', 'insId' => 11])

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
                    <hr>
                    {{-- <div class="card-header">
                        <a href="{{ route('admin.reports.daily_state.export_excel', [$startDate, $endDate, $team]) }}"
                            class="btn btn-primary">
                            Export to Excel
                        </a>
                    </div> --}}

                    <div class="text-center mb-4">
                        <h3>NOMINAL LIST OF DEUC SAILORS - B-2025 BATCH</h3>
                        <h3>CENTER: BNS DHAKA, KHILKHET, DHAKA</h3>
                    </div>

                    <table class="table table-bordered mb-0 w-100">
                        <thead>
                            <tr>
                                <th rowspan="2">Ser</th>
                                <th rowspan="2">District</th>
                                <th rowspan="2">Roll No</th>
                                <th rowspan="2">Local No</th>
                                <th rowspan="2">Name (English & Bangla)</th>
                                <th rowspan="2">Rank (As Per Branch Seniority)</th>
                                <th rowspan="2">GPA (SSC)</th>
                                <th rowspan="2">Hight (Inch)</th>
                                <th colspan="3">SSC Result</th>
                                <th rowspan="2">Mobile No</th>
                                <th colspan="2">HSC Pass</th>
                                <th rowspan="2">Documents to be Submitted to BNS SHER-E-BANGLA</th>
                            </tr>
                            <tr>
                                <th>Eng</th>
                                <th>Math</th>
                                <th>Phy</th>
                                <th>Yes/ No</th>
                                <th>GPA (If Applicable)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                <tr>
                                    <td>{{ @$i += 1 }}</td>
                                    <td>{{ ucfirst($application->eligible_district) }}</td>
                                    <td>{{ $application->serial_no }}</td>
                                    <td></td>
                                    <td>{{ $application->name }}</td>
                                    <td>{{ $application->candidate_designation }}</td>
                                    <td>{{ $application->ssc_gpa }}</td>
                                    <td>{{ $application->height }}</td>
                                    <td>{{ str_replace('English : ', '', $application->ssc_english ?? '') }}</td>
                                    <td>{{ str_replace('Math : ', '', $application->ssc_math ?? '') }}</td>
                                    <td>{{ str_replace('Physics : ', '', $application->ssc_physics ?? '') }}</td>
                                    <td>{{ $application->current_phone }}</td>
                                    <td>{{ $application->hsc_dip_group ? 'Yes' : 'No' }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
