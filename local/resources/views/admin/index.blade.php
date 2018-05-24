<!DOCTYPE html>
<html lang="en">
  <head>


     @include('admin.title')
    @include('admin.style')

  </head>

  <body>
    <div class="wrapper">
      <div class="sidebar" data-background-color="white" data-active-color="danger">

      <!-- <div class="main_container"> -->
        <div class="sidebar-wrapper">
          <!-- <div class="left_col scroll-view"> -->
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
          <!-- </div> -->
        </div>
      </div>

      <div class="main-panel">
        <!-- top navigation -->
       @include('admin.top')

		<?php $url = URL::to("/"); ?>


        <!-- /top navigation -->

        <!-- page content -->
        <!-- <div class="right_col" role="main"> -->
        <div class="content">
          <div class="container-fluid">
          <!-- top tiles -->
          <div class="row">
            <div class="col-lg-3 col-sm-6">

              <div class="card">
                <div class="content">
                  <div class="row">
                    <div class="col-xs-5">
                        <div class="icon-big icon-warning text-center">
                          <i class="ti-user"></i>
                        </div>
                  </div>
                  <div class="col-xs-7">
                    <div class="numbers">
                      <p>Total Users</p>
                        <?php echo $total_user;?>
                      </div>
                    </div>
                  </div>
                                <!-- <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div> -->
                </div>
            </div>
          </div>
            <div class="col-lg-3 col-sm-6">
              <!-- <span class="count_top"><i class="fa fa-user"></i> Total Sellers</span>
              <div class="count"><?php //echo $total_seller;?></div> -->
              <div class="card">
                <div class="content">
                  <div class="row">
                    <div class="col-xs-5">
                      <div class="icon-big icon-warning text-center">
                        <i class="ti-user"></i>
                      </div>
                    </div>
                    <div class="col-xs-7">
                      <div class="numbers">
                        <p>Freelancers</p>
                          <?php echo $total_seller;?>
                      </div>
                    </div>
                </div>
                                <!-- <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div> -->
              </div>
            </div>

            </div>
            <div class="col-lg-3 col-sm-6">
              <!-- <span class="count_top"><i class="fa fa-user"></i> Total Customers</span>
              <div class="count"><?php //echo $total_customer;?></div> -->
              <div class="card">
                <div class="content">
                  <div class="row">
                    <div class="col-xs-5">
                      <div class="icon-big icon-warning text-center">
                        <i class="ti-user"></i>
                      </div>
                    </div>
                    <div class="col-xs-7">
                      <div class="numbers">
                        <p>Employers</p>
                          <?php echo $total_customer;?>
                      </div>
                    </div>
                </div>
                                <!-- <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div> -->
              </div>
            </div>

            </div>

          </div>
          <!-- /top tiles -->

		  <div style="clear:both;"></div>
		  <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="header">
              <h4 class="title">Last 7 Days Booking Report</h4>
            </div>



		 <script type="text/javascript">
	window.onload = function () {
			var dps = [
		<?php echo $javas;?>
		];

		var chart = new CanvasJS.Chart("chartContainer",
		{


			title:{
				//text: "Last 7 Days Order Report",
				fontSize:20,
				titleFontFamily: "Open Sans, sans-serif"
			},

                        animationEnabled: true,
			axisX:{

				gridColor: "Silver",
				tickColor: "silver"
				//valueFormatString: "DD/MMM"

			},
                        toolTip:{
                          shared:true
                        },
			theme: "theme2",
			axisY: {
				gridColor: "Silver",
				tickColor: "silver"
			},
			legend:{
				verticalAlign: "center",
				horizontalAlign: "right"
			},
			data: [
			{
				type: "line",
				showInLegend: true,
				lineThickness: 2,
				name: "Orders",
				markerType: "square",
				color: "#F08080",
				dataPoints: dps
			}
			],
			axisX: {
        title: "Last 7 days",
       // titleFontFamily: "comic sans ms"
      },
			axisY: {
        title: "No of Booking",
        //titleFontFamily: "comic sans ms"
      },
          legend:{
            cursor:"pointer",
            itemclick:function(e){
              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
              	e.dataSeries.visible = false;
              }
              else{
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
		});

chart.render();
}
</script>


          <div class="content">
        	   <div id="chartContainer" style="height: 300px; width: 100%;">
          </div>
      	</div>
      </div>
    </div>
	</div>




		  <br/><br/>



          <div class="row">


            <div class="col-md-4 col-sm-4 col-xs-12" style="padding:5px;">
              <div class="card" style="padding:12px;">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Recent Booking</h2>

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="widget_summary">


				   <div class="x_content">
                  <table class="" style="width:100%">
                    <tr>
                      <th>
                        <p>Name</p>
                      </th>
                      <th>

                          <p>Booking Date</p>
                       </th>
					   <th>

                          <p>Amount</p>

                      </th>

					  <th>

                          <p>Status</p>

                      </th>
                    </tr>

					<?php foreach($booking as $book){?>
					<tr height="20"></tr>
                    <tr>
                      <td>
                       <?php echo $book->name;?>
                      </td>
                      <td>
                       <?php echo $book->booking_date;?>
                      </td>

					  <td>
                       <?php echo $book->total_amt.' '.$setting[0]->site_currency;?>
                      </td>

					  <td>
                       <?php echo $book->status;?>
                      </td>
                    </tr>
					<?php } ?>
                  </table>
                </div>

                  </div>






                </div>
              </div>
            </div>
            </div>




			<div class="col-md-4 col-sm-4 col-xs-12" style="padding:5px;">
        <div class="card" style="padding:12px;">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Latest Users</h2>

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="widget_summary">


				   <div class="x_content">
                  <table class="" style="width:100%">
                    <tr>
					<th>

                          <p>Photo</p>

                      </th>

                      <th>
                        <p>Name</p>
                      </th>
                      <th>

                          <p>Phone</p>
                       </th>
					   <th>

                          <p>User Type</p>

                      </th>


                    </tr>

					<?php foreach($users as $user){
						$sta=$user->admin; if($sta==1){ $viewst="Admin"; } else if($sta==2) { $viewst="Seller"; } else if($sta==3) { $viewst="Licensed Partner"; } else if($sta==0) { $viewst="Customer"; }
						?>
					<tr height="10"></tr>
                    <tr>
                      <?php
					   $userphoto="/userphoto/";
						$path ='/local/images'.$userphoto.$user->photo;
						if($user->photo!=""){
						?>
						 <td><img src="<?php echo $url.$path;?>" class="thumb" width="40"></td>
						 <?php } else { ?>
						  <td><img src="<?php echo $url.'/local/images/nophoto.jpg';?>" class="thumb" width="40"></td>
						 <?php } ?>
                      <td>
                       <?php echo $user->name;?>
                      </td>

					  <td>
                      <?php echo $user->phone;?>
                      </td>

					  <td>
                       <?php echo $viewst;?>
                      </td>
                    </tr>
					<?php } ?>
                  </table>
                </div>

                  </div>






                </div>
              </div>
            </div>
            </div>









		   <div class="col-md-4 col-sm-4 col-xs-12" style="padding:5px;">
         <div class="card" style="padding:12px;">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Top Testimonials</h2>

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <div class="widget_summary">


				   <div class="x_content">
                  <table class="" style="width:100%">
                    <tr>
					<th style="width:25%">

                          <p>Photo</p>

                      </th>

                      <th style="width:25%">
                        <p>Name</p>
                      </th>
                      <th>

                          <p>Description</p>
                       </th>



                    </tr>

					<?php foreach($testimonials as $testimonial){
						?>
					<tr height="20"></tr>
                    <tr>
                      <?php
					   $testimonialphoto="/testimonialphoto/";
						$path ='/local/images'.$testimonialphoto.$testimonial->image;
						if($testimonial->image!=""){
						?>
						 <td><img src="<?php echo $url.$path;?>" class="thumb" width="40"></td>
						 <?php } else { ?>
						  <td><img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="40"></td>
						 <?php } ?>
                      <td>
                       <?php echo $testimonial->name;?>
                      </td>

					  <td>
                      <?php echo substr($testimonial->description,0,40);?>
                      </td>


                    </tr>
					<?php } ?>
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
        <!-- /page content -->

      @include('admin.footer')

    </div>
  </div>


  </body>
</html>
