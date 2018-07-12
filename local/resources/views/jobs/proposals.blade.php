@extends('layouts.dashboard-template')
  


@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Applications</li>
        </ol>                       
        <h2 class="title">
           My Applications</h2>
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

        @include('shared.message')
        <div class="row">
            <form method="get" action="{{ url('/jobs/proposal') }}" id="filters">
                <div class="col-sm-12">
                    <label class="col-sm-1">Transaction Date:</label>
                    <div class="col-sm-2">
                        <input type="text" class="start_date date-picker form-control" name="start_date" placeholder="Start Date"  value="{{old('start_date')}}">
                        <span class="text-danger error-span"></span>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="end_date date-picker form-control" name="end_date" placeholder="End Date" value="{{old('end_date')}}">
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
    @if(count($proposals) > 0)
    @foreach($proposals as $application)
        <div class="job-ad-item">
            <div class="item-info">
                <div class="item-image-box">
                    <div class="item-image">
                        
                        <a href="{{ route('my.application.view',['id'=>$application->id,'job_id'=>$application->job_id]) }}" ><img align="center" class="img-responsive" src="{{ URL::to("/")}}/images/img-placeholder.png" alt="{{$application->job_title}}"/></a>
                       
                            

                        
                    </div><!-- item-image -->
                </div>
                    
                <div class="ad-info">
                    <span><a href="{{ route('my.application.view',['id'=>$application->id,'job_id'=>$application->job_id]) }}" class="title">
                                        {{$application->job_title}} @ {{$application->shop_name}}
                                    </a> </span>
                    <div class="ad-meta">
                       
                        <ul>
                            <li>Is hired: @if($application->is_hired)
                                            <i class="fa fa-check-circle-o ico-30 green"></i>
                                        @else
                                            <i class="fa fa-times-circle-o ico-30 red"></i> 
                                        @endif
                            </li>

                            <li>
                                Applied Date: {{date('M d, Y',strtotime($application->applied_date))}}
                            </li>
                            
                        </ul>
                    </div>

                </div><!-- ad-info -->

                <div class="pull-right top-30">
                    <a href="{{ route('my.application.view',['id'=>$application->id,'job_id'=>$application->job_id]) }}"><button class="btn btn-success">View</button></a>
                </div>
            </div><!-- item-info -->
        </div>

    @endforeach
    @endif
    </div>
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
} );
</script>
@endsection