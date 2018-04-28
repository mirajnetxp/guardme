@extends('admin/layout/default')
@section('title', 'Booking')
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
                  <h4 class="title">Sub Services</h4>
                </div>
                
          <div align="right">
           
                  <div class="x_content">
                   
                  <div class="table-responsive">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Sno</th>
                          <th>Shop Name</th>
                          <th>Service Name</th>
                          <th>Booking Date</th>
              
                          <th>Booking Time</th>
                          <th>User Phone No</th>
                          <th>Username</th>
                          <th>Email</th>
              
                          <th>Address</th>
                          <th>City</th>
                          <th>Pincode</th>
                          
                          <th>Total Amount</th>
                          <th>Payment Mode</th>
                          
                           <th>Status</th>
                          
                          
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
            <?php 
            $sno=0;
            foreach ($booking as $viewbook) {
              $sno++;
          $booking_time=$viewbook->booking_time;
              if($booking_time>12)
              {
                $final_time=$booking_time-12;
                $final_time=$final_time."PM";
              }
              else
              {
                $final_time=$booking_time."AM";
              }


          $ser_id=$viewbook->services_id;
      $sel=explode("," , $ser_id);
      $lev=count($sel);
      $ser_name="";
      $sum="";
      $price="";    
    for($i=0;$i<$lev;$i++)
      {
        $id=$sel[$i]; 
                
        
        
        $fet1 = DB::table('subservices')
                 ->where('subid', '=', $id)
                 ->get();
        $ser_name.=$fet1[0]->subname.'<br>';
        $ser_name.=",";        
        
        
        
        $ser_name=trim($ser_name,",");
        
      }   
      
      $bookid= $viewbook->book_id;
      $newbook = DB::table('booking')
                 ->where('book_id', '=', $bookid)
                 ->get();

            ?>
    
            
                        <tr>
             <td><?php echo $sno; ?></td>
             
                          <td><?php echo $viewbook->shop_name;?></td>
                          
              <td><?php echo $ser_name;?></td>
              
               <td><?php echo $viewbook->booking_date;?></td>
               
               
               
               
               <td><?php echo $final_time;?></td>
               
               <td><?php echo $viewbook->phone;?></td>
               
               <td><?php echo $viewbook->name;?></td>
               
               <td><?php echo $viewbook->user_email;?></td>
               
               
               <td><?php echo $viewbook->booking_address;?></td>
               
               <td><?php echo $viewbook->booking_city;?></td>
               
               <td><?php echo $viewbook->booking_pincode;?></td>
               
               
               <td><?php echo $viewbook->total_amt.' '.$setting[0]->site_currency;?></td>
               
               <td><?php echo $viewbook->payment_mode;?></td>
               
               <?php if($newbook[0]->status=="pending"){ $color="#FB6704"; } else if($newbook[0]->status=="paid")  { $color="#0DE50D"; }?> 
               <td style="color:<?php echo $color;?>;"><?php echo $newbook[0]->status;?></td>
              
              <td>
          <?php if(config('global.demosite')=="yes"){?>
            <a href="#" class="btn btn-danger btndisable">Delete</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
          <?php } else { ?>   
             <a href="<?php echo $url;?>/admin/booking/{{ $viewbook->book_id }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
          <?php } ?>
              
              </td>
                        </tr>
                        <?php } ?>
                       
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
                <div class="row">
                    
                    
                    
                </div>
            </div>
       
        <script type="text/javascript">
          $(document).ready(function() {
              $('#datatable-responsive').DataTable();
          });
        </script>
@endsection
