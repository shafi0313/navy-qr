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
                            $designations = $applicants->groupBy('candidate_designation')->keys();
                        @endphp
                        <table class="table table-bordered mb-0 w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">Subject/Rank</th>
                                    @foreach ($designations as $designation)
                                        <th class="text-end">{{ preg_replace('/^Sailor(?:\s-\s|-)?/', '', $designation) }}
                                        </th>
                                    @endforeach
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">Total Applicant</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $applicants->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $applicants->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Total Presence</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $attendants->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $attendants->sum('total') }}</td>
                                </tr>
                                
                                {{-- Preliminary Medical Start --}}
                                <tr>
                                    <td rowspan="3">Preliminary Medical</td>
                                    <td>Pending</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $pMPending->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $pMPending->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Unfit</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $pMUnfit->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $pMUnfit->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Fit</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $pMFit->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $pMFit->sum('total') }}</td>
                                </tr>
                                {{-- Preliminary Medical End --}}

                                {{-- Written Exam Start--}}
                                <tr>
                                    <td rowspan="3">Written Exam</td>
                                    <td>Pending</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $wPending->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $wPending->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Not Qualified</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $wFail->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $wFail->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Qualified</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $wPass->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $wPass->sum('total') }}</td>
                                </tr>
                                {{-- Written Exam End--}}

                                {{-- Final Medical Start--}}
                                <tr>
                                    <td rowspan="3">Final Medical</td>
                                    <td>Pending</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $fMPending->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $fMPending->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Unfit</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $fMUnfit->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $fMUnfit->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Fit</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $fMFit->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $fMFit->sum('total') }}</td>
                                </tr>
                                {{-- Final Medical End--}}

                                {{-- Viva Start--}}
                                <tr>
                                    <td rowspan="3">Viva</td>
                                    <td>Pending</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $vPending->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $vPending->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Not Qualified</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $vFail->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $vFail->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Qualified</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $vPass->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $vPass->sum('total') }}</td>
                                </tr>
                                {{-- Viva End--}}

                                {{-- HBsAg/Dope Test Start--}}
                                <tr>
                                    <td rowspan="3">HBsAg/Dope Test</td>
                                    <td>Pending</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $dPending->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $dPending->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Unfit</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $dFail->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $dFail->sum('total') }}</td>
                                </tr>
                                <tr>
                                    <td>Fit</td>
                                    @foreach ($designations as $designation)
                                        <td class="text-end">
                                            {{ $dPass->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">{{ $dPass->sum('total') }}</td>
                                </tr>
                                {{-- HBsAg/Dope Test End--}}
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
