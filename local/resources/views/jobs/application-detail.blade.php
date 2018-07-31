<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')

</head>

<body>

<?php $url = URL::to( "/" ); ?>

<!-- fixed navigation bar -->
@include('header')

<section class="job-bg page ad-profile-page">
    <div class="container">
        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>

                <li>Profile & Application Details</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">@if($person->firstname!='')
                    {{$person->firstname.' '.$person->lastname}}
                @else
                    {{$person->name}}
                @endif Profile & Application Details</h2>
        </div>
        <div class="resume-content">

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
            <div class="profile section clearfix">
                <div class="row">
                    <div class="col-md-8">
                        <div class="profile-info">
                            <h1>
                                Application Summary
                            </h1>
                            <address>
                                <p>{{$application->description}}
                                </p>
                            </address>
                        </div>
                        <a class="btn btn-secondary" href="{{ URL::to('/jobs/my') }}">&larr; Back to Jobs</a>
                    </div>
                    <div class="col-md-4">
                        <div class="career-info profile-info top-22">

                            <address>
                                <p>
                                    <span class="pull-right">Application date: {{date('d/m/Y',strtotime($application->applied_date))}} </span>
                                </p>
                                <br/>
                                <br/>
                                <p>
                                    @if (!$application->is_hired)
                                        <button class="btn btn-info pull-right mark-as-hired" data-toggle="modal"
                                                data-target="#jobATACM">Award Job
                                        </button>
                                        <button class="btn del pull-right right-10">Decline</button>
                                    @elseif ($application->completion_status == 0)
                                        <button class="mark-as-complete btn pull-right right-10">Mark as complete
                                        </button>
                                        <br>
                                        <br>
                                <form action="{{ route('application.contract', ['id' => $application->id]) }}"
                                      method="get">
                                    <button type="submit" class=" btn pull-right right-10">Download Contract
                                    </button>
                                </form>
                                @endif

                                </p>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            <div class="career-objective section">
                <div class="row">
                    <div class="col-md-8">
                        <div class="profile-logo">
							<?php $photo_path = '/local/images/userphoto/' . $person->photo;?>
                            @if($person->photo!="")
                                <img class="img-responsive" src="<?php echo $url . $photo_path;?>" alt="Image">
                            @else
                                <img class="img-responsive" src="<?php echo $url . '/local/images/nophoto.jpg';?>"
                                     alt="Image">
                            @endif
                        </div>
                        <div class="profile-info">
                            <h1>
                                @if($person->firstname!='')
                                    {{$person->firstname.' '.$person->lastname}}
                                @else
                                    {{$person->name}}
                                @endif
                            </h1>
                            <address>
                                <p>@if($person->person_address)
                                        City: {{$person->person_address->citytown}} <br>
                                    @endif
                                    @if($person->sec_work_category)
                                        Category: {{$person->sec_work_category->name}}
                                @endif
                            </address>
                        </div>
                        @if(!empty($work_history['aggregate_rating']))
                            <p><span class="stars" data-rating="{{ $work_history['aggregate_rating'] }}"
                                     data-num-stars="5"></span>
                                <strong>{{  $work_history['aggregate_rating'] }}</strong></p>
                        @endif
                    </div>
                    <div class="col-md-4 top-25">
                        <div class="icons">
                            <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                        </div>
                        <div class="career-info profile-info">
                            <h3>Security Licence</h3>
                            <address>
                                <p>Licence Type: SIA <br>
                                    Valid: @if($person->sia_licence !='')
                                        <i class="fa fa-check-circle-o ico-30 green"></i>
                                    @else
                                        <i class="fa fa-times-circle-o ico-30 red"></i>
                                    @endif
                                    <br>
                                    Expiry Date:@if($person->sia_expirydate !='')
                                        {{$person->sia_expirydate}}
                                    @else
                                        {{'NA'}}
                                    @endif
                                </p>
                            </address>
                        </div>
                    </div>

                </div>
            </div>
            <div class="work-history section">
                <div class="icons">
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                </div>
                <div class="work-info">
                    <h3>Work History</h3>
                    <ul>
                        @if(!empty($work_history['project_ratings']))
                            @foreach($work_history['project_ratings'] as $item)
                                <li>
                                    <h4>{{ $item['job_title'] }} <span>{{ $item['date_range'] }}</span></h4>
                                    <p><span class="stars" data-rating="{{ $item['star_rating'] }}"
                                             data-num-stars="5"></span> <strong>{{ $item['star_rating'] }}</strong></p>
                                    <p>{{ $item['feedback_message'] }}</p>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div><!-- work-history -->
            <div class="declaration section">
                <div class="icons">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                </div>
                <div class="declaration-info">
                    <h3>Feedback</h3>
                   
                    <p>You have not recieved any feedback yet.</p>
                </div>
            </div><!-- career-objective -->
        </div>
    </div>

</section>


@include('footer')
@include('extre.tearms ans condtion --hair')
{{--@include('footer')--}}


<script>
    $(document).ready(function () {
        $("#termsA").on("click", function () {
            var route = "{{ route('api.mark.hired', ['id' => $application->id]) }}";
            $.ajax({
                url: route,
                type: 'POST',
                success: function (data) {
                    $('#termsA').modal('hide')
                    $('.mark-as-hired').fadeOut('slow');
                    $('.alert-success').text(data[0]);
                    $('.alert-success').removeClass('hide');
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                },
                error: function (data) {
                    $('.alert-danger').text(data.responseJSON[0]);
                    $('.alert-danger').removeClass('hide');
                }
            })
        });

        $(".mark-as-complete").on("click", function () {
            $.ajax({
                url: "{{ route('api.mark.application.complete', ['application_id' => $application->id]) }}",
                type: "POST",
                success: function (data) {
                    var nextUrl = "{{ route('leave.feedback', ['application_id' => $application->id]) }}";
                    window.location.href = nextUrl;

                },
                error: function (data) {
                    //@TODO have to add some validation error
                }
            })
        });



    });
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


</body>
</html>
