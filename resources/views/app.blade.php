@extends('layouts.main')
@section('title', 'Home Page')
@section('content')
@viteReactRefresh
@vite('resources/js/app.jsx')
@inertiaHead
@inertia
@endsection