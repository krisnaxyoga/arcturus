@extends('layouts.admin')
@section('title', 'Attribute')
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <section>
        <div class="container mt-5">

            <div class="row mb-3">
                <div class="col-lg-12">

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
                    <div class="card">
                        <div class="card-header">
                            <h1>
                                @if ($model->exists)
                                    Edit
                                @else
                                    Add
                                @endif @yield('title')
                            </h1>
                        </div>

                        <div class="card-body">
                            <form
                                action="{{ route('dashboard.paymenttohotel.update', ['id' => $model->id]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <ul>
                                                <li>{{ $model->vendor_name }}</li>
                                                <li>{{ $model->bank_name }}</li>
                                                <li>{{ $model->bank_account }}</li>
                                                <li>{{ $model->account_number }}</li>
                                                <li>{{ $model->bank_address }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">image</label>
                                            <input type="file" id="image-input" class="form-control" name="image">
                                            <img id="image-preview" class="mt-3 img-fluid" style="width: 500px" src="#" alt="Preview">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-between">
                                    <div class="col-lg-auto">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    <div class="col-lg-auto">
                                        <a href="{{ route('dashboard.paymenttohotel.index') }}" class="btn btn-danger">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        @if ($widraw !== null)
                           <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>image</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($widraw as $item)
                                        <tr>
                                            <td>{{ $item->created_at }}</td>
                                            <td> <img src="{{ $item->image }}" style="width: 400px" class="img-fluid" alt="{{ $item->image }}"> </td>
                                            <td>
                                                <a href="{{ route('dashboard.paymenttohotel.destroy', $item->id) }}"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                        data-feather="trash"></i>
                                                    </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                           </div>
                        @endif
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
          // Mengaktifkan event change pada input file
          $('#image-input').change(function() {
            // Mengecek apakah ada file yang dipilih
            if (this.files && this.files[0]) {
              var reader = new FileReader();

              reader.onload = function(e) {
                // Menampilkan pratinjau gambar pada elemen img
                $('#image-preview').attr('src', e.target.result);
              }

              reader.readAsDataURL(this.files[0]);
            }
          });
        });
        </script>

    @endsection
