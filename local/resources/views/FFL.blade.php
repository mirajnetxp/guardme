<!DOCTYPE html>
<html lang="en">
<head>


    @include('style')


</head>
<body>


<!-- fixed navigation bar -->

@include('header')

<!-- slider -->


<section class=" job-bg ad-details-page">
    <div class="container">
        <div class="breadcrumb-section">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li>Favourite Freelancers</li>
            </ol>
            <h2 class="title">Favourite Freelancers</h2>
        </div>


        <!-- <div class="adpost-details post-resume"> -->
        <div class="section trending-ads latest-jobs-ads">
            <h4>Favourite Freelancers</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

            @include('shared.message')
            @foreach($FFL as $person)
                <div class="job-ad-item">
                    <div class="item-info">
                        <div class="item-image-box">
                            <div class="item-image">
								<?php

								$photo_path = '/local/images/userphoto/' . $person->photo;
								if($person->photo != ""){?>
                                <a href="{{ route('person-profile',$person->id) }}"><img
                                            src="{{asset($photo_path)}}" class="img-responsive"></a>
								<?php } else { ?>
                                <a href="{{ route('person-profile',$person->id) }}">
                                    <img align="center"
                                         class="img-responsive"
                                         src="{{asset('/local/images/nophoto.jpg')}}"
                                         alt="Profile Photo"/></a>
								<?php } ?>


                            </div><!-- item-image -->
                        </div>

                        <div class="ad-info">
					                <span>
                                        <a href="{{ route('person-profile',$person->id) }}" class="title">
                                            {{$person->firstname.' '.$person->lastname }}
                                            @if(isset($rating_array[$person->id]))
                                                <strong style="float: right; font-size: 14px;">{{  $rating_array[$person->id] }}</strong>
                                                <span class="stars"
                                                      data-rating="{{ $rating_array[$person->id] }}"
                                                      style="float: right; margin: 0 5px"
                                                      data-num-stars="5"></span>
                                            @endif

						                 </a>
                                    </span>
                            <div class="ad-meta">
                            </div><!-- ad-meta -->
                        </div><!-- ad-info -->
                        <!-- <div class="close-icon">
                            <i class="fa fa-window-close" aria-hidden="true"></i>
                        </div> -->
                    </div><!-- item-info -->
                </div>
            @endforeach
        </div>
    </div>
</section>


@include('footer')
</body>
</html>