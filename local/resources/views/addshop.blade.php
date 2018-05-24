<!DOCTYPE html>
<html lang="en">
<head>

    

   @include('style')
	




</head>
<body>

    <?php 
	
	$url = URL::to("/"); ?>

    <!-- fixed navigation bar -->
    @include('header')

    <!-- slider -->
    

	
    
	<section class="clearfix job-bg  ad-profile-page">
		<div class="container">
			<div class="breadcrumb-section">
				<ol class="breadcrumb">
					<li><a href="{{URL::to('/')}}">Home</a></li>
					<li>Company</li>
				</ol>
				<h2 class="title">Company</h2>
			</div>
			
			<div class="profile job-profile">
       			<div class="user-pro-section">
	            	<div class="profile-details section">
		                <h2>Company Details</h2>
						@if(Session::has('success'))
						    <div class="alert alert-success">
						      {{ Session::get('success') }}
						    </div>
						@endif
						@if(Session::has('error'))
							<div class="alert alert-danger">
								{{ Session::get('error') }}
							</div>
						@endif

						<?php if($shopcount==0){?>
							<form class="form-horizontal" role="form" method="POST" action="{{ route('addshop') }}" id="formID" enctype="multipart/form-data">
		                        {{ csrf_field() }}
			 					<input type="hidden" name="editid" value="">
			 					<input type="hidden" name="admin_email_id" value="<?php echo $admin_email_id[0]->email;?>">
		                		<input type="hidden" name="addresslat" id="addresslat" value="">	
		                		<input type="hidden" name="addresslong" id="addresslong" value="">
						
								<div class="form-group">
		                            <label for="name">Company Name <span class="require">*</span></label>
									<input id="shop_name" type="text" class="form-control validate[required] text-input" name="shop_name" value="{{ old('shop_name') }}" autofocus>
		        				</div>

		        				<div class="form-group">
				                    <label for="address" >Address <span class="require">*</span></label>
				                    <div id="postcode_lookup"></div>
				                    <br/>
                                  	<div class="pull-left left-112"><label>&nbsp;</label><p>Please fetch your address detail using your postcode</p></div>  
				                    
				                                                
				                        @if(count($address) == 0)
				                        <input id="line1" name="line1" class="form-control text-input validate[required] addr-line" type="text" placeholder="Address line1" value="{{ old('line1') }}">
				                        <input id="line2" name="line2" class="form-control text-input addr-line" type="text" placeholder="Address line2" value="{{ old('line2') }}">
				                        <input id="line3" name="line3" class="form-control text-input addr-line" type="text" placeholder="Address line3" value="{{ old('line3') }}">  
				                        <input id="town" name="town" class="form-control text-input validate[required] addr-line" type="text" placeholder="Town" value="{{ old('town') }}">             
				                        <input id="country" name="country" class="form-control text-input  validate[required] addr-line" type="text" placeholder="Country" value="{{ old('country') }}">
				                        <input id="postcode" name="postcode" class="form-control text-input  validate[required] addr-line" type="text" placeholder="Postalcode" value="{{ old('postcode') }}">
				                        @endif
				                </div>
				                <div class="form-group {{ $errors->has('shop_phone_no') ? ' has-error' : '' }}">
		                            <label for="name">Phone Number <span class="require">*</span></label>
		                            <input id="shop_phone_no" type="text" class="form-control validate[required] text-input" name="shop_phone_no" value="{{ old('shop_phone_no') }}">
		                            @if ($errors->has('shop_phone_no'))
		                                <span class="help-block">
		                                    <strong>{{ $errors->first('shop_phone_no') }}</strong>
		                                </span>
		                            @endif
		        				</div>	
		        				<div class="form-group {{ $errors->has('company_email') ? ' has-error' : '' }}">
		                            <label for="name">Company Email <span class="require">*</span></label>
		                            <input id="company_email" type="text" class="form-control validate[required] text-input" name="company_email" value="{{ old('company_email') }}">
		                            @if ($errors->has('name'))
		                                <span class="help-block">
		                                    <strong>{{ $errors->first('company_email') }}</strong>
		                                </span>
		                            @endif
		                        </div>
		                        <div class="form-group">
		                            <label for="category">Business Category <span class="require">*</span></label>
		                            <select name="category" id="category" class="trackprogress form-control text-input">
		                                <option value=""></option>
		                                @if($categories->count())
		                                    @foreach($categories as $category)
		                                        @if (Input::old('category') == $category->id)
		                                            <option value="{{ $category->id }}" selected>{{$category->name}}</option>
		                                        @else
		                                            <option value="{{ $category->id }}">{{$category->name}}</option>
		                                        @endif                                                
		                                    @endforeach
		                                @endif
		                            </select>                               
		                        </div>
					  			<div class="form-group">
		                            <label for="name" >Business Description <span class="require">*</span></label>
		                                <textarea id="shop_desc" class="form-control validate[required] text-input" name="shop_desc">{{ old('shop_desc') }}</textarea>
								</div>					  
		                        <?php if(!empty($site_setting[0]->site_logo)){
									?>
									<input type="hidden" name="site_logo" value="<?php echo $url.'/local/images/settings/'.$site_setting[0]->site_logo;?>">
								<?php } else { ?>
									<input type="hidden" name="site_logo" value="">
								<?php } ?>
									<input type="hidden" name="site_name" value="<?php echo $site_setting[0]->site_name;?>">		
								
								<div class="row">
								<a href="<?php echo $url;?>/addshop" class="btn pull-left">
		                                   Reset
		                                </a>		
								<?php if(config('global.demosite')=="yes"){?>
									<button type="button" class="btn radiusoff pull-right btndisable">Add</button> 
										<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
								<?php } else { ?>
									<button type="submit" class="btn  radiusoff pull-right">
		                                    Add
		                                </button>
								<?php } ?>
							</div>
							</form>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</section>

      @include('footer')
</body>
</html>