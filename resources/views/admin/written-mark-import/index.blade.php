@extends('admin.layouts.app')
@php
    $pageTitle = '3.1 - Written Exam Import';
    $folder = 'exam-mark';
    $route = $folder . 's';
@endphp
@section('title', $pageTitle)
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => $pageTitle, 'menuName' => 5])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <form action="{{ route('admin.written_mark_imports.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body row justify-content-center">
                        <div class="form-group col-sm-4">
                            <label for="file">File
                                <span class="t_r"> *</span>
                            </label>
                            <a href="{{ asset('uploads/written-exam-import.xlsx') }}" download>Download Sample File</a>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="col-md-3" style="margin-top: 30px">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
            @if ($writtenMarks->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <form action="{{ route('admin.written-mark-imports.store') }}" method="post">
                                    @csrf @method('POST')
                                    <a href="{{ route('admin.written_mark_imports.all_deletes') }}"
                                        onclick="return confirm('Do you want to delete all data on this page?')"
                                        class="btn btn-danger">Remove All</a>

                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                        class="btn btn-primary">Post</button>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>From Number</th>
                                        <th>Bangla</th>
                                        <th>English</th>
                                        <th>Math</th>
                                        <th>Science</th>
                                        <th>GK</th>
                                        <th class="no-sort" width="60px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($writtenMarks as $writtenMark)
                                        <tr>
                                            <td>{{ @$x += 1 }}</td>
                                            <td>{{ $writtenMark->serial_no }}</td>
                                            <td>{{ $writtenMark->bangla }}</td>
                                            <td>{{ $writtenMark->english }}</td>
                                            <td>{{ $writtenMark->math }}</td>
                                            <td>{{ $writtenMark->science }}</td>
                                            <td>{{ $writtenMark->general_knowledge }}</td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('admin.written-mark-imports.destroy', $writtenMark->id) }}"
                                                    method="post"
                                                    onclick="return confirm('Do you want to delete this data?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete" class="btn btn-link btn-danger btn-sm px-1 py-0">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    @endpush
@endsection
