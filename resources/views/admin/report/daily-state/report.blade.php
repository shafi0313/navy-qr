@extends('admin.layouts.app')
@section('title', 'Daily State Report')
@section('content')
    @include('admin.layouts.includes.breadcrumb', [
        'title' => ['', 'Daily State Report', 'Report'],
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('dashboard/export-applicants') }}" class="btn btn-success">
                        Export to Excel
                    </a>
                    <div class="table-responsive">
                        @php
                            // Get unique districts and designations
                            $districts = $applicants->groupBy('eligible_district');
                            $designations = $applicants->groupBy('candidate_designation')->keys();
                        @endphp
                        <table class="table table-bordered mb-0 w-100">
                            <thead>
                                <tr>
                                    <th>Subject/Rank</th>
                                    @foreach ($designations as $designation)
                                        <th class="text-end">{{ preg_replace('/^Sailor(?:\s-\s|-)?/', '', $designation) }}
                                        </th>
                                    @endforeach
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Applicant</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $applicants->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $applicants->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Presence</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $attendants->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $attendants->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Preliminary Medical</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $pMUnfit->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $pMUnfit->sum('total') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
