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
                        BOOKING REPORT
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
                                        <th>Booking Status</th>
                                        <th>Hotel Name</th>
                                        <th>Agent Name</th>
                                        <th>Guest Name</th>
                                        <th>Total Guest</th>
                                        <th>Price</th>
                                        <th>Guest Email</th>
                                        
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
                                                                    <a href="{{route('admin.booking.confirmation',$item->id)}}" class="btn btn-success text-right">Confirmation Payment</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <ul>
                                                        <li style="list-style: none;">
                                                            <span class="btn-icon btn-transparent-dark mr-2">
                                                                <i data-feather="check"></i>
                                                            </span>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <button type="button" class="badge badge-warning border-0" data-toggle="modal" data-target="#sendEmailModal">
                                                                Send email all
                                                            </button>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <button type="button" class="badge badge-primary border-0" data-toggle="modal" data-target="#sendEmailToAgentModal">
                                                                Send email to agent
                                                            </button>
                                                        </li>
                                                        <li style="list-style: none;">
                                                            <button type="button" class="badge badge-secondary border-0" data-toggle="modal" data-target="#sendEmailToHotelModal">
                                                                Send email to hotel
                                                            </button>
                                                        </li>
                                                    </ul>
                                                    
                                                    <!-- Modal for sending email to all -->
                                                    <div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sendEmailModalLabel">Send Email to All</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to send an email to all?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form class="d-inline" action="{{ route('admin.booking.confirmation', $item->id) }}" method="GET">
                                                                        @csrf
                                                                        @method('get')
                                                                        <button type="submit" class="btn btn-warning">Send Email</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Modal for sending email to agent -->
                                                    <div class="modal fade" id="sendEmailToAgentModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailToAgentModalLabel" aria-hidden="true">
                                                        <!-- Include the same modal structure as above, just change the IDs, titles, and action URL accordingly -->
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sendEmailModalLabel">Send Email to Agent</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to send an email to Agent?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form class="d-inline" action="{{ route('admin.booking.sendconfirmationtoagent',$item->id) }}" method="GET">
                                                                        @csrf
                                                                        @method('get')
                                                                        <button type="submit" class="btn btn-warning">Send Email</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Modal for sending email to hotel -->
                                                    <div class="modal fade" id="sendEmailToHotelModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailToHotelModalLabel" aria-hidden="true">
                                                        <!-- Include the same modal structure as above, just change the IDs, titles, and action URL accordingly -->
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="sendEmailModalLabel">Send Email to Hotel</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to send an email to Hotel?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <form class="d-inline" action="{{ route('admin.booking.sendconfirmationtohotel',$item->id) }}" method="GET">
                                                                        @csrf
                                                                        @method('get')
                                                                        <button type="submit" class="btn btn-warning">Send Email</button>
                                                                    </form>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <script>
                                                        // Enable Bootstrap modal functionality
                                                        $(document).ready(function() {
                                                            $('#sendEmailModal').modal({
                                                                backdrop: 'static',
                                                                keyboard: false,
                                                                show: false
                                                            });
                                                    
                                                            $('#sendEmailToAgentModal').modal({
                                                                backdrop: 'static',
                                                                keyboard: false,
                                                                show: false
                                                            });
                                                    
                                                            $('#sendEmailToHotelModal').modal({
                                                                backdrop: 'static',
                                                                keyboard: false,
                                                                show: false
                                                            });
                                                        });
                                                    </script>
                                                    
                                                @endif
                                               
                                            </td>
                                            <td> @if($item->status == 400)
                                                <span class="badge badge-danger">{{ $item->booking->booking_status }}</span>
                                                @else
                                                <span class="badge badge-success">{{ $item->booking->booking_status }}</span>
                                                @endif
                                                </td>
                                            <td>{{ $item->booking->vendor->vendor_name }}</td>
                                            <td>{{ $item->booking->users->first_name }} {{ $item->booking->users->last_name }}</td>
                                            <td>{{ $item->booking->first_name }} {{ $item->booking->last_name }}</td>
                                            <td>{{ $item->booking->total_guests }}</td>
                                            <td>{{ $item->booking->price }}</td>
                                            
                                            <td>{{ $item->booking->email }}</td>
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