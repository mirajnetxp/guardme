<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')

    <script>
        $(document).ready(function(){
            sessionStorage.clear();

        });
    </script>
</head>
<body>

<!-- fixed navigation bar -->
@include('header')



<section class="clearfix job-bg delete-page">
    <div class="container">
        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li><a href="">Tip to freelancer</a></li>
            </ol><!-- breadcrumb -->
            <h2 class="title">Tip to freelancer</h2>
        </div><!-- banner -->

        <div class="close-account text-center">
            <div class="delete-account section">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="alert alert-danger error-message hide" role="alert">

                </div>
                <div class="alert alert-success success-message hide" role="alert">

                </div>
                @include('shared.message')
                <h2>Tip Details</h2>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <label for="">Available Balance: </label> {{ $available_balance }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table width="700" border="1" style="border-collapse: collapse; margin: 0 auto;">
                            <tbody>
                            <tr>
                                <td colspan="2"><strong>Job Details</strong></td>
                            </tr>
                            <tr>
                                <td>Job Title</td>
                                <td>{{ $application_with_job->job->title }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Freelancer Details</strong></td>
                            </tr>
                            <tr>
                                <td>Freelancer name</td>
                                <td>{{ $freelancer_details->name }}</td>
                            </tr>
                            <tr>
                                <td>Freelancer email</td>
                                <td>{{ $freelancer_details->email }}</td>
                            </tr>
                            <tr>
                                <td>Tip Amout</td>
                                <td>{{ $transaction_details->amount }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        <br>
                        @if($available_balance >= $transaction_details->amount)
                            <button class="btn confirm-tip">Confirm Tip</button>
                            @else

                            <form action="{{ route('add.money.paypal') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="success_url" value="{{ route('tip.details', ['transaction_id' => $transaction_id]) }}">
                                <input type="hidden" name="success_message" value="Congratulations, payment has been added successfully. Please confirm your tip.">
                                <input type="hidden" name="payment_name" value="Tip to freelancer">
                                <input type="hidden" name="amount" value="{{ $transaction_details->amount }}">
                                <input type="submit" value="Pay with Paypal" class="btn pay-with-paypal">
                            </form>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>



@include('footer')
<script>
    $(document).ready(function(){
        $(".confirm-tip").on("click", function(e){
            formErrors = new Errors();
            e.preventDefault();
            var url = "{{ route('api.confirm.tip', ['transaction_id' => $transaction_id]) }}";
            $.ajax({
                url: url,
                type: 'POST',
                success: function(data) {
                    var redirectUrl = "{{ route('leave.feedback', ['application_id' => $application_with_job->id]) }}";
                    $(".success-message").text(data[0]);
                    $(".success-message").removeClass("hide");
                    setTimeout(function(){
                        window.location.href = redirectUrl;
                    }, 3000);
                },
                error: function(data) {
                    console.log(data);
                    $(".error-message").text(data.responseJSON[0]);
                    $(".error-message").removeClass("hide");
                }
            });
            $('html, body').animate({
                scrollTop: $(".breadcrumb-section").offset().top
            }, 2000);
        });
    });
</script>
</body>
</html>