<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('style')

    <script>
        window.verificationConfig = {
            url: "{{ url('/') }}"
        }
    </script>
    <style>
        .single-end-job-div {
            border-radius:10px ;
            /*box-shadow: 0 0 1px black;*/
            margin-bottom: 10px;
        }
        .single-end-job-div:hover {
            box-shadow: 0 0 5px 0px #4CAF50;
        }
        .borderless table {
            border-top-style: none;
            border-left-style: none;
            border-right-style: none;
            border-bottom-style: none;
        }

        .countdoun-pre-div {
            text-align: center;
        }

        .countDown {
            display: inline-block;
        }

        .countDown > div {
            float: left;
            background-color: #4CAF50;
            box-shadow: 0 0 2px 0px black;
            border-radius: 7px;
            font-weight: 900;
            color: #fff;
            text-align: center;

        }

        .countDown div > span {
            display: block;
            font-size: 10px;
        }
        .countDown > div {
            padding: 1px 0px 1px 0px;
            margin: 0 2px;
            font-size: 20px;
            min-width: 45px;
        }
    </style>

</head>
<body>

<?php

$url = URL::to( "/" ); ?>

@php

    if(Auth::user()->admin == 0) {

         $job = count(Responsive\Job::getMyJobs());
    } else {

        $userid = auth()->user()->id;
        $job =  DB::select("select DISTINCT ja.* from job_applications as ja JOIN security_jobs_schedule sj  ON ja.job_id = sj.job_id where ja.is_hired = 1 and applied_by = $userid and sj.end > Now() ");

        $totalfeedback = DB::table('job_applications')
                                ->select('id')
                                ->where('is_hired','1')
                                ->where('applied_by',$userid)
                                ->pluck('id')
                                ->toArray();
        $feedback =  DB::table('feedback')->whereIn('application_id',$totalfeedback)->get();

        $avg = 0.0;
        $totalfeedback = 0;
        foreach($feedback as $fb) {

            $appearance         = $fb->appearance;
            $punctuality        = $fb->punctuality;
            $customer_focused   = $fb->customer_focused;
            $security_conscious = $fb->security_conscious;
            $rating_aggregate   = ( $appearance + $punctuality + $customer_focused + $security_conscious ) / 4;

            $avg += $rating_aggregate;
            $totalfeedback++;

        }

        $rating = 0.0;

        if($totalfeedback != 0) {

            $rating = $avg / $totalfeedback;
        }

        $rating =  number_format((float)$rating, 2, '.', '');

        $job = count($job);

        if(Auth::user()->admin == 1) {

           $openTocket = DB::table('tickets')->where('state','1')->count();
        }
    }



@endphp

@include('header')


<section class="clearfix job-bg  ad-profile-page">
    <div class="container">

        @yield('bread-crumb')


		<?php $url = URL::to( "/" );
		$userphoto = "/userphoto/";
		$path = '/local/images' . $userphoto . $editprofile[0]->photo;
		?>
        <div class="job-profile section">
            <div class="user-profile">
                <div class="user-images">
                    @if($editprofile[0]->photo!="")
                        <img src="<?php echo $url . $path;?>" alt="User Images" class="img-responsive profile-img">
                    @else
                        <img src="<?php echo $url . '/local/images/nophoto.jpg';?>" alt="User Images"
                             class="img-responsive profile-img">
                    @endif
                </div>
                <div class="user">
                    <h2>Hello, <a href="#">@if($editprofile[0]->firstname!='')
                                {{$editprofile[0]->firstname.' '.$editprofile[0]->lastname}}
                            @else
                                {{$editprofile[0]->name}}
                            @endif</a></h2>

                    @if(auth()->user()->admin != 0)
                        <p><span class="stars" data-rating="{{  $rating }}" data-num-stars="5"></span>
                            <strong>{{   $rating }}</strong></p>
                @endif
                <!-- <h5>You last logged in at: 10-01-2017 6:40 AM [ USA time (GMT + 6:00hrs)]</h5> -->
                </div>

                <div class="favorites-user">
                    <div class="favorites">
                        <a href="{{$url . "/jobs/my"}}">
                            {{$job}}
                            <small>Open Jobs</small>
                        </a>
                    </div>
                    @if(Auth::user()->admin == 1)
                        <div class="favorites">
                            <a href="{{$url . "/support"}}">
                                {{$openTocket}}
                                <small>Open Tickets</small>
                            </a>
                        </div>
                    @endif
                    <div class="favorites">
                        <a href="bookmark.html">£
                            @if(isset($admin_fee) && isset($partner_fee))
                                @if(auth()->user()->admin==1)
                                    {{round($admin_fee,2)}}
                                @else
                                    {{round($partner_fee,2)}}
                                @endif
                            @endif
                            {{--@if(empty($wallet_data['available_balance']))--}}
                                {{--0.00--}}
                            {{--@else--}}
                                {{--{{ $wallet_data['available_balance'] }}--}}
                            {{--@endif--}}
                            <small>Credit Balance</small>
                        </a>
                    </div>
                </div>
            </div>

            <ul class="user-menu">
                <li class="@if(Route::current()->uri()=='account') {{'active'}} @endif"><a
                            href="{{URL::to('account')}}">Profile</a></li>

                @if($editprofile[0]->admin == 2)
                    <li class="@if(Route::current()->uri()=='verification') {{'active'}} @endif"><a
                                href="{{URL::to('verification')}}">Verification</a></li>
                @endif

                @if($editprofile[0]->admin == 0)
                    <li class="@if(Route::current()->uri()=='company') {{'active'}} @endif"><a
                                href="{{URL::to('company')}}">Company</a></li>
                @endif

                @if($editprofile[0]->admin == 2)
                    <li class="@if(Route::current()->getName()=='my.proposals') {{'active'}} @endif"><a
                                href="{{URL::route('my.proposals')}}">My Jobs</a></li>
                @endif

                @if($editprofile[0]->admin == 0)
                    <li class="@if(Route::current()->getName()=='my.jobs') {{'active'}} @endif"><a
                                href="{{URL::route('my.jobs')}}">My Jobs</a></li>
                @endif

                <li class="@if(Route::current()->uri()=='wallet-dashboard') {{'active'}} @endif"><a
                            href="{{URL::to('wallet-dashboard')}}">Wallet</a></li>
                <li class="@if(Route::current()->uri()=='referral' || Route::current()->uri()=='redeem') {{'active'}} @endif">
                    <a href="{{URL::to('referral')}}">Loyalty</a></li>


                @if(isEmployer())
                    <li class="@if(Route::current()->getName()=='my.favourite.freelancers') {{'active'}} @endif"><a
                                href="{{URL::route('my.favourite.freelancers')}}">Favourite</a></li>
                    <li class="@if(Route::current()->getName()=='my.teams') {{'active'}} @endif"><a
                                href="{{URL::route('my.teams')}}">Teams</a></li>
                    <li class="@if(Route::current()->getName()=='payment.requests') {{'active'}} @endif"><a
                                href="{{URL::route('payment.requests')}}">Payment Requests</a></li>
                @endif

                @if($editprofile[0]->admin == 1)
                    <li class="@if(Route::is('support')) {{'active'}} @endif"><a
                                href="{{URL::to('support')}}">Support</a></li>

                @endif
            </ul>
        </div>


        @yield('content')

    </div>
</section>
@include('footer')

<script type="text/javascript">
    /*read only star rating to display only*/
    $.fn.stars = function () {
        return $(this).each(function () {

            var rating = $(this).data("rating");

            var numStars = $(this).data("numStars");

            var fullStar = new Array(Math.floor(rating + 1)).join('<i class="fa fa-star"></i>');

            var halfStar = ((rating % 1) !== 0) ? '<i class="fa fa-star-half-empty"></i>' : '';

            var noStar = new Array(Math.floor(numStars + 1 - rating)).join('<i class="fa fa-star-o"></i>');

            $(this).html(fullStar + halfStar + noStar);

        });
    };
    $('.stars').stars();
</script>

@yield('script')
</body>
</html>