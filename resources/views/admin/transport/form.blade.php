@extends('layouts.admin')
@section('title', 'Add Transport')
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
                                action="@if ($model->exists) {{ route('dashboard.transport.update', ['id' => $model->id]) }} @else {{ route('dashboard.transport.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($model->exists ? 'PUT' : 'POST')
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Company Name</label>
                                            <input type="text" name="company_name" class="form-control" id="company_name"
                                                value="{{ $model->company_name }}" placeholder="Company Name">
                                        </div>
                                    </div> 
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email" class="form-control" id="email"
                                                value="{{ $model->email }}" placeholder="email">
                                                @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="mobile_phone" class="form-label">Mobile Phone</label>
                                            <input type="text" name="mobile_phone" class="form-control" id="mobile phone"
                                                value="{{ $model->mobile_phone }}" placeholder="mobile phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="markup" class="form-label">Mark up</label>
                                            <input type="text" name="markup" class="form-control" id="markup"
                                                value="{{ $model->markup }}" placeholder="markup">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label for="address">address</label>
                                        <textarea class="form-control" name="address" id="" cols="30" rows="10">
                                            {{ $model->address }}
                                        </textarea>
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
                                        <a href="{{ route('dashboard.transport.index') }}" class="btn btn-danger">
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
@endsection
