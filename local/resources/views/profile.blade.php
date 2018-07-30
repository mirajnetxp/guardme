<!DOCTYPE html>
<html lang="en">
<head>


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
                <li><a href="{{URL::to('personnel-search')}}">Security Personnel</a></li>
                <li>Profile</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">@php $flag = false; @endphp
                @if($person->firstname!='')

                    @php  $flag = false;  @endphp

                    @foreach($person->applications as $row)
                        @if(auth()->user()->id == $row->apply_to &&  $row->is_hired == '1')
                            @php
                                $flag = true;
                                break;
                            @endphp

                        @endif
                    @endforeach

                    @if($flag)
                        {{$person->firstname.' '.$person->lastname.auth()->user()->id.' a' }}
                    @else
                        {{$person->firstname.' ********'}}
                    @endif

                @else
                    {{'********'}}
                @endif
            </h2>
        </div>
        <div class="resume-content">
            <div class="row profile section clearfix">
                <div class="col-lg-6 col-md-6 ">
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
                        <h1>@php $flag = false; @endphp
                            @if($person->firstname!='')

                                @php  $flag = false;  @endphp

                                @foreach($person->applications as $row)
                                    @if(auth()->user()->id == $row->apply_to &&  $row->is_hired == '1')
                                        @php
                                            $flag = true;
                                            break;
                                        @endphp

                                    @endif
                                @endforeach

                                @if($flag)
                                    {{$person->firstname.' '.$person->lastname.auth()->user()->id.' a' }}
                                @else
                                    {{$person->firstname.' ********'}}
                                @endif

                            @else
                                {{'********'}}
                            @endif
                        </h1>
                        <address>
                            <p>@if($person->person_address)
                                    City: {{$person->person_address->citytown}}
                                @endif
                                <br>
                                GPS: {{($person->freelancerSettings->gps==1)?'Active':'Inactive'}}
                                @if($person->sec_work_category)
                                    Category: {{$person->sec_work_category->name}}
                                @endif
                            </p>
                        </address>
                    </div>
                    @if(!empty($work_history['aggregate_rating']))
                        <p><span class="stars" data-rating="{{ $work_history['aggregate_rating'] }}"
                                 data-num-stars="5"></span>
                            <strong>{{  $work_history['aggregate_rating'] }}</strong></p>
                    @endif

                </div>
                <div class="col-lg-6 col-md-6">
                @if(auth()->user()->admin==0)
                    <!-- Button trigger modal -->
                        <button class="btn btn-info pull-right" data-toggle="modal" data-target="#hireMe">
                            Hire Me
                        </button>

                        <!-- Modal -->
                        <div style="margin-top: 30px" class="modal fade" id="hireMe" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span
                                                    aria-hidden="true">&times;</span><span
                                                    class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Open Jobs</h4>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="list-group">
                                            @if($openJobs)
                                                @foreach($openJobs as $openJob)
                                                    <li class="list-group-item">
                                                        <form class="hireBy">
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="freelancer_id"
                                                                   value="{{$person->id}}">
                                                            <input type="hidden" name="job_id" value="{{$openJob->id}}">
                                                            <input class="pull-right  btn-small" style="background: #4CAF50;
                                                                                                        color: #fff;
                                                                                                        border: 1px solid #4caf50;
                                                                                                        border-radius: 4px;
                                                                                                        box-shadow: 0 0 1px 0px black;
                                                                                                        text-shadow: 0 0 0px black;"
                                                                   type="submit" value="Hire">
                                                        </form>
                                                        {{$openJob->title}}
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="career-objective section">
                <div class="icons">
                    <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                </div>
                <div class="career-info profile-info">
                    <h3>Security Licence</h3>

                    <address>
                        <p>Licence Type: SIA <br>
                            @if($person->sia_licence !='')Valid:
                            <i class="fa fa-check-circle-o ico-30 green"></i>
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
            <div class="work-history section">
                <div class="icons">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                </div>
                <div class="work-info">
                    <h3>Feedback</h3>
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
            </div><!-- career-objective -->
        </div>
    </div>

</section>

@include('extre.tearms ans condtion --hair')
@include('footer')
<script>
    /*read only star rating to display only*/

    $(document).ready(function () {
        $('.hireBy').submit(function () {
            event.preventDefault();
            window.hirebyclick = this;
            $('#jobATACM').modal('show')
        })
        $('#termsA').click(function () {

            var form_data = $(hirebyclick).serialize();
            $.ajax({
                url: "{{route('job.hair.by')}}",
                method: "POST",
                data: form_data,
                dataType: 'json',
                success: function (d) {
                    $('#jobATACM').modal('hide')
                    alert(d)
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('#jobATACM').modal('hide')
                    console.log(xhr.responseText);
                    alert(xhr.responseText)
                }
            });
        })



    });

    $.fn.stars = function () {
        return $(this).each(function () {

            var rating = $(this).data("rating");
            if (rating == '5.00') {
                rating = 5;
            }
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
