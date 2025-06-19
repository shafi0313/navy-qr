@extends('admin.layouts.app')
@section('title', 'App Instruction')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => 'App Instructions'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of App Instructions</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New
                        </button>
                    </div> --}}
                    <h4 class="card-title">{{ config('var.menuNames')[$instruction->menu_name] }}</h4>
                    {!! $instruction->instruction !!}
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->


    @push('scripts')
        <script>
            $('.instruction').summernote({
                height: 350,
            });
        </script>
    @endpush
@endsection
