@extends('layouts.auth')

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="card" style="width: 25rem;">
                        <div class="card-header text-center">
                          Forget Password
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
                            <form method="post" action="{{route('forgotpassword')}}">
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
                                <button type="submit" class="btn btn-primary">SEND</button>
                            </form>
                            {{-- <p>forget your password? <a class="text-success" href="{{route('forgetpassword.user')}}">click here</a></p> --}}
                            {{-- <p>you dont have account? <a class="text-success" href="{{ request()->routeIs('login.agent') ? route('agentregist') : route('vendorregist') }}">Sign Up</a></p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
