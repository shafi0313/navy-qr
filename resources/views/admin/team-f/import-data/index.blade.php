@extends('admin.layouts.app')
@php
    $pageTitle = 'Team F Import Data';
    $folder = 'important-application-import';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 14])

    <div class="row gy-2 justify-content-between">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.team_f.data_imports.import') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div style="display: flex;">
                            <div style="flex-grow: 1">
                                <label for="file" class="form-label required">Bulk Import </label>
                                <a href="{{ asset('uploads/Final-selection.xlsx') }}" download>(Download
                                    Format)</a>
                                <input type="file" name="file" class="form-control" required>
                            </div>

                            <div style="margin-top: 28px; margin-left: 10px;">
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    {{-- <form onsubmit="ajaxStoreModal(event, this, 'createModal')" --}}
                    {{-- action="{{ route('admin.team_f.data_imports.single_store') }}" method="POST"> --}}
                    {{-- @csrf --}}
                    <div style="display: flex;">
                        <div style="flex-grow: 1">
                            <label for="application_id" class="form-label required">Single Entry </label>
                            <select name="application_id" id="application_id" class="form-select"></select>
                        </div>
                        <div style="margin-top: 20px; margin-left: 10px;">
                            <button type="submit" class="btn btn-primary" onclick="ajaxEdit(this)">Add</button>
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            {{-- <div class="card">
                <form action="{{ route('admin.team_f.data_imports.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row justify-content-center">
                        <div class="form-group col-sm-4">
                            <label for="file">File
                                <span class="t_r"> * </span>
                            </label>
                            <a href="{{ asset('uploads/Final-selection.xlsx') }}" download>Download Excel
                                Format</a>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="col-md-3" style="margin-top: 30px">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div> --}}
            @if ($teamFDatum->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Roll No</th>
                                        <th>Branch</th>
                                        <th>Name</th>
                                        <th>District</th>
                                        <th class="no-sort" width="60px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamFDatum as $teamFData)
                                        <tr>
                                            <td>{{ ($teamFDatum->currentPage() - 1) * $teamFDatum->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $teamFData->serial_no }}</td>
                                            <td>{{ config('var.brCodes')[$teamFData->br_code] ?? '' }}</td>
                                            <td>{{ $teamFData->application->name }}</td>
                                            <td>{{ ucfirst($teamFData->application->district) }}</td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('admin.team-f-data-imports.destroy', $teamFData->id) }}"
                                                    method="post"
                                                    onclick="return confirm('Do you want to delete this data?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete"
                                                        class="btn btn-link btn-danger btn-sm">
                                                        <i class="fa fa-times text-light"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $teamFDatum->links() }}
                    </div>
                    <form action="{{ route('admin.team-f-data-imports.store') }}" method="post">
                        @csrf @method('POST')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('admin.important_application_imports.all_deletes') }}"
                                        onclick="return confirm('Do you want to delete all data on this page?')"
                                        class="btn btn-danger">Delete All</a>

                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                        class="btn btn-primary">Post</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">No data found!</div>
                    </div>
                </div>
            @endif
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        @include('admin.team-f.ajax')
    @endpush
@endsection
