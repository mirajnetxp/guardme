@extends('admin/layout/default')
@section('title', 'Services')
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
        
          
                     <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="card">
                <div class="x_panel">
                  <div class="header">
                  <h4 class="title">Services</h4>
                </div>
                
          <div align="right">
           
                  <div class="x_content">
                   <?php if(config('global.demosite')=="yes"){?>
                    <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span> <a href="#" class="btn btn-info btn-fill btn-wd btndisable">Add Service</a> 
                    <?php } else { ?>
                    <a href="<?php echo $url;?>/admin/addservice" class="btn btn-info btn-fill btn-wd">Add Service</a>
                    <?php } ?>
          
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Sno</th>
              <th>Image</th>
                          <th>Name</th>
                         
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
            <?php 
            $i=1;
            foreach ($services as $service) { ?>
    
            
                        <tr>
             <td><?php echo $i;?></td>
             <?php 
             $servicephoto="/servicephoto/";
            $path ='/local/images'.$servicephoto.$service->image;
            if($service->image!=""){
            ?>
             <td><img src="<?php echo $url.$path;?>" class="thumb" width="70"></td>
             <?php } else { ?>
              <td><img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="70"></td>
             <?php } ?>
                          <td><?php echo $service->name;?></td>
                          
              
              <td>
               <?php if(config('global.demosite')=="yes"){?>
              <a href="#" class="btn btn-success btndisable">Edit</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
          <?php } else { ?>
              <a href="<?php echo $url;?>/admin/editservice/{{ $service->id }}" class="btn btn-success">Edit</a>
              <?php } ?>
           <?php if(config('global.demosite')=="yes"){?>
           <a href="#" class="btn btn-danger btndisable">Delete</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
          <?php } else { ?>
             <a href="<?php echo $url;?>/admin/services/{{ $service->id }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
             
           <?php } ?>
             </td>
                        </tr>
                        <?php $i++;} ?>
                       
                      </tbody>
                    </table>
          
          
                  </div>
                </div>
              </div>
        
        
        
     
      
      
      
        </div>
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
