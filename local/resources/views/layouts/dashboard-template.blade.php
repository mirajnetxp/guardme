<!DOCTYPE html>
<html lang="en">
<head>

<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

   @include('style')  

    <script>
           window.verificationConfig =  {
              url  : "{{ url('/') }}"
          }
      </script>

      

</head>
<body>

    <?php

    $url = URL::to("/"); ?>

    @include('header')


     <section class="clearfix job-bg  ad-profile-page">
        <div class="container">

            @yield('bread-crumb')


            <?php $url = URL::to("/");
                $userphoto="/userphoto/";
                        $path ='/local/images'.$userphoto.$editprofile[0]->photo;
                        ?>
            <div class="job-profile section">   
                <div class="user-profile">
                    <div class="user-images">
                        @if($editprofile[0]->photo!="")
                            <img src="<?php echo $url.$path;?>" alt="User Images" class="img-responsive profile-img">
                        @else
                            <img src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="User Images" class="img-responsive profile-img">
                        @endif
                    </div>
                    <div class="user">
                        <h2>Hello, <a href="#">@if($editprofile[0]->firstname!='')
                                        {{$editprofile[0]->firstname.' '.$editprofile[0]->lastname}}
                                    @else
                                        {{$editprofile[0]->name}}
                                    @endif</a></h2>
                        <!-- <h5>You last logged in at: 10-01-2017 6:40 AM [ USA time (GMT + 6:00hrs)]</h5> -->
                    </div>
                    <div class="favorites-user">
                        <div class="my-ads">
                            <a href="{{ url('view/feedback') }}">
                                <h1 style="margin-bottom:-35px">{{ $feedback }}</h1>
                                <?php
                                    for($i=1;$i<=5;$i++)
                                    {
                                        if($feedback >= $i)
                                        {
                                            echo '<i class="fas fa-star" style="color: #ff0;font-size: 20px"></i>';
                                        }
                                        else
                                        {
                                            echo '<i class="far fa-star" style="color: #000;font-size: 20px"></i>';
                                        }
                                    }
                                ?>
                                <small>FeedBack</small></a>
                        </div>
                        <div class="favorites">
                            <a>{{ $balance }}<small>Balance</small></a>
                        </div>
                    </div> 
                </div>

                <ul class="user-menu">
                    <li  class="@if(Route::current()->uri()=='account') {{'active'}} @endif"><a href="{{URL::to('account')}}">Profile</a></li>

                     @if($editprofile[0]->admin == 2)       
                        <li class="@if(Route::current()->uri()=='verification') {{'active'}} @endif"><a href="{{URL::to('verification')}}">Verification</a></li>
                    @endif

                    @if($editprofile[0]->admin == 0)
                        <li class="@if(Route::current()->uri()=='company') {{'active'}} @endif"><a href="{{URL::to('company')}}">Company</a></li>
                    @endif

                    @if($editprofile[0]->admin == 2)  
                    <li class="@if(Route::current()->getName()=='my.proposals') {{'active'}} @endif"><a href="{{URL::route('my.proposals')}}">My Jobs</a></li>
                    @endif

                    @if($editprofile[0]->admin == 0)  
                    <li class="@if(Route::current()->getName()=='my.jobs') {{'active'}} @endif"><a href="{{URL::route('my.jobs')}}">My Jobs</a></li>
                    @endif

                    <li class="@if(Route::current()->uri()=='wallet-dashboard') {{'active'}} @endif"><a href="{{URL::to('wallet-dashboard')}}" >Wallet</a></li>
                    <li class="@if(Route::current()->uri()=='referral' || Route::current()->uri()=='redeem') {{'active'}} @endif"><a href="{{URL::to('referral')}}">Loyalty</a></li>
                    <li class="@if(Route::current()->uri()=='support') {{'active'}} @endif"><a href="{{URL::to('support')}}">Support</a></li>
                    <li><a href="{{URL::to('delete_account')}}" >Close account</a></li>
                </ul>
            </div>


            @yield('content')

        </div>
    </section>
   @include('footer')

       @yield('script')
</body>
</html>