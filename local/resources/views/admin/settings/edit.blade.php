@extends('admin/layout/default')
@section('title', 'Settings')
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
          
                     <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.settings') }}" enctype="multipart/form-data" novalidate>
                     {{ csrf_field() }}  
                      <div class="header">
                  <h4 class="title">Settings</h4>
                </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Site Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_name" class="form-control border-input col-md-7 col-xs-12"  name="site_name" value="<?php echo $settings[0]->site_name; ?>" required="required" type="text">
                        
             </div>
                      </div>
                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Site Description
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          
               <textarea id="site_desc" class="form-control border-input col-md-7 col-xs-12" name="site_desc"><?php echo $settings[0]->site_desc; ?></textarea>
                        </div>
                      </div>
            
            <input type="hidden" name="save_desc" value="<?php echo $settings[0]->site_desc; ?>">
                      
                      
                      <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">Site Keyword</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_keyword" type="text" name="site_keyword" value="<?php echo $settings[0]->site_keyword; ?>"  class="form-control border-input col-md-7 col-xs-12">
              
                        </div>
                      </div>
            
            
            <input type="hidden" name="save_key" value="<?php echo $settings[0]->site_keyword; ?>">
            
            
            <input type="hidden" name="save_facebook" value="<?php echo $settings[0]->site_facebook; ?>">
            <input type="hidden" name="save_twitter" value="<?php echo $settings[0]->site_twitter; ?>">
            <input type="hidden" name="save_gplus" value="<?php echo $settings[0]->site_gplus; ?>">
            <input type="hidden" name="save_pinterest" value="<?php echo $settings[0]->site_pinterest; ?>">
            <input type="hidden" name="save_instagram" value="<?php echo $settings[0]->site_instagram; ?>">
            
            <input type="hidden" name="save_copyright" value="<?php echo $settings[0]->site_copyright; ?>">
            
            <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">Facebook Link</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_facebook" type="text" name="site_facebook" value="<?php echo $settings[0]->site_facebook; ?>"  class="form-control border-input col-md-7 col-xs-12">
              
                        </div>
                      </div>
            
            
            
            <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">Twitter Link</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_twitter" type="text" name="site_twitter" value="<?php echo $settings[0]->site_twitter; ?>"  class="form-control col-md-7 col-xs-12">
              
                        </div>
                      </div>
            
            
            <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">GPlus Link</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_gplus" type="text" name="site_gplus" value="<?php echo $settings[0]->site_gplus; ?>"  class="form-control border-input col-md-7 col-xs-12">
              
                        </div>
                      </div>
            
            
            
             <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">Pinterest Link</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_pinterest" type="text" name="site_pinterest" value="<?php echo $settings[0]->site_pinterest; ?>"  class="form-control border-input col-md-7 col-xs-12">
              
                        </div>
                      </div>
            
            
            
            
            <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">Instagram Link</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_instagram" type="text" name="site_instagram" value="<?php echo $settings[0]->site_instagram; ?>"  class="form-control border-input col-md-7 col-xs-12">
              
                        </div>
                      </div>
            
    <div class="item form-group">
                        <label for="keyword" class="control-label col-md-3">Footer Copyright</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="site_copyright" type="text" name="site_copyright" value="<?php echo $settings[0]->site_copyright; ?>"  class="form-control border-input col-md-7 col-xs-12">
              
                        </div>
                      </div>        
            
                      
                      
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="currency">Currency <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="currency" required="required" class="form-control border-input col-md-7 col-xs-12">
            <option value=""></option>
            <?php foreach($currency as $newcurrency){?>
                 <option value="<?php echo $newcurrency;?>" <?php if($settings[0]->site_currency==$newcurrency){?> selected="selected" <?php } ?>><?php echo $newcurrency;?></option>
            <?php } ?>
            </select>
                          
                        </div>
                      </div>
           
            
            
            
          <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo">Logo
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" id="site_logo" name="site_logo" class="form-control border-input col-md-7 col-xs-12">
              
               @if ($errors->has('site_logo'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('site_logo') }}</strong>
                                    </span>
                                @endif
              
                        </div>
                      </div>
             
            <?php 
             $settingphoto="/settings/";
            $path ='/local/images'.$settingphoto.$settings[0]->site_logo;
            if($settings[0]->site_logo!=""){
            ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.$path;?>" class="thumb" width="100">
            </div>
            </div>
            <?php } else { ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="logo" width="100">
            </div>
            </div>
            <?php } ?>
            
            
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo">Favicon
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" id="site_favicon" name="site_favicon" class="form-control border-input col-md-7 col-xs-12">
               @if ($errors->has('site_favicon'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('site_favicon') }}</strong>
                                    </span>
                                @endif
              
                        </div>
                      </div>
            
            
            <?php 
             $settingphotos="/settings/";
            $paths ='/local/images'.$settingphotos.$settings[0]->site_favicon;
            if($settings[0]->site_favicon!=""){
            ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.$paths;?>" class="thumb" width="24" style="border:1px solid #CCCCCC;">
            </div>
            </div>
            <?php } else { ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="logo" width="24" style="border:1px solid #CCCCCC;">
            </div>
            </div>
            <?php } ?>
            
            
            
            
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo">Home Page Banner
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" id="site_banner" name="site_banner" class="form-control border-input col-md-7 col-xs-12">
               @if ($errors->has('site_banner'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('site_banner') }}</strong>
                                    </span>
                                @endif
              
                        </div>
                      </div>
            
            
             <?php 
             $bannerphotos="/settings/";
            $pathes ='/local/images'.$bannerphotos.$settings[0]->site_banner;
            if($settings[0]->site_banner!=""){
            ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.$pathes;?>" class="thumb" width="200" style="border:1px solid #CCCCCC;">
            </div>
            </div>
            <?php } else { ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="logo" width="100" style="border:1px solid #CCCCCC;">
            </div>
            </div>
            <?php } ?>
            
            
            
            
            
            
            
            
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="commission mode">Commission Mode <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
            
            <select name="commission_mode" id="commission_mode" class="form-control border-input" required="required">
                  <option value="fixed" <?php { if($settings[0]->commission_mode=="fixed") echo "selected='selected'"; }?>>Fixed</option>
                  <option value="percentage" <?php { if($settings[0]->commission_mode=="percentage") echo "selected='selected'"; }?>>Percentage</option>
                </select>
            
                          
                        </div>
                      </div>
            
            
            <div class="item form-group">
                        <label for="amount" class="control-label col-md-3">Enter Amout / percentage</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="commission_amt" type="text" name="commission_amt" value="<?php echo $settings[0]->commission_amt; ?>"  class="form-control border-input col-md-7 col-xs-12" required="required">
              
                        </div>
                      </div>
            
            
            
            
            
            <div class="item form-group">
                        <label for="amount" class="control-label col-md-3">Paypal Id</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="paypal_id" type="text" name="paypal_id" value="<?php echo $settings[0]->paypal_id; ?>"  class="form-control border-input col-md-7 col-xs-12" required="required">
              
                        </div>
                      </div>
            
            
            
            
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="commission mode">Paypal site Mode <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
            
            <select name="paypal_url" id="paypal_url" class="form-control border-input" required="required">
            <option value="">Select</option>
                  <option value="https://www.sandbox.paypal.com/cgi-bin/webscr" <?php { if($settings[0]->paypal_url=="https://www.sandbox.paypal.com/cgi-bin/webscr") echo "selected='selected'"; }?>>Test</option>
                  <option value="https://www.paypal.com/cgi-bin/webscr" <?php { if($settings[0]->paypal_url=="https://www.paypal.com/cgi-bin/webscr") echo "selected='selected'"; }?>>Live</option>
                </select>
            
                          
                        </div>
                      </div>
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="commission mode">Withdraw Option <span class="required">*</span>
                        </label>
            
                        <div class="col-md-6 col-sm-6 col-xs-12">
            
            <?php 
            
            
            $array =  explode(',', $settings[0]->withdraw_option);
            
            
            foreach($withdraw as $draw){?>
            <input type="checkbox" name="withdraw_opt[]" value="<?php echo $draw;?>" <?php  if(in_array($draw,$array)){?> checked <?php } ?> > <?php echo $draw;?><br/>
            <?php } ?>
            </div>
            
            </div>
            
            <?php /* ?><?php  if(in_array($draw,$narray)){?> checked <?php } ?><?php */?>
            
            <div class="item form-group">
                        <label for="amount" class="control-label col-md-3">Min. Withdraw Amount</label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="withdraw_amt" type="text" name="withdraw_amt" value="<?php echo $settings[0]->withdraw_amt; ?>"  class="form-control border-input col-md-7 col-xs-12" required="required">
              
                        </div>
                      </div>
            
            
            
            
            <input type="hidden" name="currentlogo" value="<?php echo $settings[0]->site_logo;?>">
            
            
            <input type="hidden" name="currentfav" value="<?php echo $settings[0]->site_favicon;?>">
            
            <input type="hidden" name="currentban" value="<?php echo $settings[0]->site_banner;?>">
           
            
            
            
                     
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <a href="<?php echo $url;?>/admin/settings" class="btn btn-primary">Cancel</a>
              <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button> 
                <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>
              
                          <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
                <?php } ?>
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
