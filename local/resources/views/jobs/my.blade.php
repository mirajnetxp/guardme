@extends('layouts.dashboard-template')
  


@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Jobs</li>
        </ol>                       
        <h2 class="title">
           My Jobs</h2>
    </div>
@endsection
@section('content')

    <div class="section trending-ads latest-jobs-ads">
        <h4>My Jobs</h4>

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
        <div class="alert alert-danger error-message hide" role="alert">

        </div>
        <div class="alert alert-success success-message hide" role="alert">

        </div>
        @include('shared.message')
        <div class="row">
            <form method="get" action="{{ url('/jobs/myfilter') }}" id="filters">
                <div class="col-sm-12">
                    <label class="col-sm-1">Transaction Date:</label>
                    <div class="col-sm-2">
                        <input type="text" class="start_date date-picker form-control" name="start_date" placeholder="Start Date"  value="{{old('start_date')}}">
                        <span class="text-danger error-span"></span>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="end_date date-picker form-control" name="end_date" placeholder="End Date"  value="{{old('end_date')}}">
                        <span class="text-danger error-span"></span>
                    </div>
                    <div class="form-group col-sm-3">
                        <select name="kind" id="kind" class="trackprogress form-control text-input">
                            <option value="1">All</option>
                            <option value="2">Open</option>       
                            <option value="3">Closed</option>
                        </select>                               
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" value="GO" class="btn btn-primary">GO</button>
                    </div>
                </div>
            </form>
        </div>
        @if(count($new_jobs) > 0)
        @foreach($new_jobs as $job)
            <div class="job-ad-item">
                <div class="item-info">
                    <div class="item-image-box">
                        <div class="item-image">
                            <a href="{{ route('my.job.applications', ['id' => $job->id]) }}"><img align="center" class="img-responsive" src="{{ URL::to("/")}}/images/img-placeholder.png" alt="{{$job->title}}"/></a>
                        </div><!-- item-image -->
                    </div>

                    <div class="ad-info">
                        <span><a href="{{ route('my.job.applications', ['id' => $job->id]) }}" class="title">{{$job->title}}</a></span>
                        <div class="ad-meta">
                            <ul>
                                <li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i>@if($job->city_town){{$job->city_town}}, @endif {{$job->country}}</a></li>
                              
                                <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i>&pound;{{$job->per_hour_rate}}</a></li>

                                <li><a href="#"><i class="fa fa-tags" aria-hidden="true"></i>{{$job->industory['name']}}</a></li>
                                <li><a href="#">Applications: <b style="color:#00a651">{{{$arr_count[$job->id]['appcount']}}}</b></a></li>
                                <li><a href="#">Hires: <b style="color:#00a651">{{{$arr_count[$job->id]['hiredcount']}}}</b></a></li>
                            </ul>
                        </div><!-- ad-meta -->                                  
                    </div><!-- ad-info -->
                    <div class="close-icon">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                    </div>
                    <div class="pause-unpause-block pull-right">
                        @if ($arr_count[$job->id]['hiredcount'] == 0)
                            @if ($job->is_pause == 0)
                               <?php
                                $pause = '';
                                $restart = 'hide'; ?>
                            @else
                                <?php
                                $pause = 'hide';
                                $restart = '';
                                ?>
                            @endif
                            <button class="btn btn-success restart-job-button {{ $restart }}" data-pause_url="{{ route('api.restart.job', $job->id) }}">Restart Job</button>
                            <button class="btn btn-danger pause-job-button {{ $pause }}" data-pause_url="{{ route('api.pause.job', $job->id) }}">Pause Job</button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div><!-- item-info -->
            </div>
            @endforeach
            @endif
    </div>

                <?php /*<table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($new_jobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->description }}</td>
                                <td><a href="{{ route('my.job.applications', ['id' => $job->id]) }}"><button class="btn btn-success">View Applications</button></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    */?>

@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
	var date = new Date();
	
	$('.date-picker').datepicker({
	    format: 'mm/dd/yyyy',
	    autoclose: true,
	});
    $(".pause-job-button").on("click", function(){
        var pause_url = $(this).attr('data-pause_url');
        var elem = $(this);
       $.ajax({
            url: pause_url,
            type: 'POST',
            success: function(data){
                $(".success-message").text(data[0]);
                $(".success-message").removeClass("hide");
                elem.addClass("hide");
                elem.siblings('.restart-job-button').removeClass('hide');
            },
            error: function(data){
                $(".error-message").text(data.responseJSON[0]);
                $(".error-message").removeClass("hide");
            }
        })
    });

    $(".restart-job-button").on("click", function(){
        var pause_url = $(this).attr('data-pause_url');
        var elem = $(this);
        $.ajax({
            url: pause_url,
            type: 'POST',
            success: function(data){
                $(".success-message").text(data[0]);
                $(".success-message").removeClass("hide");
                elem.addClass("hide");
                elem.siblings('.pause-job-button').removeClass('hide');
            },
            error: function(data){
                $(".error-message").text(data.responseJSON[0]);
                $(".error-message").removeClass("hide");
            }
        })
    });
} );
</script>
@endsection