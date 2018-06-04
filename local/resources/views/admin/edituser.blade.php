<!DOCTYPE html>
<html lang="en">
  <head>
  <?php 
 use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();
 $url = URL::to("/"); 
 $setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		$name = Route::currentRouteName();
 if($currentPaths=="/")
 {
	 $pagetitle="Home";
 }
 else 
 {
	 $pagetitle=$currentPaths;
 }
 ?>
   @include('admin.title')

    <link rel="stylesheet" href="<?php echo $url;?>/css/bootstrap.min.css" >
    
    @include('admin.style')




	 <!-- css stylesheets -->
	 <?php if(!empty($setts[0]->site_favicon)){?>
	 <link rel="icon" type="image/x-icon" href="<?php echo $url.'/local/images/settings/'.$setts[0]->site_favicon;?>" />
	 <?php } else { ?>
	 <link rel="icon" type="image/x-icon" href="<?php echo $url.'/local/images/noimage.jpg';?>" />
	 <?php } ?>

	<!-- CSS -->
    <link rel="stylesheet" href="<?php echo $url;?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $url;?>/css/icofont.css"> 
    <link rel="stylesheet" href="<?php echo $url;?>/css/slidr.css">
	<link href="<?php echo $url;?>/css/star-rating.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $url;?>/css/main.css">  
	<link id="preset" rel="stylesheet" href="<?php echo $url;?>/css/presets/preset1.css">	
    <link rel="stylesheet" href="<?php echo $url;?>/css/responsive.css">
	<!-- CSS -->
	<!--old required css -->
		<link href="<?php echo $url;?>/css/flexslider.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="<?php echo $url;?>/css/validationEngine.jquery.css" type="text/css"/>
		<link href="<?php echo $url;?>/css/autocomplete.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $url;?>/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $url;?>/css/lightbox.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $url;?>/css/jquery-ui.css" rel="stylesheet" type="text/css">


		<link href="<?php echo $url;?>/css/custom.css" rel="stylesheet" type="text/css">
	<!--old required css -->
	
    <style>
        #address_id {
            width: 85% !important;
        }
        #getaddress_button {
            position: relative;
            left: 125px;
            top: 30px;
            float: unset !important;
            margin-bottom: 30px !important;
        }
    </style>

	
  </head>

  <body>
    <?php $url = URL::to("/"); ?>
    <div class="wrapper">
      <!-- <div class="main_container"> -->
      <div class="sidebar" data-background-color="white" data-active-color="danger">
        <div class="sidebar-wrapper">
            @include('admin.sitename');

            <!-- <div class="clearfix"></div> -->

            <!-- menu profile quick info -->
            @include('admin.welcomeuser')
            <!-- /menu profile quick info -->

            <!-- <br /> -->

            <!-- sidebar menu -->
            @include('admin.menu')




            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->

            <!-- /menu footer buttons -->
          </div>
        </div>

<div class="main-panel">
        <!-- top navigation -->
       @include('admin.top')




        <!-- /top navigation -->

        <!-- page content -->
        <div class="content">
          <!-- top tiles -->






          <div class="container-fluid">

                     <div class="row">

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

  <?php
  use Responsive\User;$luser = User::where('id', $editprofile[0]->id)->first();
  ?>

  <div class="col-lg-4 col-md-5">
      <div class="card card-user">
          <div class="image">
              <img src="<?php echo $url . "/local/resources/assets/admin/assets/img/background.jpg" ?>" alt="..."/>
          </div>
          <div class="content">
              <div class="author">
                <?php
                 $userphoto="/userphoto/";
                $path ='/local/images'.$userphoto.$editprofile[0]->photo;
                if($editprofile[0]->photo!=""){
                 $avtar_path = $url.$path;


                 } else {


                $avtar_path = $url.'/local/images/nophoto.jpg';


                 } ?>
                <img class="avatar border-white" src="<?php echo $avtar_path?>" alt="..."/>
                <h4 class="title"><?php echo $editprofile[0]->name; ?><br />
                   <a href="#"><small><?php echo $editprofile[0]->email; ?></small></a>
                </h4>
              </div>
              <p class="description text-center">
                  
              </p>
          </div>
          <hr>
          <div class="text-center">
              <div class="row">
                  <div class="col-md-1 col-md-offset-1">

                  </div>
                  <div class="col-md-8">
                      <h5>{{ $luser->getBalance() }}<br /><small>Current balance</small></h5>
                  </div>
                  <div class="col-md-1">

                  </div>
              </div>
          </div>
      </div>
      @if(count($company))
      <div class="card company-detail-card">
        <div class="card-header text-center">
          Company Information
        </div>
        <div class="card-body">
          <h5>Company Name</h5>
          <span> {{$company->shop_name}}</span><br>
           <h5>Company Email</h5>
          <span> {{$company->company_email}}</span>
        </div>
      </div>
      @endif

  </div>


  <div class="col-lg-8 col-md-7">

    <div class="profile job-profile">
        <div class="user-pro-section">
            <div class="profile-details section">  
                <form role="form" method="POST" action="{{ route('admin.addbalance') }}"novalidate>
                    <div class="form-group">
                      <span>Current balance: {{ $luser->getBalance() }}</span>
                    </div>
                    <div class="form-group">
                        <label for="balance">Add points to balance
                        </label>

                        <input type="number" min="0" value="0" id="balance" name="balance" required="required" class="form-control  border-input">

                    </div>
                    <input type="hidden" name="user" value="{{ $editprofile[0]->id }}">
                        {{ csrf_field() }}

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-6">
                            <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
                        </div>
                    </div>

                </form>
                <form method="POST" action="{{ route('admin.edituser') }}" id="formID" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="profile_page" value="yes">

                         <input type="hidden" name="id" value="<?php echo $editprofile[0]->id; ?>">
                        <input type="hidden" name="usertype" value="<?php echo $editprofile[0]->admin;?>">                
                        <input type="hidden" name="savepassword" value="<?php echo $editprofile[0]->password;?>">
                        @if(count($address) >0)
                        <input type="hidden" id="addresslat" name="addresslat" value="{{$address[0]->latitude}}">  
                        <input type="hidden" id="addresslong" name="addresslong" value="{{$address[0]->longitude}}">
                        @else
                        <input type="hidden" id="addresslat" name="addresslat" value="">  
                        <input type="hidden" id="addresslong" name="addresslong" value="">                        
                        @endif

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label>Username</label>
                            <input id="name" type="text" class="trackprogress form-control validate[required] text-input" name="name" value="<?php echo $editprofile[0]->name;?>" autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname">First Name</label>
                            <input id="firstname" type="text" class="trackprogress form-control validate[required] text-input" name="firstname" value="<?php echo $editprofile[0]->firstname;?>" autofocus>
                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname">Last Name</label>
                            <input id="lastname" type="text" class="trackprogress form-control validate[required] text-input" name="lastname" value="<?php echo $editprofile[0]->lastname;?>" autofocus>
                            @if ($errors->has('lastname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">E-Mail Address</label>
                            <input id="email" type="text" class="trackprogress form-control validate[required,custom[email]] text-input" name="email" value="<?php echo $editprofile[0]->email;?>">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('address_id') ? ' has-error' : '' }}">
                                <label for="address" >Address</label>
                               
                                    <div id="postcode_lookup"></div>
                                    <br/>
                                    <div class="pull-left left-112"><label>&nbsp;</label><p>Please fetch your address detail using your postcode</p></div>
                                    @if ($errors->has('address_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address_id') }}</strong>
                                        </span>
                                    @endif
                                    @if(count($address) >0)
                                    <input id="line1" name="line1" class="trackprogress form-control text-input validate[required] addr-line" type="text" placeholder="Address line1" value="{{$address[0]->line1}}">
                                    <input id="line2" name="line2" class="trackprogress form-control text-input addr-line" type="text" placeholder="Address line2" value="{{$address[0]->line2}}">
                                    <input id="line3" name="line3" class="trackprogress form-control text-input addr-line" type="text" placeholder="Address line3" value="{{$address[0]->line3}}">  
                                    <input id="town" name="town" class="trackprogress form-control text-input validate[required] addr-line" type="text" placeholder="Town" value="{{$address[0]->citytown}}">             
                                    <input id="country" name="country" class="trackprogress form-control text-input  validate[required] addr-line" type="text" placeholder="Country" value="{{$address[0]->country}}">
                                    <input id="postcode" name="postcode" class="trackprogress form-control text-input  validate[required] addr-line" type="text" placeholder="Postalcode" value="{{$address[0]->postcode}}">

                                    @else
                                    <input id="line1" name="line1" class="trackprogress form-control text-input validate[required] addr-line" type="text" placeholder="Address line1" value="">
                                    <input id="line2" name="line2" class="trackprogress form-control text-input addr-line" type="text" placeholder="Address line2" value="">
                                    <input id="line3" name="line3" class="trackprogress form-control text-input addr-line" type="text" placeholder="Address line3" value="">  
                                    <input id="town" name="town" class="trackprogress form-control text-input validate[required] addr-line" type="text" placeholder="Town" value="">             
                                    <input id="country" name="country" class="trackprogress form-control text-input  validate[required] addr-line" type="text" placeholder="Country" value="">
                                    <input id="postcode" name="postcode" class="trackprogress form-control text-input  validate[required] addr-line" type="text" placeholder="Postalcode" value="">                 
                                    @endif
                            </div>
                             <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" >Password</label>
                                <input id="password" type="password" class="trackprogress form-control"  name="password" value="">
                            </div> 
                            <div id='phoneVue'>
                                
                                    <h4 class="text-center page-title">
                                         <i class="fa fa-phone"></i>
                         
                                         <template v-if="action === 'new'"> Phone verification</template>
                                         <template v-if="action === 'unbind'"> Remove phone number</template>
                                         <template v-if="action === 'confirm'"> SMS Confirmation</template>
                                    </h4>
                           
                     
                                <div class="form-group phone-input">
                                    <label class="control-label">
                                                     Phone Number <template v-if="action === 'confirm'">(<a href="#" @click.prevent="change">change</a>)</template>
                                                 </label>
                                    
                                        <input class="form-control" type="text" v-model="phone" id='phone' name='phone' value="{{old('phone', $editprofile[0]->phone)}}" :disabled="action === 'unbind' || (action === 'confirm' && user.phone_verified)" />
                                </div>
                     
                                <div v-if="action === 'confirm'"  class="form-group" id="confirmation-code">
                                    <template v-if="action === 'confirm'">
                                                 <label  class="control-label col-md-4">Confirmation code</label>
                                                 
                                                     <input class="form-control" type="text" v-model="code" />
                                            
                                             </template>
                                </div>
                                <div class="form-group">
                                    
                                    <div class="col-md-6 col-md-offset-2 alert alert-info">
                                        <strong>Info!</strong> Please add your phone number in this format +447654321000
                                    </div>
                                    <a href="#" @click.prevent="send" class="btn  text pull-right" >
                                         <template v-if="action === 'confirm'">OK!</template>
                                         <template v-else-if="action === 'unbind'">Remove Phone Number</template>
                                         <template v-else-if="action === 'new'">Send confirmation code</template>
                                     </a>
                                </div>
                            </div> 
                            @if($editprofile[0]->admin == 2)
                            <div class="form-group">
                                <label for="gender" >Gender</label>
                               
                                    <select name="gender" class="trackprogress form-control validate[required] text-input">                           
                                            <option value=""></option>
                                            <option value="male" <?php if($editprofile[0]->gender=='male'){?> selected="selected" <?php } ?>>Male</option>
                                            <option value="female" <?php if($editprofile[0]->gender=='female'){?> selected="selected" <?php } ?>>Female</option>
                                    </select>
                            </div>
                            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                <label for="dob" >DOB</label>
                                   <?php                            
                                        echo Form::input('date', 'dob', old('dob', $editprofile[0]->dob), ['class' => 'trackprogress validate[required] form-control', 'placeholder' => 'dob']);
                                    ?>
                                    @if ($errors->has('dob'))
                                        <span class="help-block" style="color:red;">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                    @endif
                            </div>
                            @endif
                      <div class="form-group">
                        <label for="photo">Photo</label>

                          <input type="file" id="photo" name="photo" class="form-control border-input">
						  @if ($errors->has('photo'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif

                      </div>
                <input type="hidden" name="currentphoto" value="<?php echo $editprofile[0]->photo;?>">

                            <?php if($editprofile[0]->admin!=1){?>
					   <div class="form-group">
                        <label for="usertype">User Type</label>

						<select name="usertype" required="required" class="form-control border-input">
						<option value=""></option>
							   <option value="0" <?php if($editprofile[0]->admin==0){?> selected="selected" <?php } ?>>Customer</option>
							   <option value="2" <?php if($editprofile[0]->admin==2){?> selected="selected" <?php } ?>>Seller</option>
                 <option value="3" <?php if($editprofile[0]->admin==3){?> selected="selected" <?php } ?>>Licensed Partner</option>
						</select>


                      </div>
					  <?php } ?>
					  <?php if($editprofile[0]->admin==1){?>

					  <input type="hidden" name="usertype" value="<?php echo $editprofile[0]->admin;?>">

					  <?php } ?>
            <div class="ln_solid"></div>
                            <div class="buttons pull-right">
                            <a href="<?php echo $url;?>/admin/users" class="btn">Cancel</a>
                                <?php if(config('global.demosite')=="yes"){?>
                                        <button type="button" class="btn btndisable">Update</button> <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
                                    <?php } else { ?>
                                        <button type="submit" class="btn">
                                            Update
                                        </button>
                                    <?php } ?>
                            </div>
                </form>
            </div>
        </div>
    </div>
              </div>
            </div>

        </div>















        <!-- /page content -->

      @include('admin.footer')
      </div>
    </div>



  </body>
  <script src="{{ asset('js/vue_axios.js') }}"></script>
    <script src="{{ asset('js/phone.min.js') }}"></script>
	
	
	<script type="text/javascript" src="<?php echo $url;?>/js/jquery.flexisel.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://getaddress.io/js/jquery.getAddress-2.0.7.min.js"></script>
	
	
	<script src="<?php echo $url;?>/js/jquery.multiselect.js"></script>
<script>
	class Errors{
		constructor() {
			this.formErrors = {};
		}
		record(errors) {
			this.formErrors = errors;
		}
		load() {
			$(".error-span").addClass('hide');
			$.each(this.formErrors, function(i, v) {
				i = i.replace('[]', '');
				i = i.split('.')[0];
				if (typeof $('.'+ i) != 'undefined') {
					$('.'+ i).siblings('.error-span').html(v);
					$('.'+ i).siblings('.error-span').removeClass('hide');
				}
			});
			if (typeof $('.error-span:eq(0)').closest('.form-group') != 'undefined') {
				if (typeof $('.error-span:visible:eq(0)').closest('.form-group').offset() != 'undefined')
					var scrollPosition = $('.error-span:visible:eq(0)').closest('.form-group').offset().top - 70;
				$("html, body").animate({ scrollTop: scrollPosition }, 1000);
			}
		}
	}
$('#langOpt').multiselect({
    columns: 1,
    placeholder: 'Select Services'
});
var pgsVal = 0;
$( ".trackprogress" ).each(function(i, obj) {
    if($(this).val().length >0){
        pgsVal += 4;
    }
});
console.log(pgsVal);
$( "#progressbar" ).progressbar({
    value: pgsVal,
    create: function(event, ui) {$(this).find('.ui-widget-header').css({'background-color':'#5cb85c'})}
});
$('.trackprogress').change(function() {
    var pbVal = 0;
    $( ".trackprogress" ).each(function(i, obj) {
        if($(this).val().length >0){
            pbVal += 4;
        }
    });
    $( "#progressbar" ).progressbar( "option", "value", pbVal );
    return false; 
});
$('#postcode_lookup').getAddress({
    api_key: 'ZTIFqMuvyUy017Bek8SvsA12209',
    input_id : 'address_id',
    input_name : 'address_id',
    input_class :'form-control validate[required]',
    button_class : 'btn',
    dropdown_class:'form-control',
    <!--  Or use your own endpoint - api_endpoint:https://your-web-site.com/getAddress, -->
    output_fields:{
        line_1: '#line1',
        line_2: '#line2',
        line_3: '#line3',
        post_town: '#town',
        county: '#county',
        postcode: '#postcode'
    },
<!--  Optionally register callbacks at specific stages -->                                                                                                               
    onLookupSuccess: function(data){/* Your custom code */
        console.log(data);
        $('#addresslat').val(data.latitude);
        $('#addresslong').val(data.longitude);
        $('#country').val('UK');
    },
    onLookupError: function(){/* Your custom code */},
    onAddressSelected: function(elem,index){/* Your custom code */}
});
$("select#nationality").change(function(){    
    $( "select#nationality option:selected").each(function(){        
        if($(this).attr("value")=="229"){
            $("#visa_no_field").hide();
            $("#niutr_no_field").show();
        } else {
            $("#visa_no_field").show();
            $("#niutr_no_field").hide();
        }
    });
}).change();
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
jQuery('.date-time-picker').datetimepicker({
	//language:  'uk',
	weekStart: 1,
	todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	forceParse: 0,
	showMeridian: 1,
	minuteStep: 5
});
</script>
</html>
