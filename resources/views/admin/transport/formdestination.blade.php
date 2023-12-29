@extends('layouts.admin')
@section('title', 'form destination')
@section('content')
    <section>
        <div class="container mt-5">

            <div class="row">
                <div class="col-lg-6">
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
                            
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                            <form
                                action="@if ($model->exists) {{ route('dashboard.transportdestination.update', ['id' => $model->id]) }} @else {{ route('dashboard.transportdestination.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($model->exists ? 'PUT' : 'POST')
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">destination</label>
                                            <input type="text" name="destination" class="form-control" id="destination"
                                                value="{{ $model->destination }}" placeholder="destination...">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-between">
                                    <div class="col-lg-auto">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    <div class="col-lg-auto">
                                        <a href="{{ route('dashboard.transport.destination') }}" class="btn btn-danger">
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
