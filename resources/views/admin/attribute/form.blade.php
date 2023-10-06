@extends('layouts.admin')
@section('title', 'Attribute')
@section('content')
    <section>
        <div class="container mt-5">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>
                                @if ($model->exists)
                                    Edit
                                @else
                                    Add
                                @endif @yield('title')
                            </h1>
                        </div>
                        <div class="card-body">
                            <form
                                action="@if ($model->exists) {{ route('dashboard.attribute.update', ['id' => $model->id]) }} @else {{ route('dashboard.attribute.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($model->exists ? 'PUT' : 'POST')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Attribute Name</label>
                                            <input type="text" name="name" class="form-control" id="vendor_name"
                                                value="{{ $model->name }}" placeholder="Attribute Name">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <input type="text" name="description" class="form-control" id="description"
                                                value="{{ $model->description }}" placeholder="Description">
                                        </div>
                                    </div>
                                </div>
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <hr>
                                <div class="row justify-content-between">
                                    <div class="col-lg-auto">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    <div class="col-lg-auto">
                                        <a href="{{ route('dashboard.attribute') }}" class="btn btn-danger">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        CKEDITOR.replace('editor');
    </script>
@endsection
