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
                <li><a href="{{URL::route('give.tip', ['application_id' => $application_id])}}">Tip to freelancer</a></li>
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
                <h2>Tip to freelancer</h2>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <label for="">Available Balance: </label> {{ $available_balance }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="col-md-4">
                                <label>Add tip amount</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="tip_amount" class="form-control tip_amount" placeholder="Add amount for the tip">
                                <span class="pull-left error-span text-danger"></span>
                                <div class="clearfix"></div>
                                <br>
                                <input class="submit-tip-button btn btn-info pull-left" type="button" value="submit">
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</section>



@include('footer')
<script>
    $(document).ready(function(){
        $(".submit-tip-button").on("click", function(e){
            formErrors = new Errors();
            e.preventDefault();
            $.ajax({
                url: "{{ route('api.post.tip', ['application_id' => $application_id]) }}",
                type: 'POST',
                data: {tip_amount: $("input[name='tip_amount']").val()},
                success: function(data) {
                    /*$(".success-message").text(data[0]);
                    $(".success-message").removeClass("hide");*/
                    var transaction_id = data.transaction_id;
                    var redirectUrl = "{{ route('tip.details', ['transaction_id' => '']) }}/"+ transaction_id;
                    window.location.href = redirectUrl;
                },
                error: function(data) {
                    /*$(".error-message").text(data.responseJSON[0]);
                    $(".error-message").removeClass("hide");*/
                    var errors = data.responseJSON;
                     formErrors.record(errors);
                     formErrors.load();
                }
            });
        });
    });
</script>
</body>
</html>