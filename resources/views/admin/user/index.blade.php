@extends('layouts.admin')
@section('title', 'User Data')
@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>@yield('title')</h2>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('dashboard.user.create') }}" class="btn btn-primary mb-2">add</a>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->first_name . ' ' . $item->last_name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->role->role_name }}</td>
                                                <td>
                                                    <a href="{{route('dashboard.loginhotel',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                        <i data-feather="key"></i>
                                                        </a>
                                                    <a href="{{ route('dashboard.user.edit', $item->id) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            data-feather="edit"></i></a>

                                                    <form class="d-inline"
                                                        action="{{ route('dashboard.user.delete', $item->id) }}"
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
