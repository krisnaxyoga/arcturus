@extends('layouts.auth')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="card" style="width: 35rem;">
                        <div class="card-header text-center">
                            Register Agent
                        </div>

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

                        <div class="card-body">
                            <form method="post" id="registerform" action="{{ route('agentregist.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">First Name</label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                id="name">
                                            @error('first_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="name" class="form-label">Last Name</label>
                                        <input type="text" name="last_name"
                                            class="form-control @error('last_name') is-invalid @enderror" id="name">
                                        @error('last_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Agent Name</label>
                                    <input type="text" name="busisnes_name"
                                        class="form-control @error('busisnes_name') is-invalid @enderror" id="name">
                                    @error('busisnes_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                {{-- <div class="mb-3">
                                    <label for="name" class="form-label">Company Name</label>
                                    <input type="text" name="company_name"
                                        class="form-control @error('company_name') is-invalid @enderror" id="name">
                                        @error('company_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                </div> --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Address</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror" id="name">
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email"
                                    inputmode="email"
                                        class="form-control @error('email') is-invalid @enderror" id="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Phone</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror" id="name">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Country</label>
                                    <select name="country" class="form-control" required>
                                        <option value="">{{ __('-- Select --') }}</option>
                                        @foreach (get_country_lists() as $id => $name)
                                            <option @if (($user->country ?? '') == $id) selected @endif
                                                value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">State</label>
                                    <input type="text" name="state"
                                        class="form-control @error('state') is-invalid @enderror" id="name">
                                    @error('state')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">City</label>
                                    <input type="text" name="city"
                                        class="form-control @error('city') is-invalid @enderror" id="name">
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-between">
                                      <button type="submit" class="btn btn-primary">Sign Up</button>
                                      <a href="/" class="btn btn-danger">back</a>

                                </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script>
        //     document.getElementById('registerform').addEventListener('submit', function(event) {
        //     event.preventDefault();

        //     var password = document.getElementById('password').value;
        //     var confirmPassword = document.getElementById('confirmPassword').value;

        //     if (password !== confirmPassword) {
        //         alert('Password is not same. plese input again.');
        //         return;
        //     }

        //     // Lakukan sesuatu dengan password yang cocok
        //     // alert('Password cocok. Data dapat diproses.');
        // });
    </script>
@endsection
