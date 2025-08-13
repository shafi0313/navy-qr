@extends('admin.layouts.app')
@section('title', 'My Profile')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['My Profile', '', '']])
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6">
            <div class="card text-center">
                <div class="card-body ">
                    <img src="{{ profileImg() }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                    <a href="{{ route('admin.my-profiles.edit', user()->id) }}">
                        <i class="fa-solid fa-pen-to-square my-profile-edit"></i>
                    </a>

                    <h4 class="mb-1 mt-2">{{ user()->name }}</h4>
                    {{-- <p class="text-muted">Founder</p> --}}

                    {{-- <button type="button" class="btn btn-success btn-rem mb-2">Follow</button>
                    <button type="button" class="btn btn-danger btn-rem mb-2">Message</button> --}}

                    <div class="text-start mt-3">
                        <h4 class="fs-13 text-uppercase">About Me :</h4>
                        {{-- <p class="text-muted mb-3">
                            Hi I'm Tosha Minner,has been the industry's standard dummy text ever since the
                            1500s, when an unknown printer took a galley of type.
                        </p> --}}
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

                    <ul class="social-list list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                    class="ri-facebook-circle-fill"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                    class="ri-google-fill"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                    class="ri-twitter-fill"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                    class="ri-github-fill"></i></a>
                        </li>
                    </ul>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>

    @push('scripts')
    @endpush
@endsection
