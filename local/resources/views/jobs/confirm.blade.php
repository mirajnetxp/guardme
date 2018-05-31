<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')

    <script>
        $(document).ready(function(){
            sessionStorage.clear();

        });
    </script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0';
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);

  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };

  return t;
}(document, "script", "twitter-wjs"));</script>
<!-- fixed navigation bar -->
@include('header')



<section class="clearfix job-bg delete-page">
        <div class="container">
            <div class="breadcrumb-section">
                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <li><a href="{{URL::to('/')}}">Home</a></li>
                    <li><a href="{{URL::route('job.create')}}">Create Job</a></li>
                    <li>Job Confirmation</li>
                </ol><!-- breadcrumb -->                        
                <h2 class="title">Job Confirmation</h2>
            </div><!-- banner -->

            <div class="close-account text-center">
                <div class="delete-account section">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @include('shared.message')

                    <h2>Congratulations, your job has been activated successfully.</h2>
                    
                    <a href="{{URL::route('my.jobs')}}" class="btn">My Jobs</a>
                    <a href="{{URL::to('account')}}" class="btn cancle">Profile</a>
                </div>          
            </div>
            <!--share-->
            <div id="social" class="hide">                        
                <div style="top: -5px;" class="fb-share-button" data-href="https://guarddme.com/jobs/apply/{{$job->id}}" data-layout="button_count" data-size="small" data-mobile-iframe="true">
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a>
                </div>
                <a class="twitter-share-button"
                    href="https://twitter.com/intent/tweet?text={{$job->title}}">
                Tweet</a>
                <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                <script type="IN/Share" data-url="https://guarddme.com/jobs/find"></script>
                <a href="https://plus.google.com/share?url=guarddme.com" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img src="https://www.gstatic.com/images/icons/gplus-32.png" alt="Share on Google+"/></a>
            </div>
        </div>
</section>



@include('footer')

</body>
</html>