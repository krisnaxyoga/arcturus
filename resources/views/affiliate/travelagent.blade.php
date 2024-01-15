@extends('affiliate.layout.app')
@section('title','Travel Agent')
@section('content')
<section>
    <div class="container mt-4">
        <h1>
            @yield('title')
        </h1>
        <div class="row mb-3">
             <div class="col-lg-6">
                @if($vendor)
                <div class="card">
                    <div class="card-body">
                    <a href="{{route('auth.affiliator.travelAgentLogin',['code'=>$code,'id'=>$id])}}" class="btn btn-primary">GO!!!</a>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body">
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
                        <form action="{{ route('agentregist.store') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="">First name</label>
                                    <input type="text" name="first_name" value="{{$user->name}}"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    id="name" required>
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Last name</label>
                                    <input type="text" name="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror" id="name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="">Agent Name</label>
                                    <input type="text" name="busisnes_name"
                            class="form-control @error('busisnes_name') is-invalid @enderror" id="name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="">Addess</label>
                                    <input type="text" name="address"
                                    class="form-control @error('address') is-invalid @enderror" id="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="">Email</label>
                                    <input type="email" name="email"
                                    inputmode="email"
                                        class="form-control @error('email') is-invalid @enderror" id="email" required value="{{$user->email}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="">Phone</label>
                                    <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" id="name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="">Country</label>
                                    <select name="country" class="form-control" required>
                                        <option value="">{{ __('-- Select --') }}</option>
                                        @foreach (get_country_lists() as $id => $name)
                                            <option @if (($user->country ?? '') == $id) selected @endif
                                                value="{{ $name }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label for="">State</label>
                                    <input type="text" name="state"
                                    class="form-control @error('state') is-invalid @enderror" id="name">
                                </div>
                                <div class="col-lg-6">
                                    <label for="">City</label>
                                    <input type="text" name="city"
                                    class="form-control @error('city') is-invalid @enderror" id="name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
                                </div>
                            </div>
                            <hr>
                            <input type="hidden" name="affiliate" value="{{$user->code}}">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Sign up</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
           
        </div>
        </div>
    </div>
</section>
@endsection