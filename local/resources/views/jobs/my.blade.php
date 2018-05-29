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

        @include('shared.message')
        @foreach($my_jobs as $job)
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
                                <li><a href="#"><i class="fa fa-tags" aria-hidden="true"></i>{{$job->industory->name}}</a></li>
                            </ul>
                        </div><!-- ad-meta -->                                  
                    </div><!-- ad-info -->
                    <div class="close-icon">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                    </div>
                </div><!-- item-info -->
            </div>
            @endforeach
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
                        @foreach($my_jobs as $job)
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















