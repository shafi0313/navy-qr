@extends('admin.layouts.app')
@section('title', 'Encl-2 Nominal Roll Non-DEUC Sailors')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Encl 2'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <a href="{{ route('admin.team_f.encl2_non_deuc_sailor.report', 'pdf') }}" class="btn btn-primary">
                            Export Encl-2
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
