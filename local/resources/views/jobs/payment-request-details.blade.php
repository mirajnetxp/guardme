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
                        @if($payment_request->request_amount <= $available_balance || $payment_request->type == 'job_fee')
                            <button class="btn btn-success approve-payment-request">Approve</button>
                        @else
                            <form action="{{ route('add.money.paypal') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="success_url" value="{{ route('payment.request.details', ['id' => $payment_request->id]) }}">
                                <input type="hidden" name="success_message" value="Congratulations, payment has been added successfully. Please approve payment request now.">
                                <input type="hidden" name="payment_name" value="Adding balance for extra time">
                                <input type="hidden" name="amount" value="{{ $payment_request->request_amount }}">
                                <input type="submit" value="Pay with Paypal" class="btn pay-with-paypal btn-success">
                            </form>
                        @endif

                        <button class="btn btn-danger raise-dispute-button">Raise Dispute</button>
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
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
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

        // Raise Dispute
        $(".raise-dispute-button").on("click", function(){
            var message = "I do not agree to pay &#163;{{ $payment_request->request_amount }} to {{ $payment_request->freelancer_name }} for the {{ snakeToString($payment_request->type) }}.";
            $.ajax({
                url: "{{ route('api.store.ticket') }}",
                data: {title: message, message: message, category: 3},
                type: 'POST',
                success: function(data){
                    $(".success-message").text("Support ticket has been generated successfully");
                    $(".success-message").removeClass("hide");
                },
                error: function(data){
                    console.log("error");
                    console.log(data);
                }
            })
        });
    });
</script>
@endsection