@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Jobs</li>
        </ol>
        <h2 class="title">
            Payment Request Details</h2>
    </div>
@endsection
@section('content')

    <div class="section trending-ads latest-jobs-ads">
        <h4>Payment Requests</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="alert alert-danger error-message hide" role="alert">

        </div>
        <div class="alert alert-success success-message hide" role="alert">

        </div>
        @include('shared.message')
        <div class="job-ad-item">
            <div class="item-info">
                <div class="item-image-box">
                    <span>Job Title: {{$payment_request->title}}</span> <br>
                    <span>Freelancer Name: {{$payment_request->freelancer_name }}</span><br>
                </div>
                <div class="clearfix"></div>
                <div class="ad-info">
                    <div class="ad-meta">
                        <ul>
                            <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i>&pound;{{ $payment_request->number_of_hours * $payment_request->per_hour_rate }}</a></li>
                            <li><a href="#">Type: <b style="color:#00a651">{{ snakeToString($payment_request->type) }}</b></a></li>
                            <li><a href="#">Number of hours: <b style="color:#00a651">{{ $payment_request->number_of_hours }}</b></a></li>
                            <li><a href="#">Per Hour Rate: <b style="color:#00a651">{{ $payment_request->per_hour_rate }}</b></a></li>
                            <li><a href="#">Status: <b style="color:#00a651">{{ $payment_request->status }}</b></a></li>
                        </ul>
                    </div><!-- ad-meta -->
                </div><!-- ad-info -->
                @if ($payment_request->status != 'approved')
                    <div class="actions pull-right">
                        <button class="btn btn-success approve-payment-request">Approve</button>
                        <button class="btn btn-danger">Raise Dispute</button>
                    </div>
                    <div class="clearfix"></div>
                @endif
            </div><!-- item-info -->
        </div>
    </div>

@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(".approve-payment-request").on("click", function(e){
            formErrors = new Errors();
            e.preventDefault();
            $.ajax({
                url: "{{ route('api.approve.payment.request', ['payment_request_id' => $payment_request->id]) }}",
                type: 'POST',
                success: function(data) {
                    $(".success-message").text(data[0]);
                    $(".success-message").removeClass("hide");
                   /* var transaction_id = data.transaction_id;
                    var redirectUrl = "{{ route('tip.details', ['transaction_id' => '']) }}/"+ transaction_id;
                    window.location.href = redirectUrl;*/
                },
                error: function(data) {
                    $(".error-message").text(data.responseJSON[0]);
                     $(".error-message").removeClass("hide");
                    /*var errors = data.responseJSON;
                    formErrors.record(errors);
                    formErrors.load();*/
                }
            });
        });
    });
</script>
@endsection