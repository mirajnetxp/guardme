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
                <li><a href="#">Add Extra time</a></li>
            </ol><!-- breadcrumb -->
            <h2 class="title">Log extra time</h2>
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
                <h2>Log Extra Time</h2>
                <div class="row">
                    <div class="col-md-10">
                        <div class="col-md-4">
                            <label>Extra hours</label>
                        </div>
                        <div class="col-md-8">
                            <input type="number" name="number_of_hours" class="form-control number_of_hours" placeholder="Add number of hours">
                            <span class="pull-left error-span text-danger"></span>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>
                    <br>
                <div class="row">
                    <div class="col-md-10">
                        <div class="col-md-4">
                            <label>Work details</label>
                        </div>
                        <div class="col-md-8">
                            <textarea name="description" rows="7" class="form-control description" placeholder="Add description"></textarea>
                            <div class="clearfix"></div>
                            <br><input class="submit-tip-button btn btn-info pull-left" type="button" value="submit">
                        </div>
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
                url: "{{ route('api.create.payment.request') }}",
                type: 'POST',
                data: {number_of_hours: $("input[name='number_of_hours']").val(), description: $("textarea[name='description']").val(), application_id: '{{ $application_id }}', type: 'extra_time'},
                success: function(data) {
                    $(".success-message").text(data[0]);
                    $(".success-message").removeClass("hide");
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