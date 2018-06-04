<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
   
</head>
<body>

<!-- fixed navigation bar -->
@include('header')
@if(session()->has('doc_not_v'))
    <div class="container-fluid" style="background-color: #e91e63">
        <h5 class="text-center" style="color: #ffffff">{{session()->get('doc_not_v')}}</h5>
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
                <h2 class="title">Submit Your Application</h2>
            </div>
         <div class="job-details">
                <div class="section job-ad-item">
                    <div class="item-info">
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
                        <div class="alert alert-danger hide" role="alert">

                        </div>
                        <div class="alert alert-success hide" role="alert">

                        </div>
                        @include('shared.message')
                        <div class="item-image-box">
                            <div class="item-image">
                                <img src="{{URL::to('/')}}/images/img-placeholder.png" alt="{{$job->title}}" class="img-responsive">
                            </div><!-- item-image -->
                        </div>

                        <div class="ad-info">
                            <span><span><a href="#" class="title">{{$job->title}}</a></span> @ <a href="#"> {{$job->poster->company->shop_name}}</a></span>
                            <div class="ad-meta">
                                <ul>
                                    <li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i>@if($job->cit_town){{$job->cit_town}},@endif {{$job->country}}</a></li>
                                    <!-- <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i>Full Time</a></li> -->
                                    <li><i class="fa fa-money" aria-hidden="true"></i>${{$job->per_hour_rate}}</li>
                                    <li><a href="#"><i class="fa fa-tags" aria-hidden="true"></i>{{$job->industory->name}}</a></li>
                                    <li><i class="fa fa-hourglass-start" aria-hidden="true"></i>Posted on : {{date('M d, Y',strtotime($job->created_at))}}</li>
                                </ul>
                            </div><!-- ad-meta -->                                  
                        </div><!-- ad-info -->
                    </div><!-- item-info -->
                    <div class="social-media">
                        <div class="button">
                            <a href="{{URL::route('apply.job', $job->id)}}" class="btn btn-primary"><i class="fa fa-briefcase" aria-hidden="true"></i>Apply For This Job</a>
                            <a href="#" class="btn btn-primary bookmark"><i class="fa fa-bookmark-o" aria-hidden="true"></i>Bookmark</a>
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
                            <div class="section job-description">
                               <div class="row">
                                <form action="{{ route('api.apply.job', ['id' => $job->id ]) }}" id="apply_on_job" method="post">
                                    <div class="form-group">
                                        <label for="">Application Description</label>
                                        <textarea class="form-control application_description" name="application_description" rows="10" autofocus=""></textarea>
                                        <span class="error-span text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-info">
                                    </div>

                                </form>
                            </div>


                            </div>
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
                                    <li>Compnay Name: <a href="#">{{$job->poster->company->shop_name}}</a></li>
                                    <li>Address: @if($job->poster->company->city){{$job->poster->company->city}}@endif
                                        @if($job->poster->company->state){{', '.$job->poster->company->state}}@endif
                                        @if($job->poster->company->country){{', '.$job->poster->company->country}}@endif</li>
                                </ul>
                                <ul class="share-social">
                                    <li><a href="#"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
                                </ul>                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </section>
    

@include('footer')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="{{ asset('js/share.js') }}"></script>
<script>
    $(document).ready(function(){
        $("form#apply_on_job").on("submit", function(e){
            formErrors = new Errors();
            e.preventDefault();
            $.ajax({
               url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    $('.alert-success').text("Your application has been received");
                    $('.alert-success').removeClass('hide');
                    $("html, body").animate({ scrollTop: $('.alert') }, 1000);
                },
                error: function(data) {
                    var statusCode = data.status;
                    var errors = data.responseJSON;
                    if (statusCode == 422) {
                        formErrors.record(errors);
                        formErrors.load();
                    } else {
                        formErrors.record(errors);
                        formErrors.load();
                        var errorText = '';
                        if (typeof data.responseJSON[0] != 'undefined') {
                            $('.alert-danger').text(data.responseJSON[0]);
                            $('.alert-danger').removeClass('hide');
                        }
                        $("html, body").animate({ scrollTop: $('.alert') }, 1000);
                    }

                }
            });
        });
    });
</script>

</body>
</html>