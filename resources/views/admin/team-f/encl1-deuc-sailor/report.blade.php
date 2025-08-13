@extends('admin.layouts.app')
@section('title', 'Encl-1 Nominal Roll DEUC Sailors')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Encl 1'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <a href="{{ route('admin.team_f.encl1_deuc_sailor.report', 'pdf') }}" class="btn btn-primary">
                            Export Encl-1
                        </a>
                    </div>
                    <hr>
                    @include('admin.team-f.encl1-deuc-sailor.table', ['applications' => $applications])
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
    @endpush
@endsection
