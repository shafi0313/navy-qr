@extends('admin.layouts.app')
@section('title', 'Applicant Count by District & Rank')
@section('content')
    @include('admin.layouts.includes.breadcrumb', [
        'title' => ['', 'Applicant Count by District & Rank', 'Index'],
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        @php
                            // Get unique districts and designations
                            $districts = $applicants->groupBy('eligible_district');
                            $designations = $applicants->groupBy('candidate_designation')->keys();
                        @endphp

                        <table class="table table-bordered table-centered mb-0 w-100">
                            <thead>
                                <tr>
                                    <th>District</th>
                                    <th class="text-end">Total</th>
                                    @foreach ($designations as $designation)
                                        <th class="text-end">{{ $designation }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($districts as $district => $applicantGroup)
                                    <tr>
                                        <td>{{ $district }}</td>
                                        @php
                                            $districtTotal = $applicantGroup->sum('total');
                                        @endphp
                                        <td class="text-end"><strong>{{ $districtTotal }}</strong></td>
                                        @foreach ($designations as $designation)
                                            <td class="text-end">
                                                {{ $applicantGroup->firstWhere('candidate_designation', $designation)->total ?? 0 }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end"><strong>{{ $applicants->sum('total') }}</strong></th>
                                    @foreach ($designations as $designation)
                                        <th class="text-end">
                                            {{ $applicants->where('candidate_designation', $designation)->sum('total') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
