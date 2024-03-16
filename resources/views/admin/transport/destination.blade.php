@extends('layouts.admin')
@section('title', 'Transport destination Data')
@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h2>@yield('title')</h2>
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
                    </div>
                    <div class="card-body">
                        <a href="{{ route('dashboard.transportdestination.create') }}" class="btn btn-primary mb-2">add</a>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Destination</th>
                                        {{-- <th>date</th> --}}
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key=>$item)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ $item->destination }}</td>
                                            {{-- <td>{{ $item->created_at }}</td> --}}
                                            <td>
                                                <a href="{{ route('dashboard.transportdestination.edit', $item->id) }}"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                        data-feather="edit"></i></a>

                                                <form class="d-inline"
                                                    action="{{ route('dashboard.transportdestination.destroy', $item->id) }}"
                                                    method="POST"
                                                    onSubmit="return confirm('Apakah anda yakin akan menghapus data ini?');">
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
        </div>
    </div>
</section>
@endsection