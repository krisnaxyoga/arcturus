@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
    <section>
        <div class="container mt-5">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h1>
                                @if ($setting->exists)
                                    Edit
                                @else
                                    Add
                                @endif @yield('title')
                            </h1>
                        </div>
                        <div class="card-body">
                            <form
                                action="@if ($setting->exists) {{ route('dashboard.setting.update', ['id' => $setting->id]) }} @else {{ route('dashboard.setting.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($setting->exists ? 'PUT' : 'POST')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Company Name<span
                                                    className='text-danger'>*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                value="{{ $setting->company_name }}" placeholder="Company Name">
                                            @error('name')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="address1" class="form-label">Address Line1</label>
                                            <input type="text" name="address1" class="form-control" id="address1"
                                                value="{{ $setting->address_line1 }}" placeholder="Address Line1">
                                        </div>
                                        <div class="mb-3">
                                            <label for="telephone" class="form-label">Telphone</label>
                                            <input type="text" name="telephone" class="form-control" id="telephone"
                                                value="{{ $setting->telephone }}" placeholder="Phone">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email<span
                                                    className='text-danger'>*</span></label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                value="{{ $setting->email }}" placeholder="Email">
                                            @error('email')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="permit" class="form-label">Business Permit
                                                Number</label>
                                            <input type="text" name="permit" class="form-control" id="permit"
                                                value="{{ $setting->business_permit_number }}"
                                                placeholder="Vendor Legal Name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="address2" class="form-label">Address Line2</label>
                                            <input type="text" name="address2" class="form-control" id="address2"
                                                value="{{ $setting->address_line2 }}" placeholder="Address Line2">
                                        </div>
                                        <div class="mb-3">
                                            <label for="fax" class="form-label">Fax</label>
                                            <input type="text" name="fax" class="form-control" id="fax"
                                                value="{{ $setting->fax }}" placeholder="Fax">
                                        </div>
                                        <div class="mb-3">
                                            <label for="zipcode" class="form-label">Zip Code</label>
                                            <input type="text" name="zipcode" class="form-control" id="zipcode"
                                                value="{{ $setting->zipcode }}" placeholder="Post Code">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="country" className="form-label">Country <span
                                                    className='text-danger'>*</span></label>
                                            <select name="country" class="form-control" aria-label="Default select example">
                                                @foreach ($countries as $code => $name)
                                                    <?php $selected = $name === $setting->country ? 'selected' : ''; ?>
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
                                                value="{{ $setting->state }}" placeholder="State">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                value="{{ $setting->city }}" placeholder="City">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Descriptions</label>
                                            <textarea name="description" id="editor">{{ $setting->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div><img src="{{ asset('storage/logo/' . $setting->logo_image) }}" att=""
                                                width="100"></div>
                                        <div class="mb-3">
                                            <label for="inputname">Logo Profile<span
                                                    className='text-danger'>*</span></label>
                                            <input type="file" class="form-control" id="photo" name="photo">
                                            @error('photo')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="url_website" class="form-label">URL Site</label>
                                            <input type="text" class="form-control" id="url_website"
                                                name="url_website" value="{{ $setting->url_website }}"
                                                placeholder="Website">
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
                                        <a href="{{ route('dashboard.index') }}" class="btn btn-danger">
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
