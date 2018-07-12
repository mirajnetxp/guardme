@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="{{URL::to('/')}}">Home</a></li>
            <li><a href="{{URL::to('/jobs/my')}}">Jobs</a></li>
            <li>{{$job->title}}</li>
        </ol>
        <h2 class="title">
            {{$job->title}}</h2>
    </div>
@endsection
@section('content')

    <div class="section job-ad-item">
        <a class="btn btn-secondary" href="{{ URL::to('/jobs/my') }}">&larr; Back to Jobs</a>
    </div>

    <div class="job-details-info">

        <div class="section job-description job-ad-item">

            <div class="item-info bottom-50">
                <div class="item-image-box">
                    <div class="item-image">
                        <img src="{{URL::to('/')}}/images/img-placeholder.png" alt="{{$job->title}}"
                             class="img-responsive">
                    </div><!-- item-image -->
                </div>

                <div class="ad-info">

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
                    <span><a href="#" class="title">{{$job->title}}</a></span>


                    <!-- ad-meta -->
                </div><!-- ad-info -->
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="description-info">
                        <h1>Description</h1>
                        <p>{{$job->description}}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">

                    <button class="btn btn-success pull-right" data-toggle="modal" data-target="#view-incident">
                        Incident Report
                    </button>
                    <!-- Modal -->
                    <div style="margin-top: 20px" class="modal fade" id="view-incident" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span
                                                aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">Incident Reports</h4>
                                </div>
                                <div class="modal-body">
                                    @if(count($incidents)==0)
                                        <h2>No incident report available</h2>
                                    @else
                                        <table class="table">
                                            <tr>
                                                <td>Incident Message</td>
                                                <td>Freelancer</td>
                                                <td>Incident Time</td>
                                            </tr>
                                            @foreach($incidents as $incident)
                                                <tr>
                                                    <td>{{$incident->incident_report}}</td>
                                                    <td>{{$incident->name}}</td>
                                                    <td>{{date('m/d/ Y h:i A',strtotime($incident->created_at))}}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="section job-description">
            <div class="description-info">
                <h1>Job Applications</h1>

                @foreach($applications as $application)

                    <div class="job-ad-item">
                        <div class="item-info">
                            <div class="item-image-box">
                                <div class="item-image">
									<?php
									$url = URL::to( '/' );
									$photo_path = '/local/images/userphoto/' . $application->photo;
									if($application->photo != ""){?>
                                    <a href="{{ route('view.application',['id'=>$application->id,'u_id'=>$application->u_id]) }}"><img
                                                src="<?php echo $url . $photo_path;?>" class="img-responsive"></a>
									<?php } else { ?>
                                    <a href="{{ route('view.application',['id'=>$application->id,'u_id'=>$application->u_id]) }}"><img
                                                align="center" class="img-responsive"
                                                src="<?php echo $url . '/local/images/nophoto.jpg';?>"
                                                alt="Profile Photo"/></a>
									<?php } ?>


                                </div><!-- item-image -->
                            </div>

                            <div class="ad-info">
                                    <span><a href="{{ route('view.application',['id'=>$application->id,'u_id'=>$application->u_id]) }}"
                                             class="title">
                                                        {{$application->user_name}}
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
                                @php@
                                    $btn_class = "btn-info";
                                    $btn_text = "Favourite it";
                                @endphp
                                @if(!empty($fav_freelancers) && !empty($fav_freelancers[$application->applied_by]))
                                    @php@
                                        $btn_class = "btn-danger";
                                        $btn_text = "Un-favourite it";
                                    @endphp
                                @endif
                                <button class="btn toggle-favourite {{ $btn_class }}"
                                        data-action="{{ route('api.toggle.favourite.freelancer', ['freelancer_id' => $application->applied_by]) }}">{{ $btn_text }}</button>
                                <a href="{{ route('view.application',['id'=>$application->id,'u_id'=>$application->u_id]) }}">
                                    <button class="btn btn-success">View</button>
                                </a>
                            </div>
                        </div><!-- item-info -->
                    </div>


                @endforeach
            </div>
        </div>

    </div>

@endsection
@section('script')

@endsection