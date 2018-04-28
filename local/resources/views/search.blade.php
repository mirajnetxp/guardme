 
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

    <!-- slider -->
    

	
    
	
	
	
	
	
	
	
	<div class="clearfix"></div>
	
	
	
	
	
	<div class="video">
	<div class="clearfix"></div>
	<div class="headerbg">
	 <div class="col-md-12" align="center"><h1>Search</h1></div>
	 </div>
	<div class="container">
	
	 <div class="height30"></div>
	 
	 
	<div class="container">
	<div class="row">
	<form class="form-horizontal" role="form" method="POST" action="{{ route('shopsearch') }}" id="formID" enctype="multipart/form-data">
   <div class="col-md-12">
   {!! csrf_field() !!}
   
   
   
   
   
	<div class="col-md-5 swidth noborder" >
	
		<?php //if(!empty($serid[0]->subid)){ echo $serid[0]->subid; }
		
		?>
			
				<select name="langOpt[]" multiple id="langOpt" class="validate[required]">
				<?php foreach($viewservices as $service){
					$sel=explode(",",$service->subid);
						$lev=count($sel);
					?>
                <option value="<?php echo $service->subid;?>" <?php  if(!empty($services[0]->subid)){ if($service->subid==$services[0]->subid){ echo "selected"; } }?>><?php echo $service->subname;?></option>
                <?php } ?>
                </select>
	
	</div>
	
	
	
	<div class="col-md-5 swidth nocity">	
		
		<input type="text"  name="" id="" class="form-control" placeholder="Enter City" value="<?php //echo $setting[0]->site_currency;?>">
	</div>	
	
	
	
	
	
	<div class="col-md-2 custobtn">
		                       
							   
                                <button type="submit" class="btn btn-success btn-md">
                                    Filter
                                </button>
                           
		</div>
	
	
	
	</div>
	
	
	
	
	
	</form>
	
	</div>
	
	
	
	</div>
	
	</div>
	<div class="notopborder"></div>
	<div class="container">
	
	<div class="container">
	
	
	<?php if(!empty($search_text)){?>
	
	
	<?php if(!empty($count)){?>
	
	 
	
	
	<div class="row">
	<div class="clearfix"></div>
	<?php foreach($subsearches as $shop){ 
	
	?>
	
	
	<div class="col-md-3">
		<div class="shop-list-page">
			<div class="shop_pic">
			<?php 
					   $shopphoto="/shop/";
						$paths ='/local/images'.$shopphoto.$shop->cover_photo;
						if($shop->cover_photo!=""){
						?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.$paths;?>" class="img-responsive imgservice"></a>
						<?php } else { ?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.'/local/images/no-image-big.jpg';?>" class="img-responsive imgservice"></a>
						<?php } ?>
			</div>
			<div class="col-lg-12 imgthumb">
			<?php 
						$npaths ='/local/images'.$shopphoto.$shop->profile_photo;
						if($shop->profile_photo!=""){?>
        <img align="center" class="sthumb" src="<?php echo $url.$npaths;?>" alt="Profile Photo"/>
						<?php } else { ?>
						<img align="center" class="sthumb" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Profile Photo"/>
						<?php } ?>
			</div>
			
			<?php
		if($shop->rating=="")
		{
			$starpath = '/local/images/nostar.png';
		}
		else {
		$starpath = '/local/images/'.$shop->rating.'star.png';
		}
		?>
			<div class="col-lg-12 shop_content">
				<h4 class="sv_shop_style"><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><?php echo $shop->shop_name; ?></a></h4>
				
				<img src="<?php echo $url.$starpath;?>" alt="rated <?php if($shop->rating==""){ echo "0"; } else { echo $shop->rating; }?> stars" class="star_rates" />
				<h5><span class="lnr lnr-map-marker"></span>&nbsp;<?php echo $shop->city; ?></h5>
				
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
				<h5><span class="lnr lnr-clock"></span>&nbsp; <?php echo $stime; ?> - <?php echo $etime; ?></h5>
				
				
				
							
				<div align="center"><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" class="btn btn-success radiusoff">View Profile & Book</a></div>
			</div> 
			
			
	    </div>
	</div>	
	
	
	
	
	
	<?php } ?>
	</div>
	
	
	
	
	

	
	
	<?php } ?>
	
	
	
	
	
	
	<?php if(empty($count)){?>
	
	<div class="col-md-12 noservice" align="center">No services found!</div>
	
	<?php } ?>
	
	
	

	
	
	
	
	<?php } if(empty($search_text) && empty($sub_value)) { ?>
	
	
	
	
	<div class="row">
	<div class="clearfix"></div>
	<?php foreach($shopview as $shop){

	?>
	
	
	<div class="col-md-3">
		<div class="shop-list-page">
			<div class="shop_pic">
			<?php 
					   $shopphoto="/shop/";
						$paths ='/local/images'.$shopphoto.$shop->cover_photo;
						if(!empty($shop->cover_photo)){
						?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.$paths;?>" class="img-responsive imgservice"></a>
						<?php } else { ?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.'/local/images/no-image-big.jpg';?>" class="img-responsive imgservice"></a>
						<?php } ?>
			</div>
			
			<div class="col-lg-12 imgthumb">
			<?php 
						$npaths ='/local/images'.$shopphoto.$shop->profile_photo;
						if(!empty($shop->profile_photo)){?>
        <img align="center" class="sthumb" src="<?php echo $url.$npaths;?>" alt="Profile Photo"/>
						<?php } else { ?>
						<img align="center" class="sthumb" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Profile Photo"/>
						<?php } ?>
			</div>
			
			
			
			<?php
		if($shop->rating=="")
		{
			$starpath = '/local/images/nostar.png';
		}
		else {
		$starpath = '/local/images/'.$shop->rating.'star.png';
		}
		?>
			<div class="col-lg-12 shop_content">
				<h4 class="sv_shop_style"><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><?php echo $shop->shop_name; ?></a></h4>
				<img src="<?php echo $url.$starpath;?>" alt="rated <?php if($shop->rating==""){ echo "0"; } else { echo $shop->rating; }?> stars" class="star_rates" />
				<h5><span class="lnr lnr-map-marker"></span>&nbsp;<?php echo $shop->city; ?></h5>
				
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
				<h5><span class="lnr lnr-clock"></span>&nbsp; <?php echo $stime; ?> - <?php echo $etime; ?></h5>
							
				<div align="center"><a href="vendor/<?php echo $shop->name;?>" class="btn btn-success radiusoff">View Profile & Book</a></div>
			</div> 
			
			
	    </div>
	</div>	
	
	
	
	
	
	<?php } ?>
	</div>
	
	
	
	
	
	
	
	
	
	
	
	<?php } ?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<?php if(!empty($sub_value)){?>
	
	
	
	
	<div class="row">
	<div class="clearfix"></div>
	<?php foreach($subsearches as $shop){ 
	
	?>
	
	
	<div class="col-md-3">
		<div class="shop-list-page">
			<div class="shop_pic">
			<?php 
					   $shopphoto="/shop/";
						$paths ='/local/images'.$shopphoto.$shop->cover_photo;
						if($shop->cover_photo!=""){
						?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.$paths;?>" class="img-responsive imgservice"></a>
						<?php } else { ?>
						<a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><img src="<?php echo $url.'/local/images/no-image-big.jpg';?>" class="img-responsive imgservice"></a>
						<?php } ?>
			</div>
			<div class="col-lg-12 imgthumb">
			<?php 
						$npaths ='/local/images'.$shopphoto.$shop->profile_photo;
						if($shop->profile_photo!=""){?>
        <img align="center" class="sthumb" src="<?php echo $url.$npaths;?>" alt="Profile Photo"/>
						<?php } else { ?>
						<img align="center" class="sthumb" src="<?php echo $url.'/local/images/nophoto.jpg';?>" alt="Profile Photo"/>
						<?php } ?>
			</div>
			
			<?php
		if($shop->rating=="")
		{
			$starpath = '/local/images/nostar.png';
		}
		else {
		$starpath = '/local/images/'.$shop->rating.'star.png';
		}
		?>
			<div class="col-lg-12 shop_content">
				<h4 class="sv_shop_style"><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" ><?php echo $shop->shop_name; ?></a></h4>
				
				<img src="<?php echo $url.$starpath;?>" alt="rated <?php if($shop->rating==""){ echo "0"; } else { echo $shop->rating; }?> stars" class="star_rates" />
				<h5><span class="lnr lnr-map-marker"></span>&nbsp;<?php echo $shop->city; ?></h5>
				
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
				<h5><span class="lnr lnr-clock"></span>&nbsp; <?php echo $stime; ?> - <?php echo $etime; ?></h5>
				
				
				
							
				<div align="center"><a href="<?php echo $url; ?>/vendor/<?php echo $shop->name;?>" class="btn btn-success radiusoff">View Profile & Book</a></div>
			</div> 
			
			
	    </div>
	</div>	
	
	
	
	
	
	<?php } ?>
	</div>
	
	
	
	
	
	
	
	<?php } ?>
	
	
	
	
	
	</div>
	
	
	
	
	</div>
	
	</div>
	</div>
	
	
	

      <div class="clearfix"></div>
	   <div class="clearfix"></div>

      @include('footer')
</body>
</html>