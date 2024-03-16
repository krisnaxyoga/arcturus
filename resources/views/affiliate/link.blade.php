@extends('affiliate.layout.app')
@section('title','Link Afflitiate')
@section('content')
<section>
    <div class="container mt-4">
        <h1>
            @yield('title')
        </h1>
        <div class="row">
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
</section>
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