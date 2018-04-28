@extends('admin/layout/default')
@section('title', 'Edit Services')
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
          
                     <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.editsubservice') }}" enctype="multipart/form-data" novalidate>
                     {{ csrf_field() }}  
                      <div class="header">
                        <h4 class="title">Edit Sub Services</h4>
                      </div>
            
           <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Service <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control border-input col-md-7 col-xs-12"  name="service" required="required">
              <option value=""></option>
              <?php foreach($services as $service){?>
              <option value="<?php echo $service->id;?>" <?php if($subservices[0]->service==$service->id){?> selected="selected" <?php } ?> ><?php echo $service->name;?></option>
              <?php } ?>
              </select>
                        
             </div>
                      </div>
            
            
            
            

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="name" class="form-control border-input col-md-7 col-xs-12"  name="name" value="<?php echo $subservices[0]->subname; ?>" required="required" type="text">
                        @if ($errors->has('name'))
                                    <span class="help-block" style="color:red;">
                                        <strong>That sub service is already exists</strong>
                                    </span>
                                @endif
             </div>
                      </div>
                      
                      
                     
                      
                     
            <input type="hidden" name="subid" value="<?php echo $subservices[0]->subid; ?>">
            
            
            
             <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="photo">Image <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" id="photo" name="photo" class="form-control border-input col-md-7 col-xs-12">
               @if ($errors->has('photo'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif
              
                        </div>
                      </div>
             <?php $url = URL::to("/"); ?>
            <?php 
             $subservicephoto="/subservicephoto/";
            $path ='/local/images'.$subservicephoto.$subservices[0]->subimage;
            if($subservices[0]->subimage!=""){
            ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.$path;?>" class="thumb" width="100">
            </div>
            </div>
            <?php } else { ?>
            <div class="item form-group" align="center">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="100">
            </div>
            </div>
            <?php } ?>
            
            <input type="hidden" name="currentphoto" value="<?php echo $subservices[0]->subimage;?>">
                    
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <a href="<?php echo $url;?>/admin/subservices" class="btn btn-primary">Cancel</a>
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
