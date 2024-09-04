@extends('admin.layouts.app')
@section('title', 'App/DB Backup')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'App/DB Backup', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of App/DB Backups</h4>
                        <div class="ms-auto me-2">
                            <form action="{{ route('admin.backup.db') }}" method="POST">
                                @csrf
                                <input onClick="return wait()" type="submit" class="btn btn-primary ml-auto "
                                    style="min-width: 200px" value="Backup Database">
                            </form>
                        </div>

                        <div>
                            <form action="{{ route('admin.backup.files') }}" method="POST">
                                @csrf
                                <input onClick="return wait()" type="submit" class="btn btn-primary ml-auto"
                                    style="min-width: 200px" value="Backup Program File">
                            </form>
                        </div>
                    </div>
                    <table id="data_table" class="table table-bordered bordered table-centered mb-0 w-100">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>File Size</th>
                                <th>Type</th>
                                <th class="no-sort" width="80px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($backups as $backup)
                                <tr>
                                    <td>{{ preg_replace('/[^0-9 -]+/', '', $backup['filename']) }}</td>
                                    <td>{{ readableSize($backup['size']) }}</td>
                                    <td>{{ preg_replace('/[^A-Z]+/', '', $backup['filename']) }}</td>
                                    <td>
                                        <div style="display: inline-block">
                                            <form
                                                action="{{ route('admin.backup.download', ['name' => $backup['filename'], 'ext' => $backup['extension']]) }}"
                                                method="post">
                                                @csrf
                                                <button class="btn btn-info btn-sm"><i class="fa fa-download"></i></button>
                                            </form>
                                        </div>
                                        <div style="display: inline-block">
                                            <form
                                                action="{{ route('admin.backup.delete', ['name' => $backup['filename'], 'ext' => $backup['extension']]) }}"
                                                method="post">
                                                @csrf
                                                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        <script>
            function wait() {
                $("#msg").html('Don\'t do any other action until download complete.')
            }
        </script>
    @endpush
@endsection
