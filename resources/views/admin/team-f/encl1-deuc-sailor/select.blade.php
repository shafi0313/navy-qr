@extends('admin.layouts.app')
@section('title', 'Daily State Report')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Daily State Report', 'menuName' => 11])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.daily_state.report') }}">
                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center my-3">
                                <h3>Daily Recruitment State Selection Page</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3 mx-1 row">
                                    <div class="col-sm-4">
                                        <label for="start_date" class="col-sm-2 col-form-label required text-nowrap">Date Form </label>
                                    </div>                                    
                                    <div class="col-sm-8">
                                        <input type="date" name="start_date" class="form-control" id="start_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3 mx-1 row">
                                    <div class="col-sm-4">
                                        <label for="end_date" class="col-sm-2 col-form-label required text-nowrap">Date To </label>
                                    </div>  
                                    <div class="col-sm-8">
                                        <input type="date" name="end_date" class="form-control" id="end_date" required>
                                    </div>
                                </div>
                            </div>
                            @if (user()->role_id == 1)
                                <div class="col-md-4">
                                    <div class="mb-3 mx-1 row">
                                        <div class="col-sm-4">
                                            <label for="date" class="col-sm-2 col-form-label required text-nowrap">Team </label>
                                        </div>  
                                        <div class="col-sm-8">
                                            <select name="team" class="form-select" required>
                                                <option value="">Select Team</option>
                                                <option value="all">All</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            

                        </div>
                        <div class="col-12 text-center">
                            <div class="mb-3 row justify-content-center">
                                <label for="date" class="visually-hidden">Team</label>
                                <button type="submit" class="btn btn-primary" style="width: 100px">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
