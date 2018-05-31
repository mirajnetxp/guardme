 
<!DOCTYPE html>
<html lang="en">
<head>

    

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
					<li><a href="{{URL::to('personnel-search')}}">Security Personnel</a></li>
					<li>Profile</li>
				</ol><!-- breadcrumb -->						
				<h2 class="title">@php $flag = false; @endphp
					    	@if($person->firstname!='')
					
						@php  $flag = false;  @endphp

						@foreach($person->applications as $row)
							@if(auth()->user()->id == $row->apply_to &&  $row->is_hired == '1')
								@php 
									$flag = true; 
									break;
								@endphp
								
							@endif
						@endforeach

						@if($flag)
							{{$person->firstname.' '.$person->lastname.auth()->user()->id.' a' }}
						@else
							{{$person->firstname.' ********'}}
						@endif
					   
					    	@else
					    		{{'********'}}
					    	@endif
					    </h2>
			</div>
			<div class="resume-content">
				<div class="profile section clearfix">
					<div class="profile-logo">
						<?php $photo_path ='/local/images/userphoto/'.$person->photo;?>
						@if($person->photo!="")
					    	<img class="img-responsive" src="<?php echo $url.$photo_path;?>" alt="Image">
					    @else
							<img class="img-responsive" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Image">
					    @endif
					</div>
					<div class="profile-info">
					   <h1>@php $flag = false; @endphp
					    	@if($person->firstname!='')
					
						@php  $flag = false;  @endphp

						@foreach($person->applications as $row)
							@if(auth()->user()->id == $row->apply_to &&  $row->is_hired == '1')
								@php 
									$flag = true; 
									break;
								@endphp
								
							@endif
						@endforeach

						@if($flag)
							{{$person->firstname.' '.$person->lastname.auth()->user()->id.' a' }}
						@else
							{{$person->firstname.' ********'}}
						@endif
					   
					    	@else
					    		{{'********'}}
					    	@endif
					    </h1>
					    <address>
					        <p>@if($person->person_address)
					        		City: {{$person->person_address->citytown}} <br>
								@endif
								@if($person->sec_work_category)
									Category: {{$person->sec_work_category->name}} 
								@endif
							</p>
					    </address>
					</div>					
				</div>

				<div class="career-objective section">
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
</body>
</html>
