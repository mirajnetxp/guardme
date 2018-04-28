@extends('admin/layout/default')
@section('title', 'Add Testimonial')
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
          
                     <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.add-testimonial') }}" enctype="multipart/form-data" novalidate>
                     {{ csrf_field() }}  
                     <div class="header">
                        <h4 class="title">Add Testimonial</h4>
                      </div>

                      <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="name" class="form-control border-input col-md-7 col-xs-12"  name="name" value="{{ old('name') }}" required="required" type="text">
               @if ($errors->has('name'))
                                    <span class="help-block" style="color:red;">
                                        <strong>That testimonial name is already exists</strong>
                                    </span>
                                @endif
                        
             </div>
                      </div>
                      
            
            
            <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="desc">Description <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          
                        <textarea id="desc" class="form-control border-input col-md-7 col-xs-12" required="required" name="desc">{{ old('desc') }}</textarea>
             </div>
                      </div>
            
                      
                      
            
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
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <a href="<?php echo $url;?>/admin/testimonials" class="btn btn-primary">Cancel</a>
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
