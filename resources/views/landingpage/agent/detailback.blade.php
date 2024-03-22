@extends('landingpage.agent.layouts.app')
@section('title', 'Hotel')
@section('contents')
<section class="hero-wrap hero-wrap-2" style="background-image: url('/landing/travel/images/bg_1.jpg'); height:300px">
    <div class="overlay" style="height: 300px"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center" style="height:300px">
            <div class="col-md-9  pb-5 text-center">
                <p class="breadcrumbs"><span class="mr-2"><a href="/">Home <i
                                class="fa fa-chevron-right"></i></a></span> <span>Hotel <i
                            class="fa fa-chevron-right"></i></span></p>
                <h1 class="mb-0 bread">Hotel</h1>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-no-pb pt-0" style="bottom: 2rem;">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 mb-3 shadow" style="border-radius: 15px">
                <div class="card-body">
                    <h1 class="text-center text-danger">Contract rate expired</h1>
                    <button id="back-button" class="btn btn-danger"><i class="fa fa-undo"></i> Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script>
    const backButton = document.getElementById('back-button');

backButton.addEventListener('click', () => {
  window.history.back();
});
</script>
@endsection