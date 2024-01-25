@extends('layouts.admin')
@section('title', 'Transport Data')
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
                        <a href="{{ route('dashboard.transport.create') }}" class="btn btn-primary mb-2">add</a>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>email</th>
                                        <th>mobile phone</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                @if($item->invite == 1)
                                                    <span class="badge badge-success">invited</span>
                                                @endif
                                                <a data-toggle="tooltip" data-placement="top" title="Send invite email" href="{{route('dashboard.transportdestination.invite',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2"> <i data-feather="send"></i></a>

                                                @if($item->status != 1)
                                                    <a data-toggle="tooltip" data-placement="top" title="active button" href="{{route('dashboard.transport.isactive',['id' => $item->id,'ac' => 1])}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i data-feather="toggle-left"></i></a>
                                                @else
                                                <a data-toggle="tooltip" data-placement="top" title="non active button" href="{{route('dashboard.transport.isactive',['id' => $item->id,'ac' => 0])}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2 text-danger">
                                                   <span class="text-success">
                                                    <i data-feather="toggle-right"></i>
                                                    </span> 
                                                </a>
                                              
                                                @endif
                                            </td>
                                            <td>{{ $item->company_name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->mobile_phone }}</td>
                                            <td>
                                                <a data-toggle="tooltip" data-placement="top" title="login" href="{{route('login.transport.index',$item->id)}}"  class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i data-feather="key"></i></a>
                                                <a data-toggle="tooltip" data-placement="top" title="edit" href="{{ route('dashboard.transport.edit', $item->id) }}"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                        data-feather="edit"></i></a>

                                                <form class="d-inline"
                                                    action="{{ route('dashboard.transport.destroy', $item->id) }}"
                                                    method="GET"
                                                    onSubmit="return confirm('Apakah anda yakin akan menghapus data ini?');">
                                                    @csrf
                                                    @method('delete')

                                                    <button data-toggle="tooltip" data-placement="top" title="delete" type="submit"
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