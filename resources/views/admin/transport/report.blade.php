@extends('layouts.admin')
@section('title', 'Transport Report Data')
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
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellSpacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Guest name</th>
                                        <th>Time pickup</th>
                                        <th>Date pickup</th>
                                        <th>Booking status</th>
                                        <th>price </th>
                                        <th>widraw</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key=>$item)
                                        <tr>
                                            <td>
                                                {{$key+1}}
                                            </td>
                                            <td>{{ $item->agenttransport->company_name }}</td>
                                            <td>{{ $item->guest_name }}</td>
                                            <td>{{ $item->time_pickup }}</td>
                                            <td>{{ $item->pickup_date }}</td>
                                            <td> @if($item->booking->booking_status != 'paid')
                                                <span class="badge badge-danger">{{ $item->booking->booking_status }}</span>
                                                @else
                                                <span class="badge badge-success">{{ $item->booking->booking_status }}</span>
                                                @endif

                                                    @php
                                                        $found = false; // Variabel untuk menandai apakah nilai ada atau tidak
                                                    @endphp
                                        
                                                    @foreach ($transport as $trans)
                                                        @if ($trans->ordertransport_id == $item->id)
                                                            
                                                            @if ($trans->status == 'on the way')
                                                                <a href="{{'/images/'.$trans->imgpickup}}" target="_blank" class="badge badge-primary">
                                                                    pickup
                                                                </a>
                                                            @elseif($trans->status == 'success')
                                                            <a href="{{'/images/'.$trans->imgpickup}}" target="_blank" class="badge badge-primary">
                                                                pickup
                                                            </a>
                                                            <a href="{{'/images/'.$trans->imgpcheckin}}" target="_blank" class="badge badge-warning">
                                                                checkin
                                                            </a>
                                                            @endif
                                                            @php
                                                                $found = true; // Ubah nilai menjadi true jika ada nilai yang cocok
                                                            @endphp
                                                            @break 
                                                        @endif
                                                    @endforeach
                                        
                                                    @if ($found)
                                                        {{-- <p>nilai ada</p> <!-- Tampilkan pesan jika nilai ada --> --}}
                                                    @endif
                                                </td>
                                            <td>{{$item->total_price_nomarkup}}</td>
                                            <td>
                                        
                                            @if ($trans && $trans->status == 'success')
                                            @php
                                                $bank = $bankAccount->where('transport_id', $item->id)->first();
                                            @endphp
                                                @if ($bank)
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{$item->transport_id}}{{$item->id}}">
                                                        <i data-feather="eye"></i>
                                                </button>

                                            <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{$item->transport_id}}{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{$item->agenttransport->company_name}}</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <ul>
                                                                <li> Bank name : {{$bank->bank_name}}</li>
                                                                <li> Bank account : {{$bank->account_number}}</li>
                                                                <li> swif_code : {{$bank->swif_code}}</li>
                                                            </ul>
                                                            @if($widrawa->count() == 0)
                                                            <form action="{{route('dashboard.transport.widraw')}}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('POST')
                                                                <input type="hidden" name="transport_id" value="{{$item->transport_id}}">
                                                                <input type="hidden" name="ordertransport_id" value="{{$item->id}}">
                                                                <input type="hidden" name="total" value="{{$item->total_price_nomarkup}}">
                                                            
                                                                <input type="file" class="form-control mb-2" name="widraw">
                                                                <button type="submit" class="btn btn-primary">upload</button>
                                                            </form>
                                                            @else
                                                                @foreach ($widrawa as  $widraw)
                                                                    @if($widraw->ordertransport_id == $item->id)
                                                                <img src="{{asset('/images/'.$widraw->image)}}" alt="" class="img-fluid">
                                                                    @else
                                                                <form action="{{route('dashboard.transport.widraw')}}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('POST')
                                                                        <input type="hidden" name="transport_id" value="{{$item->transport_id}}">
                                                                        <input type="hidden" name="ordertransport_id" value="{{$item->id}}">
                                                                        <input type="hidden" name="total" value="{{$item->total_price_nomarkup}}">
                                                                    
                                                                        <input type="file" class="form-control mb-2" name="widraw">
                                                                        <button type="submit" class="btn btn-primary">upload</button>
                                                                    </form>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            
                                                            
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                               
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