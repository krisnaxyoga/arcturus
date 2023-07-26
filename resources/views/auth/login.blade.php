@extends('layouts.auth')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="card" style="width: 25rem;">
                        <div class="card-header text-center">
                          Login
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
                            <form method="post" action="/login">
                                @csrf
                                <div class="mb-3">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email">
                                  @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                  @enderror
                                </div>
                                <div class="mb-3">
                                  <label for="password" class="form-label">Password</label>
                                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                                  @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                  @enderror
                                </div>
                                {{-- <div class="mb-3">
                                  <label for="influencer code" class="form-label">Influencer Code</label>
                                  <input type="text" name="pin" class="form-control @error('pin') is-invalid @enderror" id="pin" required>
                                  @error('pin')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                  @enderror
                                </div> --}}
                                <button type="submit" class="btn btn-primary">LOGIN</button>
                            </form>
                            <p>you dont have account? <a class="text-success" href="{{ request()->routeIs('login.agent') ? route('agentregist') : route('vendorregist') }}">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
