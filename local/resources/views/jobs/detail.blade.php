<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
    <style type="text/css">
        .job-ad-item .btn.btn-primary{
            margin-right: 0px;
        }
    </style>
   <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyC2C2Tp8wIjckpXAeweMhL7nOGes0Dpv2w"></script>
   <script type="text/javascript">
       <?php if( count($user_address) > 0 ) { ?>
        var markers = [
            {
                "title": '{{ $job->title }}',
                "lat": '{{ $job->latitude }}',
                "lng": '{{ $job->longitude }}',
                "description": '{{ $job->title }}, {{ $job->address_line1 }}, {{ $job->address_line2 }}, {{ $job->city_town }}, {{ $job->country }}'
                }
                ,
                {
                    "title": '{{ @$user_address->name }}',
                    "lat": '{{ @$user_address->address->latitude }}',
                    "lng": '{{ @$user_address->address->longitude }}',
                    "description": '{{ @$user_address->name }}, {{ @$user_address->address->line1 }}, {{ @$user_address->address->line2 }}, {{ @$user_address->address->line3 }}'
                }
            ];
       <?php } else { ?>
        var markers = [
            {
                "title": '{{ $job->title }}',
                "lat": '{{ $job->latitude }}',
                "lng": '{{ $job->longitude }}',
                "description": '{{ $job->title }}, {{ $job->address_line1 }}, {{ $job->address_line2 }}, {{ $job->city_town }}, {{ $job->country }}'
                }
            ];
       <?php } ?>
    window.onload = function () {
        var mapOptions = {
            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            lat_lng.push(myLatlng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title,
                icon: "http://guarddme.com/images/map-icon.png",
            });
            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.description);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
        //***********ROUTING****************//
        //Intialize the Path Array
        var path = new google.maps.MVCArray();
        //Intialize the Direction Service
        var service = new google.maps.DirectionsService();
        //Set the Path Stroke Color
        var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });
        //Loop and Draw Path Route between the Points on MAP
        for (var i = 0; i < lat_lng.length; i++) {
            if ((i + 1) < lat_lng.length) {
                var src = lat_lng[i];
                var des = lat_lng[i + 1];
                path.push(src);
                poly.setPath(path);
                service.route({
                    origin: src,
                    destination: des,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                }, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                            path.push(result.routes[0].overview_path[i]);
                        }
                    }
                });
            }
        }
    }    
</script>
</head>
<body>
<!-- fixed navigation bar -->
@include('header')
@if(session()->has('doc_not_v'))
    <div class="container-fluid" style="background-color: #e91e63">
        <h5 class="text-center" style="color: #ffffff">{{session()->has('doc_not_v')}}</h5>
    </div>
@endif
    <section class="job-bg page job-details-page">
        <div class="container">
            <div class="breadcrumb-section">
                <ol class="breadcrumb">
                     <li><a href="{{URL::to('/')}}">Home</a></li>
                    <li><a href="{{URL::route('find.jobs')}}">Jobs</a></li>
                    <li>{{$job->title}}</li>
                </ol><!-- breadcrumb -->                        
                <h2 class="title">{{$job->title}}</h2>
            </div>
            
            <div class="job-details">
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
                                    <li><i class="fa fa-hourglass-start" aria-hidden="true"></i>{{date('M d, Y',strtotime($job->created_at))}}</li>
                                </ul>
                            </div><!-- ad-meta -->
                        </div><!-- ad-info -->
                    </div><!-- item-info -->
                    <div class="social-media">
                        <div class="button">
                            <a href="{{URL::route('apply.job', $job->id)}}" class="btn btn-primary"><i class="fa fa-briefcase" aria-hidden="true"></i>Apply For This Job</a>
                            <a href="#" class="btn btn-primary"><i class="fa fa-heart-o" aria-hidden="true"></i>
                                @if($saved_job != null && $saved_job->job_id == $job->id)
                                <span id="saved">Saved</span>
                                @else
                                <span id="saved">Save For Later</span>
                                @endif
                            </a>
                        </div>
                    </div>                  
                </div>
                <div class="job-details-info">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="section job-description">
                                <div class="description-info">
                                    <h1>Description</h1>
                                    <p>{{$job->description}}</p>
                                </div>
                            </div>
                            @if(Auth::check())
                            <div class="section job-description">
                                <div class="description-info">
                                    <h1>Job Location</h1>
                                    <div id="dvMap" style="width:700px; height: 350px;"></div>
                                    <div style="padding-top: 20px;">
                                        <p>Reference: {{$job->id}}</p>
                                        <p>Bank or payment details should not be provided to any employer. GuardME is not responsible for any external transactions. All applications and payments should be made via our website.</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <div class="section job-short-info">
                                <h1>Short Info</h1>
                                <ul>
                                    <li><span class="icon"><i class="fa fa-bolt" aria-hidden="true"></i></span>Posted: {{date('M d, Y',strtotime($job->created_at))}}</li>
                                    <li><span class="icon"><i class="fa fa-user-plus" aria-hidden="true"></i></span> Job poster: <a href="#">{{$job->poster->name}}</a></li>
                                    <li><span class="icon"><i class="fa fa-industry" aria-hidden="true"></i></span>Industry: <a href="#">{{$job->industory->name}}</a></li>
                                    <li><span class="icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>Experience: <a href="#">Entry level</a></li>
                                    
                                </ul>
                            </div>
                            <div class="section company-info">
                                <h1>Company Info</h1>
                                <ul>
                                    <li>Compnay Name:<a href="#">{{$job->poster->company->shop_name}}</a></li>
                                    <li>Address1: {{ $job_gtsaw->address_line1 }}</li>
                                    <li>Address2: {{ $job_gtsaw->address_line2 }}</li>
                                    <li>Address3: {{ $job_gtsaw->address_line3 }}</li>
                                    <li><span class="icon"><i class="fa fa-industry" aria-hidden="true"></i></span>Industry: <a href="#">{{$job->industory->name}}</a></li>
                                </ul>                               
                            </div> 
                            <div class="section company-info">
                                <h1>Job Info</h1>
                                <ul>
                                    <li>Posted On: <a href="#">{{$job_gtsaw->created_at->toDateTimeString()}}</a></li>
                                    <li># freelancer: {{ $job_gtsaw->number_of_freelancers }}</li>
                                    <li># days: {{ $job_gtsaw->monthly_working_days }}</li>
                                    <li># hours: {{ $job_gtsaw->daily_working_hours }}</li>
                                    <li> pay per hour: {{ $job_gtsaw->per_hour_rate }}</li>
                                </ul>                            
                            </div>
                            @php
                             $schedule = Responsive\SecurityJobsSchedule::where('job_id', $job_gtsaw->id)->get();   
                            @endphp
                            <div class="section company-info">
                                <h1>Schedule</h1>
                                <ul>
                                    @foreach($schedule as $s)
                                    <li>Start Date On: {{ $s->start->toDateTimeString() }} </li>
                                    <li>End Date On: {{ $s->end->toDateTimeString() }}</li>
                                    @endforeach
                                </ul>                            
                            </div>                               
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@include('footer')
<script type="text/javascript">
    var job = {!! json_encode($job) !!};
    $('#saved').click(function(e){
        console.log(job.id);
        if($('#saved').text() == "Saved"){
            $.ajax({
                url: "{{url('/jobs/remove/')}}/" + job.id,
                type: "GET",
                success: function(data){
                    console.log("removed");
                    $('#saved').text("Save For Later".trim());
                }
            });
        }
        else{
            $.ajax({
                url: "{{url('/jobs/save/')}}/"+ job.id,
                type: "GET",
                success: function(data){
                    console.log("saved");
                    $('#saved').text("Saved".trim());
                }
            });
        }
    });
</script>
</body>
</html>