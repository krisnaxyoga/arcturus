@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<style>
    .object-fit-cover {
    -o-object-fit: cover!important;
    object-fit: cover!important;
}

.card-img, .card-img-top {
    border-top-left-radius: 0.35rem;
    border-top-right-radius: 0.35rem;
    height: 200px !important;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <section>
        <div class="container mt-5">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">general</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">slider</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="passwordchange-tab" data-toggle="tab" href="#passwordchange" role="tab" aria-controls="passwordchange" aria-selected="false">change password</a>
                          </li>
                      </ul>
                      <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card mb-4">
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
                                            <div><img src="{{ asset($setting->logo_image) }}" att=""
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
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{route('dashboard.setting.storepopup')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')

                                            @if ($datapop->exists)

                                                <input type="hidden" name="id" value="{{$datapop->id}}">
                                                <div class="form-group">
                                                    <label for="">popup</label>
                                                    <input type="file" name="image" id="popup" class="form-control">
                                                    <img id="image-previewpopup" class="mt-3" style="width: 200px" src="{{$datapop->image}}" alt="Preview">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">start date</label>
                                                    <input type="date" name="start_date" class="form-control" value="{{$datapop->start_date}}">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="">end date</label>
                                                    <input type="date" name="end_date" class="form-control" value="{{$datapop->end_date}}">
                                                </div>

                                            @else

                                                <div class="form-group">
                                                    <label for="">popup</label>
                                                    <input type="file" name="image" id="popup" class="form-control">
                                                    <img id="image-previewpopup" class="mt-3" style="width: 200px" src="#" alt="Preview">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">start date</label>
                                                    <input type="date" name="start_date" id="start_date" class="form-control">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="">end date</label>
                                                    <input type="date" name="end_date" id="end_date" class="form-control">
                                                </div>

                                            @endif

                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit">upload</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="row">
                                    @foreach ($popup as $ie)

                                    <div class="col-lg-6">
                                        <div class="card mb-3">
                                            <img src="{{$ie->url}}" alt="{{$ie->url}}" class="card-img-top object-fit-cover">
                                            <div class="card-body">
                                                <ul>
                                                    <li>Url :<a href="{{$ie->url}}">{{$ie->url}}</a></li>
                                                    {{-- <li>Status : <div class="toggle-switch">
                                                        <input class="toggle-input" id="toggle" type="checkbox">
                                                        <label class="toggle-label" for="toggle"></label>
                                                      </div>
                                                    </li> --}}
                                                    <li>Start Date  : {{$ie->start_date}}</li>
                                                    <li>End Date    : {{$ie->end_date}}</li>
                                                    <li>
                                                        <form class="d-inline"
                                                            action="{{ route('dashboard.setting.destroypopup', $ie->id) }}"
                                                            method="POST"
                                                            onSubmit="return confirm('are you sure delete this banner?');">
                                                            @csrf
                                                            @method('delete')

                                                            <button type="submit"
                                                                class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                                <i data-feather="trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                <li>
                                                    <a href="{{ route('dashboard.setting.editpopup', $ie->id) }}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i data-feather="edit"></i></a>
                                                </li>
                                                </ul>
                                            </div>
                                          </div>

                                   </div>
                                   @endforeach
                                </div>
                            </div>
                        </div>
                        
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h2>Slider Banner</h2>
                                </div>
                                <div class="card-body">
                                    <form action="{{route('dashboard.setting.storeslider')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                     <div class="mb-3">
                                                        <label for="">title</label>
                                                        <input type="text" class="form-control" name="title" placeholder="title">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="">image</label>
                                                        <input type="file" id="image-input" class="form-control" name="image">
                                                        <img id="image-preview" class="mt-3" style="width: 200px" src="#" alt="Preview">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="">description</label>
                                                        <textarea name="description" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i>save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                            <thead>
                                                <tr>
                                                    <th>image</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($slide as $item)
                                                    <tr>
                                                        <td><img src="{{$item->image}}" alt="" style="width:150px"/></td>
                                                        <td>{{$item->title}}</td>
                                                        <td>{{$item->description}}</td>
                                                        <td>
                                                            <a href="{{ route('dashboard.user.edit', $item->id) }}"
                                                                class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                                    data-feather="edit"></i></a>
        
                                                            <form class="d-inline"
                                                                action="{{ route('dashboard.setting.destroyslider', $item->id) }}"
                                                                method="POST"
                                                                onSubmit="return confirm('are you sure delete this banner?');">
                                                                @csrf
                                                                @method('delete')
        
                                                                <button type="submit"
                                                                    class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                                    <i data-feather="trash-2"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" role="tabpanel" aria-labelledby="passwordchange-tab" id="passwordchange">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{route('dashboard.setting.storeslider')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                             <div class="mb-3">
                                                                <label for="">new password</label>
                                                                <input type="text" class="form-control" name="title" placeholder="title">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i>save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        $(document).ready(function() {
          // Mengaktifkan event change pada input file
          $('#image-input').change(function() {
            // Mengecek apakah ada file yang dipilih
            if (this.files && this.files[0]) {
              var reader = new FileReader();
        
              reader.onload = function(e) {
                // Menampilkan pratinjau gambar pada elemen img
                $('#image-preview').attr('src', e.target.result);
              }
        
              reader.readAsDataURL(this.files[0]);
            }
          });

          $('#popup').change(function() {
            // Mengecek apakah ada file yang dipilih
            if (this.files && this.files[0]) {
              var reader = new FileReader();
        
              reader.onload = function(e) {
                // Menampilkan pratinjau gambar pada elemen img
                $('#image-previewpopup').attr('src', e.target.result);
              }
        
              reader.readAsDataURL(this.files[0]);
            }
          });

        });
        </script>
        <!-- JavaScript -->
<script>
 document.addEventListener("DOMContentLoaded", function () {
        // Ambil elemen input untuk start_date dan end_date
        var startDateInput = document.getElementById("start_date");
        var endDateInput = document.getElementById("end_date");

        // Tambahkan event listener ke start_date
        startDateInput.addEventListener("change", function () {
            // Ambil nilai dari start_date
            var startDateValue = startDateInput.value;

            // Set nilai end_date sama dengan start_date
            endDateInput.value = startDateValue;
        });
    });
</script>
        <style>
            /* Genel stil */
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
  margin: 10px;
}

/* Giriş stil */
.toggle-switch .toggle-input {
  display: none;
}

/* Anahtarın stilinin etrafındaki etiketin stil */
.toggle-switch .toggle-label {
  position: absolute;
  top: 0;
  left: 0;
  width: 40px;
  height: 24px;
  background-color: #2196F3;
  border-radius: 34px;
  cursor: pointer;
  transition: background-color 0.3s;
}

/* Anahtarın yuvarlak kısmının stil */
.toggle-switch .toggle-label::before {
  content: "";
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  top: 2px;
  left: 2px;
  background-color: #fff;
  box-shadow: 0px 2px 5px 0px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s;
}

/* Anahtarın etkin hale gelmesindeki stil değişiklikleri */
.toggle-switch .toggle-input:checked + .toggle-label {
  background-color: #4CAF50;
}

.toggle-switch .toggle-input:checked + .toggle-label::before {
  transform: translateX(16px);
}

/* Light tema */
.toggle-switch.light .toggle-label {
  background-color: #BEBEBE;
}

.toggle-switch.light .toggle-input:checked + .toggle-label {
  background-color: #9B9B9B;
}

.toggle-switch.light .toggle-input:checked + .toggle-label::before {
  transform: translateX(6px);
}

/* Dark tema */
.toggle-switch.dark .toggle-label {
  background-color: #4B4B4B;
}

.toggle-switch.dark .toggle-input:checked + .toggle-label {
  background-color: #717171;
}

.toggle-switch.dark .toggle-input:checked + .toggle-label::before {
  transform: translateX(16px);
}

        </style>
@endsection
