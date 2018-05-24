<!DOCTYPE html>
<html lang="en">
<head>


    @include('style')

    <style type="text/css">
        .noborder ul, li {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .noborder .label {
            color: #000;
            font-size: 16px;
        }
    </style>

    <script>

        function set_loc(id, val) {
            $('#loc_id').val(id);
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

<?php $url = URL::to( "/" ); ?>

<!-- fixed navigation bar -->
@include('header')

<section class="job-bg page job-list-page">
    <div class="container">
        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li>Search</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">Search Security Personnel</h2>
        </div>

        <div class="banner-form banner-form-full job-list-form">
            <form method="get" action="{{ route('post-personnel-search') }}" id="formID">
                <!-- category-change -->
                <div class="dropdown category-dropdown">
                    <a data-toggle="dropdown" href="#">
						<span class="change-text">
							@if(old('cat_val')!=null)
                                {{old('cat_val')}}
                            @else
                                {{'Category'}}
                            @endif
						</span> <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu category-change cat">
                        <li><a href="#" onclick="set_cat(-1,'all')">All Category</a></li>
                        @foreach($cats as $cat)
                            <li><a href="#" onclick="set_cat({{$cat->id}},'{{$cat->name}}')">{{$cat->name}}</a></li>
                        @endforeach
                    </ul>
                    <input type="hidden" name="cat_id" value="{{old('cat_id')}}" id="cat_id">
                    <input type="hidden" name="cat_val" value="{{old('cat_val')}}" id="cat_val">
                </div><!-- category-change -->

                <div class="dropdown category-dropdown language-dropdown">
                    <a data-toggle="dropdown" href="#">
                        <span class="change-text">Gender</span> <i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu category-change language-change loc">
                        <li><a href="#" onclick="set_loc('gender','all')">All Gender</a></li>
                        <li><a href="#" onclick="set_loc('gender','male')">Male</a></li>
                        <li><a href="#" onclick="set_loc('gender','female')">Female</a></li>
                    </ul>

                    <input type="hidden" name="gender" value="" id="loc_val">
                </div>

            {{--<div class="dropdown category-dropdown language-dropdown">
                <a data-toggle="dropdown" href="#"><span class="change-text" >
                    @if(old('loc_val')!=NULL)
                            {{old('loc_val')}}
                        @else
                            {{'Location'}}
                        @endif
                    </span> <i class="fa fa-angle-down"></i></a>
                <ul class="dropdown-menu category-change language-change loc">
                    @foreach($locs as $loc)
                        <li><a href="#" onclick="set_loc({{$loc->id}},'{{$loc->citytown}}')">{{$loc->citytown}}</a></li>
                    @endforeach
                </ul>
                <input type="hidden" name="loc_id" value="{{old('loc_id')}}" id="loc_id">
                <input type="hidden" name="loc_val" value="{{old('loc_val')}}" id="loc_val">
            </div>--}}

            <!-- language-dropdown -->

                <input type="text" class="form-control" placeholder="Security Personnel" name="sec_personnel"
                       value="{{old('sec_personnel')}}">
                <input type="hidden" class="form-control post_code" placeholder="" name="post_code" id="" value="">
                <input type="hidden" class="form-control distance" placeholder="" name="distance" id="" value="1">
                <button type="submit" class="btn btn-primary" value="Search">Search</button>
            </form>
        </div>

        <div class="category-info">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <div class="accordion">
                        <!-- panel-group -->
                        <div class="panel-group" id="accordion">

                            <!-- gender panel -->
                        {{--<div class="panel panel-default panel-faq">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <div  class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#accordion-one">
                                        <h4>Gender<span class="pull-right"><i class="fa fa-minus"></i></span></h4>
                                    </a>
                                </div>
                            </div><!-- panel-heading -->

                            <div id="accordion-one" class="panel-collapse collapse in">
                                <!-- panel-body -->
                                <div class="panel-body">

                                </div><!-- panel-body -->
                            </div>
                        </div>--}}

                        <!-- Location -->
                            <div class="panel panel-default panel-faq">
                                <!-- panel-heading -->
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#accordion-three">
                                            <h4>Location<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                        </a>
                                    </div>
                                </div><!-- panel-heading -->

                                <div id="accordion-three" class="panel-collapse collapse">
                                    <!-- panel-body -->
                                    <div class="panel-body">
                                        <form method="get" action="{{ route('post-personnel-search') }}">
                                            <div class="form-group">
                                                <input type="text" class="form-control " name="location_filter">
                                                <button class="btn-sm btn btn-default" type="submit">filter</button>
                                            </div>
                                        </form>

                                    </div><!-- panel-body -->
                                </div>
                            </div>
                        </div>

                        <!-- available panel -->
                    {{--<div class="panel panel-default panel-faq">
                        <!-- panel-heading -->
                        <div class="panel-heading">
                            <div  class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#accordion-three">
                                    <h4>Available<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                </a>
                            </div>
                        </div><!-- panel-heading -->

                        <div id="accordion-three" class="panel-collapse collapse">
                            <!-- panel-body -->
                            <div class="panel-body">

                            </div><!-- panel-body -->
                        </div>
                    </div>--}}

                    <!-- panel -->


                        <!-- panel -->
                        <div class="panel panel-default panel-faq">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#accordion-five">
                                        <h4>Star rating<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                    </a>
                                </div>
                            </div><!-- panel-heading -->

                            <div id="accordion-five" class="panel-collapse collapse">
                                <!-- panel-body -->
                                <div class="panel-body">

                                </div><!-- panel-body -->
                            </div>
                        </div>
                        <!-- panel -->
                        <div class="panel panel-default panel-faq">
                            <!-- panel-heading -->
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#accordion-six">
                                        <h4>Distance to<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
                                    </a>
                                </div>
                            </div><!-- panel-heading -->

                            <div id="accordion-six" class="panel-collapse distance-data collapse">
                                <form method="get" action="{{ route('post-personnel-search') }}" id="formID">
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
                                    <input type="hidden" name="gender" value="" id="">
                                    <input type="hidden" name="sec_personnel" value="" id="">
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
                <div class="col-sm-8 col-md-7">

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
						<?php if($sec_personnels->count() > 0){?>

						<?php foreach($sec_personnels as $person){ ?>

                        @if($person->freelancerSettings->visible==1)
                        <div class="job-ad-item">
                            <div class="item-info">
                                <div class="item-image-box">
                                    <div class="item-image">
										<?php

										$photo_path = '/local/images/userphoto/' . $person->photo;
										if($person->photo != ""){?>
                                        <a href="{{ route('person-profile',$person->id) }}"><img
                                                    src="<?php echo $url . $photo_path;?>" class="img-responsive"></a>
										<?php } else { ?>
                                        <a href="{{ route('person-profile',$person->id) }}"><img align="center"
                                                                                                 class="img-responsive"
                                                                                                 src="<?php echo $url . '/local/images/nophoto.jpg';?>"
                                                                                                 alt="Profile Photo"/></a>
										<?php } ?>


                                    </div><!-- item-image -->
                                </div>

                                <div class="ad-info">
					<span><a href="{{ route('person-profile',$person->id) }}" class="title">@if($person->firstname!='')
                                {{$person->firstname.' '.$person->lastname}}
                            @else
                                {{$person->name}}
                            @endif</a> </span>
                                    <div class="ad-meta">
                                        <ul>
                                            <li><a href="{{ route('person-profile',$person->id) }}"><i
                                                            class="fa fa-map-marker"
                                                            aria-hidden="true"></i>@if($person->citytown){{$person->citytown}} @endif
                                                </a></li>
                                            <li><a href="{{ route('person-profile',$person->id) }}"><i
                                                            class="fa fa-clock-o" aria-hidden="true"></i>
													<?php //echo $stime; ?> - <?php /*echo $etime; */?>
                                                </a></li>
                                            <!-- <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i>$25,000 - $35,000</a></li> -->
                                        </ul>
                                    </div><!-- ad-meta -->
                                </div><!-- ad-info -->
                            </div><!-- item-info -->
                        </div>
                        @endif
						<?php } ?>

						<?php }
						else{?>

                        <div class="col-md-12 noservice" align="center">No personnels found!</div>

					<?php } ?>



                    <!-- pagination  -->
                        <div class="text-center">
                            {{$sec_personnels->links()}}
                        </div><!-- pagination  -->
                    </div>
                </div>


                <div class="col-md-2 hidden-xs hidden-sm">
                    <div class="advertisement text-center">
                        <a href="#"><img src="images/ads/1.jpg" alt="" class="img-responsive"></a>
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
    });

    function getDistanceLength(distanceval) {
        $('.distance').val(distanceval);
    }

</script>

@include('footer')
</body>
</html>