@extends('affiliate.layout.app')
@section('title','Dashboard')
@section('content')
@php
    
    $code = session('auth_code');
@endphp
@if($code)
<div class="container mt-5">
    <!-- Custom page header alternative example-->
    <div class="row">
         <div class="jumbotron jumbotron-fluid bg-primary">
        <div class="container">
          <h1 class="display-4 text-light">Welcome Dashboard Affiliator</h1>
        </div>
      </div>
    </div>
   <div class="row">
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-body">
                <h2>{{$vendoraffiliate->count()}}</h2>
                <p class="m-0">
                   Total Hotel 
                </p>
            </div>
        </div>
        <h3>Hotels</h3>
        @foreach ($vendoraffiliate as $item)
            <div class="card mb-3">
                <div class="card-body">
                    <span>
                        <h3 class="m-0">{{$item->vendors->vendor_name}}</h3>
                        <hr>
                        <p class="m-0">{{$item->vendors->email}}</p>
                        <p class="m-0">{{$item->vendors->phone}}</p>
                        <p class="m-0">{{$item->vendors->address_line1}}</p>
                    </span>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h2 class="m-0">{{$user->code}}</h2>
                <input id="link" disabled class="form-control" value="{{route('vendorregist.affiliate',['affiliate'=>$user->code])}}"/>
                <hr>
                <p style="font-weight: 700;" class="text-danger">please copy the url above for users who will register</p>
                <button type="button" class="btn btn-primary" onclick="myFunction()">Copy</button>
            </div>
        </div>
    </div>
   </div>
</div>
@else
    <div class="container">
        <div class="row">
            <div class="jumbotron jumbotron-fluid bg-primary">
           <div class="container">
             <h1 class="display-4 text-light">Please Contact Admin...</h1>
             <a href="mailto:admin@arcturus.my.id" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 10px 20px; background-color: #3498db; color: #ffffff; text-decoration: none; border-radius: 5px;">Email Admin</a>

           </div>
         </div>
       </div>
    </div>
@endif
@endsection
@push('script')
<script>
function myFunction() {
    /* Get the text field */
    var copyText = document.getElementById("link");

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);

    /* Alert the copied text */
    alert("Copied the text: " + copyText.value);
}
</script>
@endpush