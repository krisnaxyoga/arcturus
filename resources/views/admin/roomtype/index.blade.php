@extends('layouts.admin')
@section('title', 'Roomtype Data')
@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>@yield('title')</h2>
                            @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <a href="{{ route('dashboard.roomtype.create') }}" class="btn btn-primary mb-2">add</a>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                    <thead>
                                        <tr>
                                            <th>Roomtype Code</th>
                                            <th>Roomtype Name</th>
                                            <th>Descriptions</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    <a href="{{ route('dashboard.roomtype.edit', $item->id) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            data-feather="edit"></i></a>

                                                    <form class="d-inline"
                                                        action="{{ route('dashboard.roomtype.delete', $item->id) }}"
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
