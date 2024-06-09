@extends('layouts.admin')

@section('title', 'Agent Data')

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
                        <a href="{{route('dashboard.agent.create')}}" class="btn btn-primary mb-2">add</a>
                        <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#ImportModal">
                            Import Excel
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>Agent Name</th>
                                        <th>Company Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->vendor_name }}</td>
                                        <td>{{ $item->vendor_legal_name }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <a href="{{ route('agent.backdoor',$item->user_id) }}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                                <i data-feather="key"></i>
                                            </a>

                                            @if($item->is_active == 1)
                                                <a href="{{route('dashboard.agent.unactive',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark"><i data-feather="x"></i></a>
                                            @else
                                                <a href="{{route('dashboard.agent.active',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark"><i data-feather="check"></i></a>
                                            @endif

                                            <a href="{{route('dashboard.agent.edit',$item->id)}}" class="btn btn-datatable btn-icon btn-transparent-dark"><i data-feather="edit"></i></a>

                                            <form class="d-inline" action="{{route('dashboard.agent.delete',$item->id)}}" method="POST" onSubmit="return confirm('Apakah anda yakin akan menghapus data ini?');">
                                                @csrf
                                                @method('delete')

                                                <button type="submit" class="btn btn-datatable btn-icon btn-transparent-dark">
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
    <!-- Modal -->
    <div class="modal fade" id="ImportModal" tabindex="-1" aria-labelledby="ImportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="ImportModalLabel">Import Agent</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('dashboard.agent.import')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">
                <a href="{{route('dashboard.agent.template')}}" class="btn btn-success">Download Template</a>
                    <div class="mb-3">
                        <label for="">import excel</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">import</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</section>
@endsection
