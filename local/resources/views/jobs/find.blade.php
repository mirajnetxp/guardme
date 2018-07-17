<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
    <script>

        function set_loc(val) {
            //$('#loc_id').val(id);
            $('#loc_val').val(val);
        }

        function set_cat(id, val) {
            $('#cat_id').val(id);
            $('#cat_val').val(val);
        }

        $(document).ready(function () {
            //$('.content-data').hide();
            $('.skeleton').show();

        });

        $(window).load(function () {
            $('.content-data').show();
            $('.skeleton').hide();
        });

    </script>

</head>
<body>

<!-- fixed navigation bar -->
@include('header')
@if(session()->has('login_first'))
    <div class="container-fluid" style="background-color: #e91e63">
        <h5 class="text-center" style="color: #ffffff">{{session()->get('login_first')}}</h5>
    </div>
@endif
<section class="job-bg page job-list-page">
    <div class="container">
        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li>Jobs</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">Jobs</h2>
        </div>
        <div class="banner-form banner-form-full job-list-form">
            <form method="POST" action="{{ route('post.find.jobs') }}" id="city-search">
                <!-- category-change -->
                <div class="dropdown category-dropdown">
                    {!! csrf_field() !!}
                    <a data-toggle="dropdown" href="#">
                        <span class="change-text">
                            @if(old('cat_val')!=null)
                                {{old('cat_val')}}
                            @else
                                {{'Industry'}}
                            @endif
                        </span> <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu category-change cat">
                        @foreach($b_cats as $cat)
                            <li><a href="#" onclick="set_cat({{$cat->id}},'{{$cat->name}}')">{{$cat->name}}</a></li>
                        @endforeach
                    </ul>
                    <input type="hidden" name="cat_id" value="{{old('cat_id')}}" id="cat_id">
                    <input type="hidden" name="cat_val" value="{{old('cat_val')}}" id="cat_val">
                </div><!-- category-change -->

                <!-- language-dropdown -->
            {{--<div class="dropdown category-dropdown language-dropdown">--}}
            {{--<a data-toggle="dropdown" href="#"><span class="change-text">--}}
            {{--@if(old('loc_val')!=null)--}}
            {{--{{old('loc_val')}}--}}
            {{--@else--}}
            {{--{{'Location'}}--}}
            {{--@endif--}}
            {{--</span> <i class="fa fa-angle-down"></i></a>--}}
            {{--<ul class="dropdown-menu category-change language-change loc">--}}
            {{--@foreach($locs as $loc)--}}
            {{--<li><a href="#" onclick="set_loc('{{$loc->city_town}}')">{{$loc->city_town}}</a></li>--}}
            {{--@endforeach--}}
            {{--</ul>--}}

            {{--<input type="hidden" name="loc_val" value="{{old('loc_val')}}" id="loc_val">--}}
            {{--</div>--}}
            <!-- language-dropdown -->
                <input type="text" class="form-control" placeholder="Type City" name="city" value="{{old('city')}}">
                {{--<input type="hidden" class="form-control post_code" placeholder="" name="post_code" id="" value="">--}}
                {{--<input type="hidden" class="form-control distance" placeholder="" name="distance" id="" value="1">--}}
                <button type="submit" class="btn btn-primary" value="Search">Search</button>
            </form>
        </div>

        <div class="category-info">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <div class="accordion">
                        <!-- panel-group Freelancer rating-->
                        {{--<div class="panel-group" id="accordion">--}}

                            {{--<!-- panel -->--}}
                            {{--<div class="panel panel-default panel-faq">--}}
                                {{--<!-- panel-heading -->--}}
                                {{--<div class="panel-heading">--}}
                                    {{--<div class="panel-title">--}}
                                        {{--<a data-toggle="collapse" data-parent="#accordion" href="#accordion-one">--}}
                                            {{--<h4>Freelancer Rating<span class="pull-right"><i--}}
                                                            {{--class="fa fa-minus"></i></span></h4>--}}
                                        {{--</a>--}}
                                    {{--</div>--}}
                                {{--</div><!-- panel-heading -->--}}
                                {{--<div id="accordion-one" class="panel-collapse collapse in">--}}
                                    {{--<!-- panel-body -->--}}
                                    {{--<div class="panel-body">--}}
                                        {{--<form method="POST" action="{{ route('post.find.jobs') }}">--}}
                                            {{--{{csrf_field()}}--}}
                                            {{--<div class="form-group row" style="width:240px; padding-left:18px;">--}}
                                                {{--<div id="skipstepfreelancerrating"></div>--}}
                                                {{--<span class="example-val-from" id="skip-value-lowerfree"></span>--}}
                                                {{--<span class="example-val-to" id="skip-value-upperfree"></span>--}}
                                                {{--<input type="hidden" name="min_freelancer_rating"--}}
                                                       {{--id="min_freelancer_rating" value=""--}}
                                                       {{--class=" form-control">--}}
                                                {{--<input type="hidden" name="max_freelancer_rating"--}}
                                                       {{--id="max_freelancer_rating" value=""--}}
                                                       {{--class=" form-control">--}}
                                            {{--</div>--}}
                                            {{--<button type="submit" class="btn btn-info btn-small">Filter</button>--}}
                                        {{--</form>--}}
                                    {{--</div><!-- panel-body -->--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <!-- panel -->
                        <div class="panel panel-default panel-faq">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#accordion-three">
                                        <h4>Pay Per Hour<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                    </a>
                                </div>
                            </div><!-- panel-heading -->

                            <div id="accordion-three" class="panel-collapse collapse in">
                                <!-- panel-body -->
                                <div class="panel-body">

                                    <form method="POST" action="{{ route('post.find.jobs') }}">
                                        {{csrf_field()}}
                                        <div class="form-group row" style="width:240px; padding-left:18px;">
                                            <div id="skipstep"></div>
                                            <span class="example-val-from" id="skip-value-lower"></span>
                                            <span class="example-val-to" id="skip-value-upper"></span>
                                            <input type="hidden" name="min_rate" id="min_rate" value=""
                                                   class="min_area form-control">
                                            <input type="hidden" name="max_rate" id="max_rate" value=""
                                                   class="max_area form-control">
                                        </div>
                                        <button type="submit" class="btn btn-info btn-small">Filter</button>
                                    </form>
                                </div><!-- panel-body -->
                            </div>
                        </div>

                        <!-- panel job type  -->
                        <div class="panel panel-default panel-faq">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#accordion-four">
                                        <h4>Job Type<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                    </a>
                                </div>
                            </div><!-- panel-heading -->

                            <div id="accordion-four" class="panel-collapse collapse in">
                                <!-- panel-body -->
                                <div class="panel-body">
                                    <form method="POST" action="{{ route('post.find.jobs') }}" id="city-search">
                                        {{csrf_field()}}
                                        <label for="full-time">
                                            <input type="radio" name="workingDays" value="oneDay"> 1 Day</label>

                                        <label for="part-time">
                                            <input type="radio" name="workingDays" value="oneDayPlus"> 1 Day+
                                        </label>
                                        <button type="submit" class="btn btn-info btn-small">Filter</button>
                                    </form>
                                    {{--<label for="contractor"><input type="checkbox" name="contractor" id="contractor">--}}
                                    {{--Contractor</label>--}}
                                    {{--<label for="intern"><input type="checkbox" name="intern" id="intern"> Intern</label>--}}
                                    {{--<label for="seasonal"><input type="checkbox" name="seasonal" id="seasonal"> Seasonal--}}
                                    {{--/ Temp</label>--}}
                                </div><!-- panel-body -->
                            </div>
                        </div>

                        <!-- panel -->
                        <div class="panel panel-default panel-faq">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#accordion-six">
                                        <h4>Distance<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                    </a>
                                </div>
                            </div><!-- panel-heading -->
                            <div id="accordion-six" class="panel-collapse distance-data collapse">
                                <form method="get" action="{{ route('post.find.jobs') }}" id="formID">
                                    <ul class="radio">
                                        <li><input type="radio" name="crust" value="1" title="0-10 KM" checked=""
                                                   onClick="getDistanceLength(1);"/>0-10 KM
                                        </li>
                                        <li><input type="radio" name="crust" value="2" title="11-20 KM"
                                                   onClick="getDistanceLength(2);"/>11-20 KM
                                        </li>
                                        <li><input type="radio" name="crust" value="3" title="21-50 KM"
                                                   onClick="getDistanceLength(3);"/>21-50 KM
                                        </li>
                                        <li><input type="radio" name="crust" value="4" title="50+ KM"
                                                   onClick="getDistanceLength(4);"/>50+ KM
                                        </li>
                                    </ul>
                                    <!-- panel-body -->
                                    <div class="panel-body">
                                        <input type="text" name="hidden_post_code" id="hidden_post_code" onblur=""
                                               placeholder="Postcode" class="form-control">
                                    </div><!-- panel-body -->
                                    <div class="panel-body">
                                        <button class="btn-sm btn btn-default" type="submit">filter</button>
                                    </div>
                                    <input type="hidden" name="cat_id" value="" id="">
                                    <input type="hidden" name="cat_val" value="" id="">
                                    <input type="hidden" name="loc_val" value="" id="">
                                    <input type="hidden" name="keyword" value="" id="">
                                    <input type="hidden" class="form-control post_code" placeholder="" name="post_code"
                                           id="" value="">
                                    <input type="hidden" class="form-control distance" placeholder="" name="distance"
                                           id="" value="1">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- recommended-ads -->
                <div class="col-sm-8 col-md-9">

                    <div class="section job-list-item skeleton">

                        <div class="featured-top clearfix">
                            <div class="dropdown pull-right">
                                <div class="dropdown category-dropdown">
                                    <h5>Sort by:</h5>
                                    <a data-toggle="dropdown" href="#"><span class="change-text">Most Relevant</span><i
                                                class="fa fa-caret-square-o-down"></i></a>
                                    <ul class="dropdown-menu category-change">
                                        <li><a href="#">Most Relevant</a></li>
                                        <li><a href="#">Most Popular</a></li>
                                    </ul>
                                </div><!-- category-change -->
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="animated-background facebook">
                                <div class="background-masker header-top"></div>
                                <div class="background-masker header-left"></div>
                                <div class="background-masker header-right"></div>
                                <div class="background-masker header-bottom"></div>
                                <div class="background-masker subheader-left"></div>
                                <div class="background-masker subheader-right"></div>
                                <div class="background-masker subheader-bottom"></div>
                                <div class="background-masker content-top"></div>
                                <div class="background-masker content-first-end"></div>
                                <div class="background-masker content-second-line"></div>
                                <div class="background-masker content-second-end"></div>
                                <div class="background-masker content-third-line"></div>
                                <div class="background-masker content-third-end"></div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="animated-background facebook">
                                <div class="background-masker header-top"></div>
                                <div class="background-masker header-left"></div>
                                <div class="background-masker header-right"></div>
                                <div class="background-masker header-bottom"></div>
                                <div class="background-masker subheader-left"></div>
                                <div class="background-masker subheader-right"></div>
                                <div class="background-masker subheader-bottom"></div>
                                <div class="background-masker content-top"></div>
                                <div class="background-masker content-first-end"></div>
                                <div class="background-masker content-second-line"></div>
                                <div class="background-masker content-second-end"></div>
                                <div class="background-masker content-third-line"></div>
                                <div class="background-masker content-third-end"></div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="animated-background facebook">
                                <div class="background-masker header-top"></div>
                                <div class="background-masker header-left"></div>
                                <div class="background-masker header-right"></div>
                                <div class="background-masker header-bottom"></div>
                                <div class="background-masker subheader-left"></div>
                                <div class="background-masker subheader-right"></div>
                                <div class="background-masker subheader-bottom"></div>
                                <div class="background-masker content-top"></div>
                                <div class="background-masker content-first-end"></div>
                                <div class="background-masker content-second-line"></div>
                                <div class="background-masker content-second-end"></div>
                                <div class="background-masker content-third-line"></div>
                                <div class="background-masker content-third-end"></div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="animated-background facebook">
                                <div class="background-masker header-top"></div>
                                <div class="background-masker header-left"></div>
                                <div class="background-masker header-right"></div>
                                <div class="background-masker header-bottom"></div>
                                <div class="background-masker subheader-left"></div>
                                <div class="background-masker subheader-right"></div>
                                <div class="background-masker subheader-bottom"></div>
                                <div class="background-masker content-top"></div>
                                <div class="background-masker content-first-end"></div>
                                <div class="background-masker content-second-line"></div>
                                <div class="background-masker content-second-end"></div>
                                <div class="background-masker content-third-line"></div>
                                <div class="background-masker content-third-end"></div>
                            </div>
                        </div>
                    </div>
                    <div class="section job-list-item content-data" style="display:none">
                        <div class="featured-top">

                            <div class="dropdown pull-right">
                                <div class="dropdown category-dropdown">
                                    <h5>Sort by:</h5>
                                    <a data-toggle="dropdown" href="#"><span class="change-text">Most Relevant</span><i
                                                class="fa fa-caret-square-o-down"></i></a>
                                    <ul class="dropdown-menu category-change">
                                        <li><a href="#">Most Relevant</a></li>
                                        <li><a href="#">Most Popular</a></li>
                                    </ul>
                                </div><!-- category-change -->
                            </div>
                        </div><!-- featured-top -->


                        @if(Session::has('flash_message'))
                            <div class="messagebox">
                                <div class="errormsg">
                                    {{ Session::get('flash_message') }}
                                </div>
                            </div>
                        @endif
						<?php if(count( $sort_jobs ) > 0){?>

						<?php foreach($sort_jobs as $job){ ?>

                        <div class="job-ad-item">
                            <div class="item-info">
                            <!-- <div class="item-image-box">
                    <div class="item-image">
                      
                        <a href="{{ route('view.job',$job->id) }}" ><img align="center" class="img-responsive" src="{{ URL::to("/")}}/images/img-placeholder.png" alt="{{$job->title}}"/></a>
                        
                    </div> 
                </div> -->

                                <div class="ad-info">
                                    <span><a href="{{ route('view.job',$job->id) }}" class="title">{{$job->title}}</a> </span>
                                    <div class="ad-meta">
                                        <ul>
                                            <li><a href="{{ route('view.job',$job->id) }}"><i class="fa fa-map-marker"
                                                                                              aria-hidden="true"></i>
                                                    @if($job->city_town){{$job->city_town}} @endif </a></li>
                                            <li><a href="{{ route('view.job',$job->id) }}"><i class="fa fa-clock-o"
                                                                                              aria-hidden="true"></i>
                                                    {{date('d/m/Y',strtotime($job->created_at))}}
                                                </a></li>
                                            <li><a href="#"><i class="fa fa-money"
                                                               aria-hidden="true"></i>&pound;{{$job->per_hour_rate}}</a>
                                            </li>
                                            <li>@if($job->is_hired)
                                                    <i class="fa fa-check-circle-o ico-30 green"></i>
                                                    Applied Date: {{date('M d, Y',strtotime($job->applied_date))}}
                                                @endif
                                            </li>
                                        </ul>
                                    </div><!-- ad-meta -->
                                </div><!-- ad-info -->
                            </div><!-- item-info -->
                        </div>

						<?php } ?>

						<?php }
						else{?>


                        <div class="col-md-12 noservice" align="center">No job matching found!</div>

						<?php } ?>

                        <div class="text-center">
                            @if(isset($paginationLinksHtml ))
                                {{ $paginationLinksHtml }}
                            @endif
                            {{$sort_jobs->links()}}
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function ($) {
        $('#hidden_post_code').on('blur', function () {
            if ($(this).val() != '') {
                $('.post_code').val($(this).val());
            }
        });

//================================
        var skipSlider = document.getElementById('skipstep');
        noUiSlider.create(skipSlider, {
            range: {
                'min': 8,
                '20%': 10,
                '40%': 12,
                '60%': 14,
                '80%': 16,
                '90%': 18,
                'max': 20
            },
            snap: true,
            start: [0, 100]
        });
        var skipValues = [
            document.getElementById('skip-value-lower'),
            document.getElementById('skip-value-upper')
        ];
        var skipInputValues = [
            document.getElementById('min_rate'),
            document.getElementById('max_rate')
        ];

        skipSlider.noUiSlider.on('update', function (values, handle) {
            skipValues[handle].innerHTML = values[handle];
            skipInputValues[handle].value = values[handle];
            //alert(values[handle]);
        });

//        //////////////////////////////============================
//        var skipstepfreelancerrating = document.getElementById('skipstepfreelancerrating');
//        noUiSlider.create(skipstepfreelancerrating, {
//            range: {
//                'min': 0,
//                '20%': 1,
//                '30%': 1.5,
//                '35%': 1.75,
//                '40%': 2,
//                '50%': 2.5,
//                '60%': 3,
//                '70%': 3.5,
//                '75%': 3.75,
//                '80%': 4,
//                '90%': 4.5,
//                'max': 5
//            },
//            snap: true,
//            start: [0, 100]
//        });
//        var skipValuesf = [
//            document.getElementById('skip-value-lowerfree'),
//            document.getElementById('skip-value-upperfree')
//        ];
//        var skipInputValuesf = [
//            document.getElementById('min_freelancer_rating'),
//            document.getElementById('max_freelancer_rating')
//        ];
//        skipstepfreelancerrating.noUiSlider.on('update', function (values, handle) {
//            skipValuesf[handle].innerHTML = values[handle];
//            skipInputValuesf[handle].value = values[handle];
//            //alert(values[handle]);
//        });


    });

    function getDistanceLength(distanceval) {
        $('.distance').val(distanceval);
    }

</script>
@include('footer')


</body>
</html>