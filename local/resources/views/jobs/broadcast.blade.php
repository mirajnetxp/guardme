<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
    <style type="text/css">
        .noborder ul,li { margin:0; padding:0; list-style:none;}
        .noborder .label { color:#000; font-size:16px;}
        .update{

            margin-top:10px
        }
    </style>
</head>
<body>

<!-- fixed navigation bar -->
@include('header')

        <!-- slider -->
<div class="clearfix"></div>

<div class="video">
    <div class="clearfix"></div>
    <div class="headerbg">
        <div class="col-md-12" align="center"><h1>Broadcast Job</h1></div>
    </div>
    <div class="container" >
        <div style="margin-top: 20px;"></div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(! \Auth::user()->verified)
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        We have already sent email verification to your email. Please check and confirm via the given link. Have not received yet? <a href="{!! route('user.resend_verification') !!}" class="alert-link">Resend email verification</a>.
                    </div>
                </div>
            </div>
        @endif


        @include('shared.message')

        <div class="row">
            <div class="col-md-12">
                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation"><a href="javascript:void(0)">Create job</a></li>
                        <li role="presentation"><a href="javascript:void(0)">Create Schedule</a></li>
                        <li role="presentation" class="active"><a href="javascript:void(0)">Broadcast Job</a></li>
                        <li role="presentation"><a href="javascript:void(0)">Payment</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="height30"></div>
                        <div role="tabpanel" class="tab-pane active" id="create_schedule">
                            <form id="broadcast_job" method="POST" action="{{ route('api.broadcast.job', ['id' => $id]) }}">

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="visible_to_all_security_personal" name="visible_to_all_security_personal" value="1"> All personnel
                                            <br>
                                            <span class="text-danger error-span"></span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="visible_to_favourite" class="visible_to_favourite" value="1"> Favourite security personnel
                                            <br>
                                            <span class="text-danger error-span"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="1" class="specific_area" name="specific_area">Security personnel within : <b>0 to 500km</b>
                                        <br>
                                        <span class="text-danger error-span"></span>
                                        {{--Security personnel within : <b>0 km </b>&nbsp;&nbsp; <input id="ex2"  name="specific_area_min_max" type="text" class="span2" value="" data-slider-min="0" data-slider-max="500" data-slider-step="5" data-slider-value="[0,250]"/> <b>500 km</b>--}}
                                    </label>
                                </div>

                                <div class="form-group">
                                    <b>From: </b><input type="text" name="min_area" class="min_area">
                                    <b>To: </b><input type="text" name="max_area" class="max_area">
                                    <br>
                                    <span class="text-danger error-span"></span>
                                </div>

                                <div class="form-group">
                                    <label for="specific_category_id">Security Category</label>
                                    <select type="text" name="specific_category_id" class="form-control specific_category_id" id="specific_category_id">
                                        <option value="">Please Select Category</option>
                                        @foreach($all_security_categories as $sec_cat)
                                            <option value="{{ $sec_cat->id }}">{{ $sec_cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-span"></span>
                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="terms_conditions" name="terms_conditions"> Agree to terms and conditions
                                        <br>
                                        <span class="text-danger error-span"></span>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right">Next</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="height30"></div>
        <div class="row">

        </div>

    </div>
</div>

<div class="clearfix"></div>
@include('footer')
{{--TODO have to remove this jquery and slider js--}}
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{ url('/') }}/js/bootstrap-slider.min.js"></script>--}}
<script>
    $("#ex2").slider({});
    $(document).ready(function(){

        $("form#broadcast_job").on("submit", function(e){
            formErrors = new Errors();
            e.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    var nextUrl = "{{ route('job.payment.details', ['id' => $id]) }}";
                    window.location.href = nextUrl;
                },
                error: function(data) {
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