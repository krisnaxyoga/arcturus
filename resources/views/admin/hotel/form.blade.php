@extends('layouts.admin')
@section('title', 'Hotel')
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
                                action="@if ($model->exists) {{ route('dashboard.hotel.update', $model->id) }} @else {{ route('dashboard.hotel.store') }} @endif"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method($model->exists ? 'PUT' : 'POST')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="vendor_name" class="form-label">Hotel Name</label>
                                            <input type="text" name="vendor_name" class="form-control" id="vendor_name"
                                                value="{{ $model->vendor_name }}" placeholder="Hotel Name">
                                        </div>
                                        <div class="d-flex">
                                            <div class="mb-3">
                                                <label for="firstname" class="form-label">First Name</label>
                                                <input type="text" name="firstname" class="form-control" id="firstname"
                                                    value="@if ($model->exists) {{ $model->users->first_name }} @else {{ $user->first_name }} @endif"
                                                    placeholder="First Name">
                                            </div>
                                            <div class="mb-3 mx-3">
                                                <label for="lastname" class="form-label">Last Name</label>
                                                <input type="text" name="lastname" class="form-control" id="lastname"
                                                    value="@if ($model->exists) {{ $model->users->last_name }} @else {{ $user->last_name }} @endif"
                                                    placeholder="Last Name">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="address1" class="form-label">Address Line1</label>
                                            <input type="text" name="address1" class="form-control" id="address1"
                                                value="{{ $model->address_line1 }}" placeholder="Address Line1">
                                        </div>
                                        <div class="mb-3">
                                            <label for="address2" class="form-label">Address Line2</label>
                                            <input type="text" name="address2" class="form-control" id="address2"
                                                value="{{ $model->address_line2 }}" placeholder="Address Line2">
                                        </div>
                                       
                                    </div>
                                    <div class="col-lg-6">
                                        {{-- <div class="mb-3">
                                            <label for="vendor_legal_name" class="form-label">Name Hotel</label>
                                            <input type="text" name="vendor_legal_name" class="form-control"
                                                id="vendor_legal_name" value="{{ $model->vendor_legal_name }}"
                                                placeholder="Buissines Name">
                                        </div> --}}
                                        
                                        <div class="mb-3">
                                            <label for="input1" class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control" id="input1"
                                            value="{{ $model->phone }}"   placeholder="Phone">
                                        </div>
                                        <div class="mb-3">
                                            <label for="input1" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="input1"
                                            value="{{ $model->email }}"   placeholder="email@mail.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
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
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" class="form-control" id="state" name="state"
                                                value="{{ $model->state }}" placeholder="State">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                value="{{ $model->city }}" placeholder="City">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="markup" class="form-label">System Markup</label>
                                            <input type="text" class="form-control" id="markup" name="markup"
                                                value="{{ $model->system_markup }}" placeholder="System Markup">
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
                                        <a href="{{ route('dashboard.hotel') }}" class="btn btn-danger">
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
    <script>
        var inputContainer = document.getElementById('inputContainer');
        var inputElement = document.getElementById('gambar');
        var fileList = inputElement.files;
        inputElement.addEventListener('change', function() {
            var files = Array.from(inputElement.files);


            files.forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var imageContainer = document.createElement('div');
                    imageContainer.className = 'image-container';

                    var image = document.createElement('img');
                    image.src = event.target.result;
                    image.style.width = '100px';
                    image.style.height = 'auto';
                    image.className = 'mx-2';

                    console.log(inputElement.files, "value image");

                    var removeButton = document.createElement('button');
                    removeButton.innerHTML = '<i class="fa fa-trash"></i>';
                    removeButton.className = 'btn btn-danger';

                    removeButton.addEventListener('click', function() {
                        imageContainer.remove();
                        hapusData(0);
                    });

                    imageContainer.appendChild(image);
                    imageContainer.appendChild(removeButton);

                    inputContainer.appendChild(imageContainer);
                };

                reader.readAsDataURL(file);
            });
        });

        function hapusData(index) {
            var files = Array.from(inputElement.files);
            files.splice(index, 1);

            // Membuat kembali FileList dari array yang dimodifikasi
            var newFileList = new DataTransfer();
            files.forEach(function(file) {
                newFileList.items.add(file);
            });

            // Mengganti value files pada elemen input
            inputElement.files = newFileList.files;
        }
    </script>
@endsection
