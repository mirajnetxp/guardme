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
                <li><a href="{{URL::route('leave.feedback', ['application_id' => $application_id])}}">Leave Feedback</a></li>
                <li>Job Confirmation</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">Leave Feedback</h2>
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

                <h2>Leave Feedback</h2>
                    <form action="{{ route('api.leave.feedback', ['application_id' => $application_id]) }}" class="feedback-form">
                        <div class="rating-container">
                            <div class="row">
                                <div class="col-md-4 text-right"><label for="appearance">Appearance</label></div>
                                <div class="col-md-8 text-left"><input id="appearance" name="appearance" class="rating rating-loading" data-min="0" data-max="5" data-step="1"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right"><label for="punctuality">Punctuality</label></div>
                                <div class="col-md-8 text-left"><input id="punctuality" name="punctuality" class="rating rating-loading" data-min="0" data-max="5" data-step="1"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right"><label for="customer_focused">Customer Focused</label></div>
                                <div class="col-md-8 text-left"><input id="customer_focused" name="customer_focused" class="rating rating-loading" data-min="0" data-max="5" data-step="1"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right"><label for="security_conscious">Security Conscious</label></div>
                                <div class="col-md-8 text-left"><input id="security_conscious" name="security_conscious" class="rating rating-loading" data-min="0" data-max="5" data-step="1"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4 text-right"><label for="feedback">Feedback message</label></div>
                            <div class="col-md-8"><textarea name="feedback_message" id="feedback" class="form-control" cols="30" rows="10"></textarea></div>

                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <input style="position: relative;right: 128px;" class="btn btn-info text-right" type="submit" value="submit">
                    </form>
            </div>
        </div>
    </div>
</section>



@include('footer')
<script>
    $(document).ready(function(){
        $(".feedback-form").on("submit", function(e){
            formErrors = new Errors();
            e.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    $(".success-message").text(data[0]);
                    $(".success-message").removeClass("hide");
                    console.log(data);
                },
                error: function(data) {
                    $(".error-message").text(data.responseJSON[0]);
                    $(".error-message").removeClass("hide");
                    /*var errors = data.responseJSON;
                    formErrors.record(errors);
                    formErrors.load();*/
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