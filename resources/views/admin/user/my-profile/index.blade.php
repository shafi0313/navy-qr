@extends('admin.layouts.app')
@section('title', 'My Profile')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'My Profile'])
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6">
            <div class="card text-center">
                <div class="card-body ">
                    <img src="{{ profileImg() }}" class="rounded-circle avatar-lg img-thumbnail ms-2" alt="profile-image">
                    <a href="{{ route('admin.my-profiles.edit', user()->id) }}">
                        <i class="fa-solid fa-pen-to-square my-profile-edit"></i>
                    </a>
                    <h4 class="mb-1 mt-2">{{ user()->name }}</h4>
                    <div class="text-start mt-3">
                        <h4 class="fs-13 text-uppercase">About Me :</h4>
                        <p class="text-muted mb-2"><strong>Full Name :</strong>
                            <span class="ms-2">{{ user()->name }}</span>
                        </p>
                        <p class="text-muted mb-2"><strong>Mobile :</strong>
                            <span class="ms-2">{{ user()->mobile }}</span>
                        </p>

                        <p class="text-muted mb-2"><strong>Email :</strong>
                            <span class="ms-2 ">{{ user()->email }}n</span>
                        </p>

                        <p class="text-muted mb-1"><strong>Address :</strong>
                            <span class="ms-2">{{ user()->address }}</span>
                        </p>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>

    @push('scripts')
    @endpush
@endsection
