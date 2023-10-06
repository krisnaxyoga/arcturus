@extends('layouts.admin')
@section('title', 'Hotel')
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
                            <a href="{{ route('dashboard.hotel.create') }}" class="btn btn-primary mb-2">add</a>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>Hotel Name</th>
                                            {{-- <th>Company Name</th> --}}
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->vendor_name }}</td>
                                                {{-- <td>{{ $item->vendor_legal_name }}</td> --}}
                                                <td>{{ $item->address_line1 }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>
                                                     <a href="{{route('dashboard.loginhotel',$item->user_id)}}" class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                   <i data-feather="key"></i>
                                                    </a>
                                                    <a href="{{ route('dashboard.hotel.edit', $item->id) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            data-feather="edit"></i></a>

                                                    <form class="d-inline"
                                                        action="{{ route('dashboard.hotel.delete', $item->id) }}"
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
