<!DOCTYPE html>
<html lang="en">
<head>

    

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
    <!-- slider -->
    

	
    
	
	
	<section class=" job-bg ad-details-page">
		<div class="container">

			<div class="breadcrumb-section">
				<ol class="breadcrumb">
					<li><a href="{{URL::to('/')}}">Home</a></li>
					<li><?php echo $contact[0]->page_title;?></li>
				</ol>						
				<h2 class="title"><?php echo $contact[0]->page_title;?></h2>
			</div>

			<div class="job-postdetails post-resume">
				<div class="row">	
					<div class="user-account job-user-account">
						<h2>Contact Us</h2>
					<div class="col-md-6 clearfix">

						<form method="POST" action="{{ route('contact') }}" id="formID" enctype="multipart/form-data">
                        {{ csrf_field() }}
	
						<div class="form-group">
							<input type="text" value=""  class="form-control validate[required] text-input" id="name" name="name" placeholder="Name">
						</div>
						
						<div class="form-group">
							<input type="text" value=""  class="form-control validate[required,custom[email]] text-input" id="email" name="email" placeholder="Email">				
						</div>
						<div class="form-group">
							 <input type="text" value=""  class="form-control validate[required] text-input" id="phone_no" name="phone_no" placeholder="Phone No">
						</div>
						<div class="form-group">
							 <input type="text" value=""   class="form-control validate[required] text-input" id="msg" name="msg" placeholder="Message">
						</div>
			
		  			<button type="submit" class="btn" value="Send">Send</button>
         
       				 </form>
						
					</div>
<div class="clearfix visible-xs">&nbsp;</div>
					<div class="col-md-6">
						<?php
						echo trim($contact[0]->page_desc,"'");
						?>
					</div>
				</div>
				</div>
			</div>

		
		</div>
	</section>
	
      @include('footer')
	  <?php if(session()->has('message')){?>
    <script type="text/javascript">
        alert("<?php echo session()->get('message');?>");
		</script>
    </div>
	 <?php } ?>
</body>
</html>