@extends('admin.layouts.app')
@section('title', 'User')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'Users', 'insId' => 2])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Users</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New
                        </button>
                    </div>
                    <table id="data_table" class="table table-bordered bordered table-centered mb-0 w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Team</th>
                                {{-- <th>IP Address</th> --}}
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $x = 0; @endphp

                            @foreach ($activeUsers as $activeUser)
                                <tr>
                                    <td class="text-center">{{ ++$x }}</td>
                                    <td>{{ $activeUser['name'] ?? 'Unknown' }}</td>
                                    <td>{{ $activeUser['email'] ?? '-' }}</td>
                                    <td class="text-center">{{ $activeUser['team'] ?? '-' }}</td>
                                    {{-- <td class="text-center">{{ $activeUser['ip_address'] ?? '-' }}</td> --}}
                                    <td class="text-center">
                                        @if ($activeUser['type'] === 'web')
                                            <span class="badge bg-primary">Web</span>
                                        @else
                                            <span class="badge bg-success">Tab</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $activeUser['last_activity'] ?? '-' }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.active_users.logout', $activeUser['id']) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                                        </form>
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
    @endpush
@endsection
