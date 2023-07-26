@extends('layouts.vendor')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Hotel Profile</h1>

    <div class="container">
        <h1>Settings</h1>
        <hr />
        <div class="row">
            <div class="col-lg-12">
                {session.success && (
                <div class="alert alert-success border-0 shadow-sm rounded-3">
                    {session.success}
                </div>
                )}
                <div class="card">
                    <div class="card-body">
                        <form onSubmit={storePost}>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p style="{{ 'font-weight: bold;' }}">Personal Information</p>
                                    <div>
                                        <label for="busisnessname" class="form-label">Business name</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class='fa fa-building'></i></span>
                                            <input type="text" class="form-control" value="{{ $data[0]->vendor_name }}"
                                                placeholder="Business name">
                                            @error('busisnessname')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label for="Email" class="form-label">Email</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class="fa fa-envelope" aria-hidden="true"></i></span>
                                            <input type="email" class="form-control" value="{{ $data[0]->users->email }}"
                                                placeholder="E-mail" aria-label="email" aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class='mr-2'>
                                            <label for="Firstname" class="form-label">First name</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                        class='fa fa-user'></i></span>
                                                <input type="text" class="form-control"
                                                    value="{{ $data[0]->users->first_name }}" placeholder="First Name"
                                                    aria-label="first name" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="Lastname" class="form-label">Last name</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                        class='fa fa-user'></i></span>
                                                <input type="text" class="form-control"
                                                    value="{{ $data[0]->users->last_name }}" placeholder="Last name"
                                                    aria-label="lastname" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="Lastname" class="form-label">Phone Number</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class='fa fa-phone'></i></span>
                                            <input type="text" class="form-control" value="{{ $data[0]->phone }}"
                                                placeholder="Phone Number" aria-label="Username"
                                                aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    {{-- {/* <div>
                                        <label for="Lastname" class="form-label">Birthday</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class="fa fa-birthday-cake" aria-hidden="true"></i></span>
                                            <input type="date" class="form-control" placeholder="Birthday"
                                                aria-label="Username" aria-describedby="basic-addon1" />
                                        </div>
                                    </div> */} --}}
                                    <div class="mb-3">
                                        <img style="{{ 'width: 100px;' }}" src={data[0].logo_img} alt="" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Logo</label>
                                        <input class="form-control" onChange={handleFileChange} type="file"
                                            id="formFile" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <p style="{{ 'font-weight: bold;' }}">Location Information</p>
                                    <div>
                                        <label for="Lastname" class="form-label">Address Line 1</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class="fa fa-location-arrow" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" value="{{$data[0]->address_line1}}" 
                                            placeholder="Address Line 1" aria-label="busisnessname" aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="Lastname" class="form-label">Address Line 2</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class="fa fa-location-arrow" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" value="{{$data[0]->address_line2}}" 
                                            placeholder="Address Line 2" aria-label="busisnessname" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="Lastname" class="form-label">City <span
                                                class='text-danger'>*</span></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class='fa fa-street-view'></i></span>
                                            <input onChange={(e)=> setCity(e.target.value)} defaultValue={data[0].city}
                                            type="text" class="form-control" placeholder="City"
                                            aria-label="busisnessname" aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="Lastname" class="form-label">State <span
                                                class='text-danger'>*</span></label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class='fa fa-map-signs'></i></span>
                                            <input onChange={(e)=> setState(e.target.value)} defaultValue={data[0].state}
                                            type="text" class="form-control" placeholder="State"
                                            aria-label="busisnessname" aria-describedby="basic-addon1" />
                                        </div>
                                    </div>
                                    <div class='mb-3'>
                                        <label for="Lastname" class="form-label">Country <span
                                                class='text-danger'>*</span></label>
                                        <select onChange={(e)=> setCountry(e.target.value)} class="form-control"
                                            aria-label="Default select example">
                                            {Object.keys(country).map(key => (
                                            <option key={key} selected={key===data[0].country} value={key}>{country[key]}
                                            </option>
                                            ))}
                                        </select>
                                    </div>
                                    {/* <div>
                                        <label for="Lastname" class="form-label">Zip Code</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text rounded-0" id="basic-addon1"><i
                                                    class='fa fa-map-pin'></i></span>
                                            <input type="text" class="form-control" placeholder="Zip code"
                                                aria-label="busisnessname" aria-describedby="basic-addon1" />
                                        </div>
                                    </div> */}
                                </div>
                            </div>
                            <hr />
                            <button type='submit' class='btn btn-primary'>
                                <i class='fa fa-save'></i>
                                save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
