@extends('admin/layout/default')
@section('title', 'Pages')
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
                  <h4 class="title">Pages</h4>
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
              
                          <th>Heading</th>
                          
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
            <?php 
            $i=1;
            foreach ($pages as $page) { ?>
    
            
                        <tr>
             <td><?php echo $i;?></td>
            
                          <td><?php echo $page->page_title;?></td>
                          
              
              <td>
              <?php if(config('global.demosite')=="yes"){?>
              <a href="#" class="btn btn-success btndisable">Edit</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
          <?php } else { ?>
              <a href="<?php echo $url;?>/admin/edit-page/{{ $page->page_id }}" class="btn btn-success">Edit</a>
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
