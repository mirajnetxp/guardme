<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
    
</head>

<body>

    <?php $url = URL::to("/"); ?>

    <!-- fixed navigation bar -->
    @include('header')

    <section class="job-bg page ad-profile-page">
        <div class="container">
            <div class="breadcrumb-section">
                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <li><a href="{{URL::to('/')}}">Home</a></li>
                    
                    <li>Profile & Application Details</li>
                </ol><!-- breadcrumb -->                        
                <h2 class="title">@if($person->firstname!='')
                                {{$person->firstname.' '.$person->lastname}}
                            @else
                                {{$person->name}}
                            @endif Profile & Application Details</h2>
            </div>
            <div class="resume-content">

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
                <div class="profile section clearfix">
                    <div class="row">
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
                                        @if (!$application->is_hired)
                                        <button class="btn btn-info pull-right mark-as-hired">Award Job</button>
                                            <button class="btn del pull-right right-10">Decline</button>
                                        @else
                                            <button class="mark-as-complete btn pull-right right-10">Mark as complete</button>
                                            
                                        @endif


                                    </p>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="career-objective section">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="profile-logo">
                                <?php $photo_path ='/local/images/userphoto/'.$person->photo;?>
                                @if($person->photo!="")
                                    <img class="img-responsive" src="<?php echo $url.$photo_path;?>" alt="Image">
                                @else
                                    <img class="img-responsive" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Image">
                                @endif
                            </div>
                            <div class="profile-info">
                                <h1>
                                    @if($person->firstname!='')
                                        {{$person->firstname.' '.$person->lastname}}
                                    @else
                                        {{$person->name}}
                                    @endif
                                </h1>
                                <address>
                                    <p>@if($person->person_address)
                                            City: {{$person->person_address->citytown}} <br>
                                        @endif
                                        @if($person->sec_work_category)
                                            Category: {{$person->sec_work_category->name}} 
                                        @endif
                                </address>
                            </div>
                        </div>
                        <div class="col-md-4 top-25">
                            <div class="icons">
                                <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                            </div>   
                            <div class="career-info profile-info">
                                <h3>Security Licence</h3>

                                <address>
                                    <p>Licence Type: SIA <br> 
                                        Valid: @if($person->sia_licence !='')
                                                    <i class="fa fa-check-circle-o ico-30 green"></i>
                                                @else
                                                    <i class="fa fa-times-circle-o ico-30 red"></i> 
                                                @endif
                                                    <br> 
                                        Expiry Date:@if($person->sia_expirydate !='')
                                                        {{$person->sia_expirydate}}
                                                    @else
                                                        {{'NA'}}
                                                    @endif
                                    </p>
                                </address>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="work-history section">
                    <div class="icons">
                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                    </div>   
                    <div class="work-info">
                        <h3>Work History</h3>
                        <ul>
                            <li>
                                <h4>work1 @ xyz <span>2012 - Present</span></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </li>
                            <li>
                                <h4>work2 @ XYZ <span>2011 - 2012</span></h4>
                                <p>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </li>
                            <li>
                                <h4>work3 @ xyz <span>2005 - 2011</span></h4>
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>
                            </li>
                            <li>
                                <h4>wok4 @ xyz <span>2004 - 2005</span></h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </li>
                            <li>
                                <h4>work5 @ xyz <span>2002 - 2004</span></h4>
                                <p>Incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            </li>
                        </ul>
                    </div>                                 
                </div><!-- work-history -->
                <div class="declaration section">
                    <div class="icons">
                        <i class="fa fa-comments-o" aria-hidden="true"></i>
                    </div>   
                    <div class="declaration-info">
                        <h3>Feedback</h3>
                        <p><span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni। dolores eos qui ratione voluptatem sequi nesciunt.</p>
                    </div>                                 
                </div><!-- career-objective --> 
            </div>
        </div>
        
    </section>
    

      @include('footer')


<script>
    $(document).ready(function(){
        $(".mark-as-hired").on("click", function(){
            var route = "{{ route('api.mark.hired', ['id' => $application->id]) }}";
            $.ajax({
                url: route,
                type: 'POST',
                success: function(data) {
                    $('.mark-as-hired').fadeOut('slow');
                    $('.alert-success').text(data[0]);
                    $('.alert-success').removeClass('hide');
                },
                error: function(data) {
                    $('.alert-danger').text(data.responseJSON[0]);
                    $('.alert-danger').removeClass('hide');
                }
            })
        });
        $(".mark-as-complete").on("click", function() {
           $.ajax({
               url: "{{ route('api.mark.application.complete', ['application_id' => $application->id]) }}",
               type: "POST",
               success: function (data) {
                   var nextUrl = "{{ route('leave.feedback', ['application_id' => $application->id]) }}";
                   window.location.href = nextUrl;

               }
           })
        });
    });
</script>


</body>
</html>