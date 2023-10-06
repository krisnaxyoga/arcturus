@extends('layouts.admin')
@section('title', 'Agent')
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
                                action="@if ($model->exists) {{ route('dashboard.agent.update', ['id' => $model->id]) }} @else {{ route('dashboard.agent.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($model->exists ? 'PUT' : 'POST')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="vendor_name" class="form-label">Agent Name</label>
                                            <input type="text" name="vendor_name" class="form-control" id="vendor_name"
                                                value="{{ $model->vendor_name }}" placeholder="Vendor Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="input1" class="form-label">First Name</label>
                                            <input type="text" name="firstname" class="form-control" id="firstname"
                                                value="@if ($model->exists) {{ $model->users->first_name }} @else {{ $user->first_name }} @endif" placeholder="First Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="address1" class="form-label">Address Line1</label>
                                            <input type="text" name="address1" class="form-control" id="address1"
                                                value="{{ $model->address_line1 }}" placeholder="Address Line1">
                                        </div>
                                        <div class="mb-3">
                                            <label for="input1" class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                value="{{ $model->phone }}" placeholder="Phone">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="vendor_legal_name" class="form-label">Company Name</label>
                                            <input type="text" name="vendor_legal_name" class="form-control"
                                                id="vendor_legal_name" name="vendor_legal_name" value="{{ $model->vendor_legal_name }}"
                                                placeholder="Vendor Legal Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="input2" class="form-label">Last Name</label>
                                            <input type="text" name="lastname" class="form-control" id="lastname"
                                                value="@if ($model->exists) {{ $model->users->last_name }} @else {{ $user->last_name }} @endif" 
                                                placeholder="Last Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="address2" class="form-label">Address Line2</label>
                                            <input type="text" name="address2" class="form-control" id="address2"
                                                value="{{ $model->address_line2 }}" placeholder="Address Line2">
                                        </div>
                                        <div class="mb-3">
                                            <label for="input1" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                value="{{ $model->email }}" placeholder="email@mail.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="country" className="form-label">Country <span
                                                    className='text-danger'>*</span></label>
                                            <select name="country" class="form-control" aria-label="Default select example">
                                                @foreach ($countries as $code => $name)
                                                    <?php $selected = $name === $model->country ? 'selected' : ''; ?>
                                                    <option value="{{ $name }}" {{ $selected }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" class="form-control" id="state" name="state"
                                            value="{{ $model->state }}"  placeholder="State">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                            value="{{ $model->city }}"   placeholder="City">
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
                                        <a href="{{ route('dashboard.agent') }}" class="btn btn-danger">
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
