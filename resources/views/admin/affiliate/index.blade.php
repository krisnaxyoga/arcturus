@extends('layouts.admin')
@section('title', 'Affiliate Data')
@section('content')
<section>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
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
                        <a href="{{ route('admin.afiliate.create') }}" class="btn btn-primary mb-2">add</a>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>invite</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Affiliator code</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>
                                                <a href="{{route('admin.afiliate.invite',$item->id)}}" class="badge badge-warning">
                                                    invite
                                                </a>
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>
                                                <a data-toggle="tooltip" data-placement="top" title="login" href="{{ route('auth.affiliator',['code'=>$item->auth_code,'id'=>$item->id]) }}"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                        data-feather="key"></i></a>
                                                <a href="{{ route('admin.afiliate.edit', $item->id) }}"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                        data-feather="edit"></i></a>

                                                <form class="d-inline"
                                                    action="{{ route('admin.afiliate.destroy', $item->id) }}"
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
