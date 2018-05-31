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
        </div>
</section>



@include('footer')

</body>
</html>