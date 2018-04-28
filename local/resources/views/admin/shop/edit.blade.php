@extends('admin/layout/default')
@section('title', 'Edit Shop')
@section('content')
  <?php $url = URL::to("/"); ?>
        <div class="content">
            <div class="container-fluid">
                
                <div class="row">

                    <div class="col-md-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                  
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
        <div class="card">
          
                     <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.edit-shop') }}" enctype="multipart/form-data" novalidate>
                     {{ csrf_field() }}  
                      <div class="header">
                      <h4 class="title">Edit Shop</h4>
                    </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shop Name 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="shop_name" class="form-control border-input col-md-7 col-xs-12"  name="shop_name" value="<?php echo $editshop[0]->shop_name; ?>" required="required" type="text">
                        
             </div>
                      </div>
                      
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Address 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="address" class="form-control border-input col-md-7 col-xs-12"  name="address" value="<?php echo $editshop[0]->address; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">City
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="city" class="form-control border-input col-md-7 col-xs-12"  name="city" value="<?php echo $editshop[0]->city; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Pin Code
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="pin_code" class="form-control border-input col-md-7 col-xs-12"  name="pin_code" value="<?php echo $editshop[0]->pin_code; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Country
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="country" class="form-control border-input col-md-7 col-xs-12"  name="country" value="<?php echo $editshop[0]->country; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">State
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="state" class="form-control border-input col-md-7 col-xs-12"  name="state" value="<?php echo $editshop[0]->state; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shop Phone No
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="shop_phone_no" class="form-control border-input col-md-7 col-xs-12"  name="shop_phone_no" value="<?php echo $editshop[0]->shop_phone_no; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Description
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="description" class="form-control border-input col-md-7 col-xs-12"  name="description" value="<?php echo $editshop[0]->description; ?>" required="required" type="text">
                        
             </div>
                      </div>
            
            
            
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Phone No
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="phone_no" class="form-control border-input col-md-7 col-xs-12"  name="phone_no" value="<?php echo $userdata[0]->phone;?>"  type="text" disabled>
                        
             </div>
                      </div>
                     
           
           
           
           <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Start Time
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="start_time" class="form-control border-input col-md-7 col-xs-12"  name="start_time" value="<?php echo $stime;?>"  type="text" disabled>
                        
             </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">End Time
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="end_time" class="form-control border-input col-md-7 col-xs-12"  name="end_time" value="<?php echo $etime;?>"  type="text" disabled>
                        
             </div>
                      </div>
            
            
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Advance Booking Opening days
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="booking_opening_days" class="form-control border-input col-md-7 col-xs-12"  name="booking_opening_days" value="<?php echo $editshop[0]->booking_opening_days; ?>"  type="text" disabled>
                        
             </div>
                      </div>
            
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Status
                        </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <select id="status" name="status" class="form-control border-input">
              <option value="unapproved" <?php  if($editshop[0]->status=="unapproved") echo "selected='selected'"; ?>>Unapproved</option>
              <option value="approved" <?php  if($editshop[0]->status=="approved") echo "selected='selected'";  ?>>Approved</option>
            </select> 
            </div>
          </div>  
            
            
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Featured
                        </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <select id="featured" name="featured" class="form-control border-input">
            
              <option value="yes" <?php  if($editshop[0]->featured=="yes") echo "selected='selected'"; ?>>Yes</option>
              <option value="no" <?php  if($editshop[0]->featured=="no") echo "selected='selected'";  ?>>No</option>
            </select> 
            </div>
          </div>  
          
          
          <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Shop Working Days
                        </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="checkbox" name="check_list[]" id="working_date" disabled="disabled" class=""  value="0" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="0") echo "checked=='checked'"; }?>>Sunday <br>
          <input type="checkbox" name="check_list[]" id="working_date" disabled="disabled" class=""  value="1" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="1") echo "checked=='checked'"; }?>>Monday <br>
          <input type="checkbox" name="check_list[]" id="working_date" disabled="disabled" class=""  value="2" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="2") echo "checked=='checked'"; }?>>Tuesday <br>
          <input type="checkbox" name="check_list[]" id="working_date" disabled="disabled" class=""  value="3" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="3") echo "checked=='checked'"; }?>>Wednesday <br>
          <input type="checkbox" name="check_list[]" class="" id="working_date" disabled="disabled" value="4" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="4") echo "checked=='checked'"; }?>>Thursday  <br>
          <input type="checkbox" name="check_list[]" id="working_date" class="" disabled="disabled" value="5" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="5") echo "checked=='checked'"; }?>>Friday  <br>
          <input type="checkbox" name="check_list[]" id="working_date" class="" disabled="disabled" value="6" <?php for($i=0;$i<$lev;$i++){ if($sel[$i]=="6") echo "checked=='checked'"; }?>>Saturday
            
            </div>
            
            </div>    
          
          
          <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Cover Photo
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
             <input type="file" id="shop_cover_photo" name="shop_cover_photo" class="form-control border-input" disabled>
             
             <?php 
             $shopphoto="/shop/";
            $paths ='/local/images'.$shopphoto.$editshop[0]->cover_photo;
            if($editshop[0]->cover_photo!=""){
            ?>
           <br/>
            <div class="col-md-12">
            <img src="<?php echo $url.$paths;?>" class="thumb" width="150">
            </div>
           
            <?php } else { ?>
            <br/>
            <div class="col-md-12">
            <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="150">
            </div>
           
            <?php } ?>
            </div>
            
          </div>  
            
          
          
          <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Gallery
                        </label>
            
            <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="file" id="photo" name="photo" class="form-control border-input col-md-7 col-xs-12" disabled>
            <div class="col-md-12" style="margin-top:10px;">
            <?php foreach($viewgallery as $gallery){?>
            
            <?php 
             $galphoto="/gallery/";
            $paths ='/local/images'.$galphoto.$gallery->image;
            if($gallery->image!=""){
            ?>
            
            <div class="col-md-3 col-sm-3">
            <img src="<?php echo $url.$paths;?>" class="thumb" width="100">
            </div>
           
            <?php } else { ?>
            
            <div class="col-md-3 col-sm-3">
            <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="100">
            </div>
            <?php } ?>
            
            <?php } ?>
            </div>
            </div>
            </div>
            
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Booking Per Hour
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="booking_per_hour" class="form-control border-input col-md-7 col-xs-12"  name="booking_per_hour" value="<?php echo $editshop[0]->booking_per_hour; ?>"  type="text" disabled>
                        
             </div>
                      </div>
            
            
            
            
            
            <?php if(!empty($site_setting[0]->site_logo)){
               
              ?>
            
            <input type="hidden" name="site_logo" value="<?php echo $url.'/local/images/settings/'.$site_setting[0]->site_logo;?>">
          
            <?php } else { ?>
            
            <input type="hidden" name="site_logo" value="">
            
            <?php } ?>
            
            
            <input type="hidden" name="site_name" value="<?php echo $site_setting[0]->site_name;?>">
                     
            <input type="hidden" name="editid" value="<?php echo $editshop[0]->id; ?>">
            
            
            <input type="hidden" name="email_status" value="<?php echo $editshop[0]->admin_email_status; ?>">
            
            <input type="hidden" name="show_owner_email" value="<?php echo $editshop[0]->seller_email;  ?>">
            
                     
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <a href="<?php echo $url;?>/admin/shop" class="btn btn-primary">Cancel</a>
                          <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
                        </div>
                      </div>
                    </form>
                    </div>
                  </div>
                </div>
              </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    
                    
                </div>
            </div>
       
@endsection
