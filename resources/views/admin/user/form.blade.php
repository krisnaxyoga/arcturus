@extends('layouts.admin')
@section('title', 'User')
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
                                action="@if ($model->exists) {{ route('dashboard.user.update', ['id' => $model->id]) }} @else {{ route('dashboard.user.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($model->exists ? 'PUT' : 'POST')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="vendor" className="form-label">Vendor <span
                                                    className='text-danger'>*</span></label>
                                            <select name="vendor_id" id="vendor_id" class="form-control" aria-label="Default select example">
                                                @foreach ($vendors as $id => $vendor_name)
                                                    <?php $selected = $id === $model->vendor_id ? 'selected' : ''; ?>
                                                    <option value="{{ $id }}" {{ $selected }}>
                                                        {{ $vendor_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="position" class="form-label">Position</label>
                                            <input type="text" name="position" class="form-control" id="position"
                                                value="{{ $model->name }}" placeholder="Position">
                                        </div>
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">First Name<span
                                                className='text-danger'>*</span></label>
                                            <input type="text" name="firstname" class="form-control" id="firstname"
                                                value="{{ $model->first_name }}" placeholder="First Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                value="{{ $model->mobile_phone }}" placeholder="Phone">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Default password</label>
                                            <input type="text" name="password" id="password" readonly class="form-control" id="input1" value="password123">
                                        </div>
    
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="departement" class="form-label">Departement</label>
                                            <input type="text" name="departement" class="form-control" id="departement"
                                                value="{{ $model->departement }}" placeholder="Departement">
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" className="form-label">Role <span
                                                    className='text-danger'>*</span></label>
                                            <select name="role_id" id="role_id" class="form-control" aria-label="Default select example">
                                                @foreach ($roles as $id => $role_name)
                                                    <?php $selected = $id === $model->role_id ? 'selected' : ''; ?>
                                                    <option value="{{ $id }}" {{ $selected }}>
                                                        {{ $role_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lastname" class="form-label">Last Name</label>
                                            <input type="text" name="lastname" class="form-control" id="lastname"
                                                value="{{ $model->last_name }}" placeholder="Last Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email<span
                                                className='text-danger'>*</span></label>
                                            <input type="text" name="email" class="form-control" id="email"
                                                value="{{ $model->email }}" placeholder="email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

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
                                        <a href="{{ route('dashboard.user') }}" class="btn btn-danger">
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
