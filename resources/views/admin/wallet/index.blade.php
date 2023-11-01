@extends('layouts.admin')
@section('title', 'Reports Page')
@section('content')
<section class="mt-3">
    <div class="container">
        <h1>Reports</h1>
          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        TOP UP REPORT
                    </div>
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
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>confirmation</th>
                                        <th>Top up Status</th>
                                        <th>Agent Name</th>
                                        <th>total topup</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key=>$item)
                                        <tr>
                                            <td>
                                                @if($item->status == 400)
                                                    <button type="button" class="btn btn-datatable btn-icon btn-transparent-dark mr-2" data-bs-toggle="modal" data-bs-target="#imageModal{{$key}}">
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                    
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="imageModal{{$key}}" tabindex="-1" aria-labelledby="imageModalLabel{{$key}}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="imageModalLabel{{$key}}">Image Preview</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{$item->url_payment}}" alt="Image" class="img-fluid">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="{{route('dashboard.wallet.confirmation',$item->id)}}" class="btn btn-success text-right">Confirmation Payment</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <ul>
                                                        <li style="list-style: none;"> 
                                                            <span class=" btn-icon btn-transparent-dark mr-2">
                                                                <i data-feather="check"></i>
                                                            </span>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <form class="d-inline" action="{{route('admin.booking.sendconfirmationtoagent',$item->id)}}" method="GET" onSubmit="return confirm('Are you sure you want to send an email to this user?');">
                                                                @csrf
                                                                @method('get')
                    
                                                                <button type="submit" class="badge badge-primary border-0">
                                                                    send email to agent
                                                                </button>
                                                            </form>
                                                        </li>
                                                       
                                                    </ul>
                                                @endif
                                               
                                            </td>
                                            <td> @if($item->status == 400)
                                                <span class="badge badge-warning">processing</span>
                                                @else
                                                <span class="badge badge-success">success</span>
                                                @endif
                                                </td>
                                            <td>{{ $item->users->first_name }} {{ $item->users->last_name }}</td>
                                            <td>{{ $item->total_transaction }}</td>
                                            
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