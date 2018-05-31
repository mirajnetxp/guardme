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
            <!-- AddToAny BEGIN -->
            <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                <span style="float: left;font-weight: 800;font-size: 22px">Share this ad&nbsp;&nbsp;&nbsp;</span>
                <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                <a class="a2a_button_facebook"></a>
                <a class="a2a_button_twitter"></a>
                <a class="a2a_button_google_plus"></a>
                <a class="a2a_button_linkedin"></a>
            </div>
            <!-- AddToAny END -->
        </div>
</section>



@include('footer')
<script async src="https://static.addtoany.com/menu/page.js"></script>
</body>
</html>