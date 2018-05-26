<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
   
</head>
<body>

<!-- fixed navigation bar -->
@include('header')

    <section class="job-bg page job-details-page">
        <div class="container">
            <div class="breadcrumb-section">
                <ol class="breadcrumb">
                     <li><a href="{{URL::to('/')}}">Home</a></li>
                    
                    <li>{{$job->title}}</li>
                </ol><!-- breadcrumb -->                        
                <h2 class="title">{{$job->title}}</h2>
            </div>
            <div class="job-details">
                 <div class="profile section clearfix">
                    <div class="row">
                        <div class="alert alert-danger error-message hide" role="alert">

                        </div>
                        <div class="alert alert-success success-message hide" role="alert">

                        </div>
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
                                        <label class="pull-right">Is Hired: 
                                            @if($application->is_hired)
                                                <i class="fa fa-check-circle-o ico-30 green"></i>
                                            @else
                                                <i class="fa fa-times-circle-o ico-30 red"></i> 
                                            @endif
                                        </label>
                              
                                    </p>
                                </address>
                            </div>
                            <div class="clearfix"></div>
                            <label class="pull-right">Completion Status:
                                @if($application->completion_status == 1)
                                    <i class="fa fa-check-circle-o ico-30 green"></i>
                                @elseif ($application->completion_status == 2)
                                    <i class="fa fa-times-circle-o ico-30 red"></i>
                                @endif
                            </label>
                            @if ($application->completion_status == 0)
                                <button class="btn del cancel-job-button">Cancel Job</button>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="section job-ad-item">
                    <div class="item-info">
                        <div class="item-image-box">
                            <div class="item-image">
                                <img src="{{URL::to('/')}}/images/img-placeholder.png" alt="{{$job->title}}" class="img-responsive">
                            </div><!-- item-image -->
                        </div>

                        <div class="ad-info">
                            <span><span><a href="#" class="title">{{$job->title}}</a></span> @ <a href="#"> {{$job->poster->company->shop_name}}</a></span>
                            <div class="ad-meta">
                                <ul>
                                    <li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i>@if($job->city_town){{$job->city_town}},@endif {{$job->country}}</a></li>
                                    <!-- <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i>Full Time</a></li> -->
                                    <li><i class="fa fa-money" aria-hidden="true"></i>&pound;{{$job->per_hour_rate}}</li>
                                    <li><a href="#"><i class="fa fa-tags" aria-hidden="true"></i>{{$job->industory->name}}</a></li>
                                    <li><i class="fa fa-hourglass-start" aria-hidden="true"></i>Posted on : {{date('M d, Y',strtotime($job->created_at))}}</li>
                                </ul>
                            </div><!-- ad-meta -->                                  
                        </div><!-- ad-info -->
                    </div><!-- item-info -->
                    
                     <div class="description-info">
                                    <h3>Description</h3>
                                    <p>{{$job->description}}</p>
                                </div>

                </div>
            </div>
        </div>
    </section>
            

  
      @include('footer')
<script>
    $(document).ready(function(){
        $(".cancel-job-button").on("click", function(){
            $.ajax({
                url: "{{ route('api.cancel.job', ['application_id' => $application->id]) }}",
                type: 'POST',
                success: function(data) {
                    $(this).hide();
                    $(".success-message").html(data[0]);
                    $(".success-message").removeClass('hide');
                    setTimeout(function(){
                        location.reload();
                    }, 4000);
                },
                error: function (data) {
                    var errorText = data.responseJSON[0];
                    $(".error-message").html(errorText);
                    $(".error-message").removeClass('hide');
                }
            })
        });
    });
</script>
</body>
</html>