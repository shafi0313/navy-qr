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
                        <div class="col-sm-3">
                            <label for="" class="mb-1">Today's Written Exam Applicants (Pending)</label>
                            @foreach ($todayWrittenApplicantCount as $team => $count)
                                <h4 class="">{{ user()->role_id == 1 ? $team . ': ' : '' }}{{ $count }}</h4>
                            @endforeach
                        </div>
                        <div class="col-sm-4">
                            <label for="file">File
                                <span class="t_r"> *</span>
                            </label>
                            <a href="{{ asset('uploads/written-exam-import.xlsx') }}" download>Download Sample File</a>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="col-sm-3" style="margin-top: 20px">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </div>
                </form>
            </div>
            @if ($writtenMarks->count() > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end align-items-center">
                            <form action="{{ route('admin.written_mark_imports.check') }}" method="post">
                                @csrf @method('POST')
                                <input type="hidden" name="written_marks"
                                    value="{{ $writtenMarks->pluck('id')->implode(',') }}">

                                <button type="submit" onclick="return confirm('Are you sure?')"
                                    class="btn btn-warning">Check Data</button>
                            </form>

                            <form action="{{ route('admin.written_mark_imports.all_deletes') }}" method="post">
                                @csrf @method('POST')
                                <input type="hidden" name="written_marks"
                                    value="{{ $writtenMarks->pluck('id')->implode(',') }}">

                                <button type="submit"
                                    onclick="return confirm('Do you want to remove all data on this page')"
                                    class="btn btn-danger mx-2">Clear This List</button>
                            </form>

                            <form action="{{ route('admin.written-mark-imports.store') }}" method="post">
                                @csrf @method('POST')
                                <input type="hidden" name="written_marks"
                                    value="{{ $writtenMarks->pluck('id')->implode(',') }}">

                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-primary">Add
                                    to Database</button>
                            </form>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Roll Number</th>
                                        <th>Bangla</th>
                                        <th>English</th>
                                        <th>Math</th>
                                        <th>Science</th>
                                        <th>GK</th>
                                        <th>Remark</th>
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
                                            <td>{{ $writtenMark->remark ?? '' }}</td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('admin.written-mark-imports.destroy', $writtenMark->id) }}"
                                                    method="post"
                                                    onclick="return confirm('Do you want to delete this data?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" title="Delete"
                                                        class="btn btn-link btn-danger btn-sm px-1 py-0">
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
