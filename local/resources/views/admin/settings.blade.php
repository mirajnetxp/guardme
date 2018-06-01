<!DOCTYPE html>
<html lang="en">
  <head>

   @include('admin.title')

    @include('admin.style')


  </head>

  <body>
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

<style>
.form-group  img {
  float: left;
}
</style>


          <div class="container-fluid">


                <div class="card" style="padding:15px;">

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
  <div class="header">
      <h4 class="title">Settings</h4>
      <!-- <p class="category">Here is a subtitle for this table</p> -->
  </div>
                <?php $url = URL::to("/"); ?>
                   <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.settings') }}" enctype="multipart/form-data" novalidate>
                     <div class="col-md-8 col-sm-8 col-xs-8 col-md-offset-1">
                       {{ csrf_field() }}
                        <!-- <span class="section">Settings</span> -->

                        <div class="form-group">
                          <label for="name">Site Name <span class="required">*</span>
                          </label>

                            <input id="site_name" class="form-control border-input"  name="site_name" value="<?php echo $settings[0]->site_name; ?>" required="required" type="text">


                        </div>
                        <div class="form-group">
                          <label for="desc">Site Description
                          </label>


  						   <textarea id="site_desc" class="form-control border-input" name="site_desc"><?php echo $settings[0]->site_desc; ?></textarea>

                        </div>

  					  <input type="hidden" name="save_desc" value="<?php echo $settings[0]->site_desc; ?>">


                        <div class="form-group">
                          <label for="keyword">Site Keyword</label>
                          <input id="site_keyword" type="text" name="site_keyword" value="<?php echo $settings[0]->site_keyword; ?>"  class="form-control border-input">


                        </div>


  					  <input type="hidden" name="save_key" value="<?php echo $settings[0]->site_keyword; ?>">


  					  <input type="hidden" name="save_facebook" value="<?php echo $settings[0]->site_facebook; ?>">
  					  <input type="hidden" name="save_twitter" value="<?php echo $settings[0]->site_twitter; ?>">
  					  <input type="hidden" name="save_gplus" value="<?php echo $settings[0]->site_gplus; ?>">
  					  <input type="hidden" name="save_pinterest" value="<?php echo $settings[0]->site_pinterest; ?>">
  					  <input type="hidden" name="save_instagram" value="<?php echo $settings[0]->site_instagram; ?>">

  					  <input type="hidden" name="save_copyright" value="<?php echo $settings[0]->site_copyright; ?>">

  					  <div class="form-group">
                          <label for="keyword">Facebook Link</label>

                            <input id="site_facebook" type="text" name="site_facebook" value="<?php echo $settings[0]->site_facebook; ?>"  class="form-control border-input">
                        </div>



  					  <div class="form-group">
                          <label for="keyword">Twitter Link</label>

                            <input id="site_twitter" type="text" name="site_twitter" value="<?php echo $settings[0]->site_twitter; ?>"  class="form-control border-input">


                        </div>


  					  <div class="form-group">
                          <label for="keyword">GPlus Link</label>
                            <input id="site_gplus" type="text" name="site_gplus" value="<?php echo $settings[0]->site_gplus; ?>"  class="form-control border-input">

                          </div>



  					   <div class="form-group">
                          <label for="keyword">Pinterest Link</label>
                            <input id="site_pinterest" type="text" name="site_pinterest" value="<?php echo $settings[0]->site_pinterest; ?>"  class="form-control border-input">


                        </div>




  					  <div class="item form-group">
                          <label for="keyword">Instagram Link</label>

                            <input id="site_instagram" type="text" name="site_instagram" value="<?php echo $settings[0]->site_instagram; ?>"  class="form-control border-input">


                        </div>

  		<div class="form-group">
                          <label for="keyword">Footer Copyright</label>
                            <input id="site_copyright" type="text" name="site_copyright" value="<?php echo $settings[0]->site_copyright; ?>"  class="form-control border-input">

                        </div>



  					   <div class="form-group">
                          <label for="currency">Currency <span class="required">*</span>
                          </label>

  						<select name="currency" required="required" class="form-control border-input">
  						<option value=""></option>
  						<?php foreach($currency as $newcurrency){?>
  							   <option value="<?php echo $newcurrency;?>" <?php if($settings[0]->site_currency==$newcurrency){?> selected="selected" <?php } ?>><?php echo $newcurrency;?></option>
  						<?php } ?>
  						</select>


                        </div>




  					<div class="form-group">
                          <label for="logo">Logo
                          </label>

                            <input type="file" id="site_logo" name="site_logo" class="form-control border-input">

  						   @if ($errors->has('site_logo'))
                                      <span class="help-block" style="color:red;">
                                          <strong>{{ $errors->first('site_logo') }}</strong>
                                      </span>
                                  @endif


                        </div>

  					  <?php
  					   $settingphoto="/settings/";
  						$path ='/local/images'.$settingphoto.$settings[0]->site_logo;
  						if($settings[0]->site_logo!=""){
  						?>
  					  <div class="form-group" align="center">

  					  <img src="<?php echo $url.$path;?>" class="thumb" width="100">

  					  </div>
  						<?php } else { ?>
  					  <div class="form-group" align="center">

  					  <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="logo" width="100">

  					  </div>
  						<?php } ?>




  						<div class="form-group">
                          <label for="logo">Favicon
                          </label>

                            <input type="file" id="site_favicon" name="site_favicon" class="form-control border-input">
  						   @if ($errors->has('site_favicon'))
                                      <span class="help-block" style="color:red;">
                                          <strong>{{ $errors->first('site_favicon') }}</strong>
                                      </span>
                                  @endif


                        </div>


  					  <?php
  					   $settingphotos="/settings/";
  						$paths ='/local/images'.$settingphotos.$settings[0]->site_favicon;
  						if($settings[0]->site_favicon!=""){
  						?>
  					  <div class="form-group" align="center">

  					  <img src="<?php echo $url.$paths;?>" class="thumb" width="24" style="border:1px solid #CCCCCC;">

  					  </div>
  						<?php } else { ?>
  					  <div class="form-group" align="center">
  					    <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="logo" width="24" style="border:1px solid #CCCCCC;">
  					  </div>
  						<?php } ?>






  						<div class="form-group">
                          <label for="logo">Home Page Banner
                          </label>

                            <input type="file" id="site_banner" name="site_banner" class="form-control border-input">
  						   @if ($errors->has('site_banner'))
                                      <span class="help-block" style="color:red;">
                                          <strong>{{ $errors->first('site_banner') }}</strong>
                                      </span>
                                  @endif


                        </div>


  					   <?php
  					   $bannerphotos="/settings/";
  						$pathes ='/local/images'.$bannerphotos.$settings[0]->site_banner;
  						if($settings[0]->site_banner!=""){
  						?>
  					  <div class="form-group" align="center">

  					  <img src="<?php echo $url.$pathes;?>" class="thumb" width="200" style="border:1px solid #CCCCCC;">

  					  </div>
  						<?php } else { ?>
  					  <div class="form-group" align="center">

  					  <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="logo" width="100" style="border:1px solid #CCCCCC;">

  					  </div>
  						<?php } ?>








  						 <div class="form-group">
                          <label for="commission mode">Commission Mode <span class="required">*</span>
                          </label>


  						<select name="commission_mode" id="commission_mode" class="form-control border-input" required="required">
  									<option value="fixed" <?php { if($settings[0]->commission_mode=="fixed") echo "selected='selected'"; }?>>Fixed</option>
  									<option value="percentage" <?php { if($settings[0]->commission_mode=="percentage") echo "selected='selected'"; }?>>Percentage</option>
  								</select>



                        </div>


  						<div class="form-group">
                          <label for="amount" >Enter Amout / percentage</label>

                            <input id="commission_amt" type="text" name="commission_amt" value="<?php echo $settings[0]->commission_amt; ?>"  class="form-control border-input" required="required">


                        </div>





  					  <div class="form-group">
                          <label for="amount">Paypal Id</label>

                            <input id="paypal_id" type="text" name="paypal_id" value="<?php echo $settings[0]->paypal_id; ?>"  class="form-control border-input" required="required">


                        </div>




  					   <div class="form-group">
                          <label for="commission mode">Paypal site Mode <span class="required">*</span>
                          </label>


  						<select name="paypal_url" id="paypal_url" class="form-control border-input" required="required">
  						<option value="">Select</option>
  									<option value="https://www.sandbox.paypal.com/cgi-bin/webscr" <?php { if($settings[0]->paypal_url=="https://www.sandbox.paypal.com/cgi-bin/webscr") echo "selected='selected'"; }?>>Test</option>
  									<option value="https://www.paypal.com/cgi-bin/webscr" <?php { if($settings[0]->paypal_url=="https://www.paypal.com/cgi-bin/webscr") echo "selected='selected'"; }?>>Live</option>
  								</select>



                        </div>


  					  <div class="form-group">
                          <label for="commission mode">Withdraw Option <span class="required">*</span>
                          </label>



  						<?php


  						$array =  explode(',', $settings[0]->withdraw_option);


  						foreach($withdraw as $draw){?>
  						<input type="checkbox" name="withdraw_opt[]" value="<?php echo $draw;?>" <?php  if(in_array($draw,$array)){?> checked <?php } ?> > <?php echo $draw;?><br/>
  						<?php } ?>


  						</div>

  						<?php /* ?><?php  if(in_array($draw,$narray)){?> checked <?php } ?><?php */?>

  						<div class="form-group">
                          <label for="amount">Min. Withdraw Amount</label>

                            <input id="withdraw_amt" type="text" name="withdraw_amt" value="<?php echo $settings[0]->withdraw_amt; ?>"  class="form-control border-input" required="required">


                        </div>




  					  <input type="hidden" name="currentlogo" value="<?php echo $settings[0]->site_logo;?>">


  					  <input type="hidden" name="currentfav" value="<?php echo $settings[0]->site_favicon;?>">

  					  <input type="hidden" name="currentban" value="<?php echo $settings[0]->site_banner;?>">





                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-md-offset-3">
                            <a href="<?php echo $url;?>/admin/settings" class="btn btn-primary btn-fill">Cancel</a>
  						  <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn-info btn-fill btn-wd btndisable">Submit</button>
  								<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>

                            <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
  								<?php } ?>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

</div>













        <!-- /page content -->

      @include('admin.footer')
      </div>
    </div>



  </body>
</html>
