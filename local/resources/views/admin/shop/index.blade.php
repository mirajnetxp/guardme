@extends('admin/layout/default')
@section('title', 'Shop')
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
                  <h4 class="title">Shop</h4>
                </div>
                
          <div align="right">
           
                  <div class="x_content">
                   
                  <div class="table-responsive">
                    <div align="right">
          
                  <div class="x_content">
                   
          
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Sno</th>
              <th>Shop Name</th>
                          <th>Address</th>
                          <th>Shop Phone No</th>
              <th>Featured</th>
              <th>Status</th>
              <th>Total Balance</th>
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
            <?php 
            $i=1;
            foreach ($shop as $viewshop) { ?>
    
            
                        <tr>
             <td><?php echo $i;?></td>
             
                          <td><?php echo $viewshop->shop_name;?></td>
                          
              <td><?php echo substr($viewshop->address,0,150).'...';?></td>
              
               <td><?php echo $viewshop->shop_phone_no;?></td>
               
               <td><?php echo $viewshop->featured;?></td>
               
               <td><?php echo $viewshop->status;?></td>
               
               <td> - </td>
              
              <td>
              
      <?php if(config('global.demosite')=="yes"){?>
              <a href="#" class="btn btn-success btndisable">Edit</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
          <?php } else { ?>       
              <a href="<?php echo $url;?>/admin/edit-shop/{{ $viewshop->id }}" class="btn btn-success">Edit</a>
              <?php } ?>
           <?php if(config('global.demosite')=="yes"){?>
           <a href="#" class="btn btn-danger btndisable">Delete</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
          <?php } else { ?>
             <a href="<?php echo $url;?>/admin/shop/{{ $viewshop->id }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
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
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    
                    
                </div>
            </div>
       
        <script type="text/javascript">
          $(document).ready(function() {
              $('#datatable-responsive').DataTable();
          });
        </script>
@endsection
