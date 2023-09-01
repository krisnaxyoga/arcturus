@extends('layouts.admin')
@section('title', 'Hotel Payroll')
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
                                            <th>Bank Account</th>
                                            <th>Account Number</th>
                                            <th>Bank Address</th>
                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->vendor_name }}</td>
                                                <td>{{ $item->bank_account }}</td>
                                                <td>{{ $item->account_number }}</td>
                                                <td>{{ $item->bank_address }}</td>
                                                <td><a href="{{ route('dashboard.paymenttohotel.edit', $item->id) }}"
                                                        class="btn btn-datatable btn-icon btn-transparent-dark mr-2"><i
                                                            data-feather="share"></i></a>
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
