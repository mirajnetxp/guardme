<?php
use Illuminate\Support\Facades\Route;
$currentPaths = Route::getFacadeRoot()->current()->uri();
$url = URL::to( "/" );
$setid = 1;
$setts = DB::table( 'settings' )
           ->where( 'id', '=', $setid )
           ->get();
?>
        <!DOCTYPE html>
<html lang="en">
<head>


    @include('style')

    <style>
        .banner-job {

            @if(!empty($setts[0]->site_banner))
    background-image: url({{$url}}/local/images/settings/{{$setts[0]->site_banner}});
            @else
    background-image: url({{$url}}/img/banner.jpg);
        @endif

        }
    </style>

    <script>

        function set_loc(val) {
            //$('#loc_id').val(id);
            $('#loc_val').val(val);
        }

        function set_cat(id, val) {
            $('#cat_id').val(id);
            $('#cat_val').val(val);
        }
    </script>

</head>
<body>


<!-- fixed navigation bar -->

@include('header')


<!-- slider -->

<div class="banner-job">

    <div class="container text-center color-grey">
        <h1 class="title">Manned Security Freelance Marketplace.</h1>
        <h3>Looking for security personnel in the UK?</h3>
        <h4>Get access to thousands of vetted SIA security personnel.</h4>
        <div class="banner-form">
            <form method="POST" action="{{ route('post.find.jobs') }}" id="formID">
                {!! csrf_field() !!}
                <input type="text" class="form-control" placeholder="Type City" name="city" value="">

                <div class="dropdown category-dropdown language-dropdown">
                    <a data-toggle="dropdown" href="#">
                        <span class="change-text">
                    {{'Location'}}
                        </span></a>
                </div><!-- category-change -->


                {{--<div class="dropdown category-dropdown language-dropdown">--}}
                {{--<a data-toggle="dropdown" href="#"><span class="change-text" >--}}
                {{--@if(old('loc_val')!=NULL)--}}
                {{--{{old('loc_val')}}--}}
                {{--@else--}}
                {{--{{'Location'}}--}}
                {{--@endif--}}
                {{--</span> <i class="fa fa-angle-down"></i></a>--}}

                {{--<ul class="dropdown-menu category-change language-change loc">--}}
                {{--@foreach($locs as $loc)--}}
                {{--<li><a href="#" onclick="set_loc('{{$loc->city_town}}')">{{$loc->city_town}}</a></li>--}}
                {{--@endforeach--}}
                {{--</ul>   --}}

                {{--<input type="hidden" name="loc_val" value="{{old('loc_val')}}" id="loc_val">                        --}}
                {{--</div><!-- category-change -->--}}
                <button type="submit" class="btn btn-primary" value="Search">Search</button>
            </form>
        </div><!-- banner-form -->

        <!-- banner-socail -->
    </div><!-- container -->
</div><!-- banner-section -->

<script>
    $(document).ready(function () {
        src = "{{ route('searchajax') }}";
        $("#search_text").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            minLength: 1,

        });
    });
</script>



<div class="clearfix"></div>

<div class="page">
    <div class="container">
	<div class="section text-center">
	<h1>About the GuardME Marketplace</h1>

<p align="center"><iframe width="784" height="441" src="https://www.youtube.com/embed/orR54d5NKZE" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></p>
    </div>

<div class="section workshop-traning">
				<div class="section-title">
					<h4>Featured Jobs</h4>
					<a href="#" class="btn btn-primary">See all</a>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="workshop">
							<img src="images/job/5.png" alt="Image" class="img-responsive">
							<h3><a href="#">Business Process Management Training</a></h3>
							<h4>Course Duration: 3 Month ( Sat, Mon, Fri)</h4>
							<div class="workshop-price">
								<h5>Course instructor: Kim Jon ley</h5>
								<h5>Course Amount: $200</h5>
							</div>
							<div class="ad-meta">
								<div class="meta-content">
									<span class="dated"><a href="#">7 Jan 10:10 pm </a></span>
								</div>
								<div class="user-option pull-right">
									<a href="#"><i class="fa fa-map-marker"></i> </a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="workshop">
							<img src="images/job/6.png" alt="Image" class="img-responsive">
							<h3><a href="#">Employee Motivation and Engagement</a></h3>
							<h4>Course Duration: 3 Month ( Sat, Mon, Fri)</h4>
							<div class="workshop-price">
								<h5>Course instructor: Kim Jon ley</h5>
								<h5>Course Amount: $200</h5>
							</div>
							<div class="ad-meta">
								<div class="meta-content">
									<span class="dated"><a href="#">7 Jan 10:10 pm </a></span>
								</div>
								<div class="user-option pull-right">
									<a href="#"><i class="fa fa-map-marker"></i> </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- workshop-traning -->

			<div class="section cta cta-two text-center">
				<div class="row">
					<div class="col-sm-4">
						<div class="single-cta">
							<div class="cta-icon icon-jobs">
								<img src="images/icon/31.png" alt="Icon" class="img-responsive">
							</div><!-- cta-icon -->
							<h3>112</h3>
							<h4>Live Jobs</h4>
						</div>
					</div><!-- single-cta -->

					<div class="col-sm-4">
						<div class="single-cta">
							<!-- cta-icon -->
							<div class="cta-icon icon-company">
								<img src="images/icon/32.png" alt="Icon" class="img-responsive">
							</div><!-- cta-icon -->
							<h3>97</h3>
							<h4>Total Employers</h4>
						</div>
					</div><!-- single-cta -->

					<div class="col-sm-4">
						<div class="single-cta">
							<div class="cta-icon icon-candidate">
								<img src="images/icon/33.png" alt="Icon" class="img-responsive">
							</div><!-- cta-icon -->
							<h3>204</h3>
							<h4>Security Contractors</h4>
						</div>
					</div><!-- single-cta -->
				</div><!-- row -->
			</div><!-- cta -->			

		</div><!-- container -->
	
           </div><!-- page -->
           
            

        </div>
    </div>
</div>

</div>
</div>


<!-- download -->
<section id="download" class="clearfix parallax-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>Download on App Store</h2>
            </div>
        </div><!-- row -->

        <!-- row -->
        <div class="row">
            <!-- download-app -->
            <div class="col-sm-4">
                <a href="https://play.google.com/store/apps/details?id=com.guarddme.com" class="download-app">
                    <img src="images/icon/16.png" alt="Image" class="img-responsive">
                    <span class="pull-left">
                            <span>available on</span>
                            <strong>Google Play</strong>
                        </span>
                </a>
            </div><!-- download-app -->
            <!-- download-app -->
            <div class="col-sm-4">
                <a href="#" class="download-app">
                    <img src="images/icon/17.png" alt="Image" class="img-responsive">
                    <span class="pull-left">
                            <span>available on</span>
                            <strong>App Store</strong>
                        </span>
                </a>
            </div><!-- download-app -->
            <!-- download-app -->
            <div class="col-sm-4">
                <a href="#" class="download-app">
                    <img src="images/icon/18.png" alt="Image" class="img-responsive">
                    <span class="pull-left">
                            <span>available on</span>
                            <strong>Windows Store</strong>
                        </span>
                </a>
            </div><!-- download-app -->
        </div><!-- row -->
    </div><!-- contaioner -->
</section><!-- download -->


@include('footer')
</body>
</html>
