 
<!DOCTYPE html>
<html lang="en">
<head>

    

   @include('style')
	
<style type="text/css">
.noborder ul,li { margin:0; padding:0; list-style:none;}
.noborder .label { color:#000; font-size:16px;}
</style>



</head>
<body>

    <?php $url = URL::to("/"); ?>

    <!-- fixed navigation bar -->
    @include('header')

    <section class="job-bg page job-list-page">
		<div class="container">
			<div class="breadcrumb-section">
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="{{URL::to('/')}}">Home</a></li>
					<li>Search</li>
				</ol><!-- breadcrumb -->						
				<h2 class="title">Search</h2>
			</div>

			<div class="banner-form banner-form-full job-list-form">
				<form method="POST" action="{{ route('shopsearch') }}" id="formID">
					<!-- category-change -->
					<div class="dropdown category-dropdown">					{!! csrf_field() !!}	
						<a data-toggle="dropdown" href="#"><span class="change-text">Category</span> <i class="fa fa-angle-down"></i></a>
						<ul class="dropdown-menu category-change">
							<li><a href="#">Customer Service</a></li>
							<li><a href="#">Software Engineer</a></li>
							<li><a href="#">Program Development</a></li>
							<li><a href="#">Project Manager</a></li>
							<li><a href="#">Graphics Designer</a></li>
						</ul>								
					</div><!-- category-change -->
					
					<!-- language-dropdown -->
					<div class="dropdown category-dropdown language-dropdown">
						<a data-toggle="dropdown" href="#"><span class="change-text">Location</span> <i class="fa fa-angle-down"></i></a>
						<ul class="dropdown-menu category-change language-change">
							<li><a href="#">Location 1</a></li>
							<li><a href="#">Location 2</a></li>
							<li><a href="#">Location 3</a></li>
						</ul>								
					</div><!-- language-dropdown -->
				
					<input type="text" class="form-control" placeholder="Security Personnel">
					<button type="submit" class="btn btn-primary" value="Search">Search</button>
				</form>
			</div>
			
			<div class="category-info">	
				<div class="row">
					<div class="col-md-3 col-sm-4">
						<div class="accordion">
							<!-- panel-group -->
							<div class="panel-group" id="accordion">
							 	
								<!-- panel -->
								<div class="panel panel-default panel-faq">
									<!-- panel-heading -->
									<div class="panel-heading">
										<div  class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#accordion-one">
												<h4>Gender<span class="pull-right"><i class="fa fa-minus"></i></span></h4>
											</a>
										</div>
									</div><!-- panel-heading -->

									<div id="accordion-one" class="panel-collapse collapse in">
										<!-- panel-body -->
										<div class="panel-body">
											
										</div><!-- panel-body -->
									</div>
								</div>
							</div>

							<!-- panel -->
								<div class="panel panel-default panel-faq">
									<!-- panel-heading -->
									<div class="panel-heading">
										<div  class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#accordion-three">
												<h4>Available<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
											</a>
										</div>
									</div><!-- panel-heading -->

									<div id="accordion-three" class="panel-collapse collapse">
										<!-- panel-body -->
										<div class="panel-body">
											
										</div><!-- panel-body -->
									</div>
								</div>

								<!-- panel -->
								<div class="panel panel-default panel-faq">
									<!-- panel-heading -->
									<div class="panel-heading">
										<div class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#accordion-four">
												<h4>Employment Type<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
											</a>
										</div>
									</div><!-- panel-heading -->

									<div id="accordion-four" class="panel-collapse collapse">
										<!-- panel-body -->
										<div class="panel-body">
											<label for="full-time"><input type="checkbox" name="full-time" id="full-time"> Full Time</label>
											<label for="part-time"><input type="checkbox" name="part-time" id="part-time"> Part Time</label>
											<label for="contractor"><input type="checkbox" name="contractor" id="contractor"> Contractor</label>
											<label for="intern"><input type="checkbox" name="intern" id="intern"> Intern</label>
											<label for="seasonal"><input type="checkbox" name="seasonal" id="seasonal"> Seasonal / Temp</label>
										</div><!-- panel-body -->
									</div>
								</div>
								<!-- panel -->
								<div class="panel panel-default panel-faq">
									<!-- panel-heading -->
									<div class="panel-heading">
										<div class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#accordion-five">
												<h4>Star rating<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
											</a>
										</div>
									</div><!-- panel-heading -->

									<div id="accordion-five" class="panel-collapse collapse">
										<!-- panel-body -->
										<div class="panel-body">
											
										</div><!-- panel-body -->
									</div>
								</div>
								<!-- panel -->
								<div class="panel panel-default panel-faq">
									<!-- panel-heading -->
									<div class="panel-heading">
										<div class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#accordion-six">
												<h4>Distance to<span class="pull-right"><i class="fa fa-plus"></i></span></h4>
											</a>
										</div>
									</div><!-- panel-heading -->

									<div id="accordion-six" class="panel-collapse collapse">
										<!-- panel-body -->
										<div class="panel-body">
											<input type="text" placeholder="Postcode" class="form-control">
										</div><!-- panel-body -->
									</div>
								</div> 
						</div>
					</div>


					<!-- recommended-ads -->
					<div class="col-sm-8 col-md-7">				
						<div class="section job-list-item">
							<div class="featured-top">
								<h4>Showing 1-25 of 65,712 ads</h4>
								<div class="dropdown pull-right">
									<div class="dropdown category-dropdown">
										<h5>Sort by:</h5>						
										<a data-toggle="dropdown" href="#"><span class="change-text">Most Relevant</span><i class="fa fa-caret-square-o-down"></i></a>
										<ul class="dropdown-menu category-change">
											<li><a href="#">Most Relevant</a></li>
											<li><a href="#">Most Popular</a></li>
										</ul>								
									</div><!-- category-change -->		
								</div>							
							</div><!-- featured-top -->	

<?php if(!empty($search_text)){?>
	
	
	<?php if(!empty($count)){?>
	
			<?php foreach($subsearches as $shop){ ?>
				
		<div class="job-ad-item">
			<div class="item-info">
				<div class="item-image-box">
					<div class="item-image">
						<?php 
						$shopphoto="/shop/";
						$npaths ='/local/images'.$shopphoto.$shop->profile_photo;
						if($shop->profile_photo!=""){?>
       						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.$npaths;?>" class="img-responsive"></a>
						<?php } else { ?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img align="center" class="img-responsive" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Profile Photo"/></a>
						<?php } ?>
							

						
					</div><!-- item-image -->
				</div>
					<?php 				
						if($shop->start_time>12)
						{
							$start=$shop->start_time-12;
							$stime=$start."PM";
						}
						else
						{
							$stime=$shop->start_time."AM";
						}
						if($shop->end_time>12)
						{
							$end=$shop->end_time-12;
							$etime=$end."PM";
						}
						else
						{
							$etime=$shop->end_time."AM";
						}
					?>
				<div class="ad-info">
					<span><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" class="title"><?php echo $shop->shop_name; ?></a> </span>
					<div class="ad-meta">
						<ul>
							<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $shop->city; ?> </a></li>
							<li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $stime; ?> - <?php echo $etime; ?></a></li>
							<!-- <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i>$25,000 - $35,000</a></li> -->
						</ul>
					</div><!-- ad-meta -->									
				</div><!-- ad-info -->
			</div><!-- item-info -->
		</div>
	
			<?php } ?>
	
		<?php } ?>

	<?php if(empty($count)){?>
	
	<div class="col-md-12 noservice" align="center">No services found!</div>
	
	<?php } ?>
		<?php } if(empty($search_text) && empty($sub_value)) { ?>

			<?php foreach($shopview as $shop){?>

					<div class="job-ad-item">
						<div class="item-info">
							<div class="item-image-box">
								<div class="item-image">
								<?php 
									$shopphoto="/shop/";
									$npaths ='/local/images'.$shopphoto.$shop->profile_photo;
									if($shop->profile_photo!=""){?>
			       						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.$npaths;?>" class="img-responsive"></a>
									<?php } else { ?>
									<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img align="center" class="img-responsive" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Profile Photo"/></a>
									<?php } ?>
							
								</div><!-- item-image -->
							</div>
								<?php 				
									if($shop->start_time>12)
									{
										$start=$shop->start_time-12;
										$stime=$start."PM";
									}
									else
									{
										$stime=$shop->start_time."AM";
									}
									if($shop->end_time>12)
									{
										$end=$shop->end_time-12;
										$etime=$end."PM";
									}
									else
									{
										$etime=$shop->end_time."AM";
									}
								?>
							<div class="ad-info">
								<span><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" class="title"><?php echo $shop->shop_name; ?></a> </span>
								<div class="ad-meta">
									<ul>
										<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $shop->city; ?> </a></li>
										<li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $stime; ?> - <?php echo $etime; ?></a></li>
										<!-- <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i>$25,000 - $35,000</a></li> -->
									</ul>
								</div><!-- ad-meta -->									
							</div><!-- ad-info -->
						</div><!-- item-info -->
					</div>

				<?php } ?>
			<?php } ?>
			<?php if(!empty($sub_value)){?>
				<?php foreach($subsearches as $shop){ ?>
						<div class="job-ad-item">
							<div class="item-info">
								<div class="item-image-box">
									<div class="item-image">
										<?php 
											$shopphoto="/shop/";
											$npaths ='/local/images'.$shopphoto.$shop->profile_photo;
											if($shop->profile_photo!=""){?>
					       						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.$npaths;?>" class="img-responsive"></a>
											<?php } else { ?>
											<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img align="center" class="img-responsive" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Profile Photo"/></a>
											<?php } ?>
							
									</div><!-- item-image -->
								</div>
									<?php 				
										if($shop->start_time>12)
										{
											$start=$shop->start_time-12;
											$stime=$start."PM";
										}
										else
										{
											$stime=$shop->start_time."AM";
										}
										if($shop->end_time>12)
										{
											$end=$shop->end_time-12;
											$etime=$end."PM";
										}
										else
										{
											$etime=$shop->end_time."AM";
										}
									?>
								<div class="ad-info">
									<span><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" class="title"><?php echo $shop->shop_name; ?></a> </span>
									<div class="ad-meta">
										<ul>
											<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $shop->city; ?> </a></li>
											<li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo $stime; ?> - <?php echo $etime; ?></a></li>
											<!-- <li><a href="#"><i class="fa fa-money" aria-hidden="true"></i>$25,000 - $35,000</a></li> -->
										</ul>
									</div><!-- ad-meta -->									
								</div><!-- ad-info -->
							</div><!-- item-info -->
						</div>

					<?php } ?>
					<?php } ?>	

					<!-- pagination  -->
							<div class="text-center">
								<ul class="pagination ">
									<li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
									<li><a href="#">1</a></li>
									<li class="active"><a href="#">2</a></li>
									<li><a href="#">3</a></li>
									<li><a href="#">4</a></li>
									<li><a href="#">5</a></li>
									<li><a>...</a></li>
									<li><a href="#">10</a></li>
									<li><a href="#">20</a></li>
									<li><a href="#">30</a></li>
									<li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
								</ul>
							</div><!-- pagination  -->			
						</div>
					</div>


				<div class="col-md-2 hidden-xs hidden-sm">
						<div class="advertisement text-center">
							<a href="#"><img src="images/ads/1.jpg" alt="" class="img-responsive"></a>
						</div>
					</div>



				</div>
			</div>


		</div>
	</section>
    

      @include('footer')
</body>
</html>