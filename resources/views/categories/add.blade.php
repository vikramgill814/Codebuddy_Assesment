@extends('layouts.app')

@section('content')
<style>
    .row
    {
        margin-left: 10px !important;
        margin-right: 10px !important;
    }
</style>
    <div class="page-wrapper">
        <!-- Page-header start -->
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                  
                    <div style="clear: both"></div>
                    <div class="page-header-title">
                        <div class="d-inline">
                            <h4>Add Category</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Page-header end -->

        <!-- Page-body start -->
        <div class="page-body">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Zero config.table start -->
                    <div class="card">
                        <div class="card-block">
                            <div class="mT-30">
                                @include('common.error')

                                <form class="container" method="POST" action="{{ route('categories.store') }}" id="needs-validation">
                                    @csrf
                                    <div class="row mt-5">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">Parent Category</label>
                                            <select name="parent_category" id="parent_category" class="form-control">
                                            <option value="" selected>Select a Parent Category</option>
                                            @foreach ($parentCategories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom02">Category Name<span class="red">*</span></label>
                                            <input type="text" class="form-control" name="category_name" value="" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3 text-center">
                                         
                                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                            <a href="{{ url('categories') }}" class="btn btn-sm btn-danger">Back</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page-body end -->
    </div>

@endsection
