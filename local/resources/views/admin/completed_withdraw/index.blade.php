@extends('admin/layout/default')
@section('title', 'Completed Withdrawal')
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
                  <h4 class="title">Completed Withdrawal</h4>
                </div>
                
          <div align="right">
           
                  <div class="x_content">
                   
                  <div class="table-responsive">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>SNo</th>
                      <th>Shop Name</th>
                      <th>Email</th>
                      <th>Mobile No</th>
                      <th>User Id</th>
                      <th>Shop Id</th>
                      <th>Withdraw Amt</th>
                      <th>Withdraw Mode</th>
                      <th>Paypal Id</th>
                      <th>Bank Acc No</th>
                      <th>Bank Info</th>
                      <th>IFSC Code</th>
                      <th>Status</th>
                      
              
              
              
                          
                        </tr>
                      </thead>
                      <tbody>
            <?php 
            $sno=0;
            foreach ($withdraw as $draw) {
              $sno++;
          


          
            ?>
    
            
                        <tr>
             <td><?php echo $sno; ?></td>
             
                          <td><?php echo $draw->shop_name;?></td>
                          
              <td><?php echo $draw->email;?></td>
              
               <td><?php echo $draw->shop_phone_no;?></td>
               
               
               
               
               <td><?php echo $draw->id;?></td>
               
               <td><?php echo $draw->withdraw_shop_id;?></td>
               
               <td><?php echo $draw->withdraw_amt.' '.$setting[0]->site_currency;?></td>
               
               <td><?php echo $draw->withdraw_mode;?></td>
               
               <td><?php echo $draw->paypal_id;?></td>
               
               <td><?php echo $draw->bank_acc_no;?></td>
               
               <td><?php echo $draw->bank_info;?></td>
               
               <td><?php echo $draw->ifsc_code;?></td>
               
                <td><?php echo $draw->withdraw_status;?></td>
               
               
               
              
             
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
