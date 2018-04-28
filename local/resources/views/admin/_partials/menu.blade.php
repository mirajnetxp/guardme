<?php 
  use Illuminate\Support\Facades\Route;
  $currentPaths= Route::getFacadeRoot()->current()->uri();  
  $url = URL::to("/");
?>
<div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
    Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
    Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
  -->

      <div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    Thumbsup
                </a>
            </div>

            <ul class="nav">
                <li class="<?php echo Request::is('admin')?'active':''; ?>"><a href="<?php echo $url;?>/admin"><i class="fa fa-laptop"></i> Dashboard </a></li>
          <li class="<?php echo Request::is('admin/services')?'active':''; ?>"><a href="<?php echo $url;?>/admin/services"><i class="fa fa-cog"></i> Services </a></li>
          <li class="<?php echo Request::is('admin/subservices')?'active':''; ?>"><a href="<?php echo $url;?>/admin/subservices"><i class="fa fa-cog"></i> Sub Services </a></li>
          
          
           <li class="<?php echo Request::is('admin/booking')?'active':''; ?>"><a href="<?php echo $url;?>/admin/booking"><i class="fa fa-book" aria-hidden="true"></i> Booking History </a></li>
           
            <li class="<?php echo Request::is('admin/pending_withdraw')?'active':''; ?>"><a href="<?php echo $url;?>/admin/pending_withdraw"><i class="fa fa-money" aria-hidden="true"></i> Pending Withdrawal </a></li>
          
          <li class="<?php echo Request::is('admin/completed_withdraw')?'active':''; ?>"><a href="<?php echo $url;?>/admin/completed_withdraw"><i class="fa fa-money" aria-hidden="true"></i> Completed Withdrawal </a></li>
          
          <li class="<?php echo Request::is('admin/testimonials')?'active':''; ?>"><a href="<?php echo $url;?>/admin/testimonials"><i class="fa fa-comments"></i> Testimonials </a></li>
          <li class="<?php echo Request::is('admin/referral/items')?'active':''; ?>"><a href="<?php echo $url;?>/admin/referral/items"><i class="fa fa-sticky-cart"></i>Referral Items</a></li>

          <li class="<?php echo Request::is('admin/pages')?'active':''; ?>"><a href="<?php echo $url;?>/admin/pages"><i class="fa fa-sticky-note"></i> Pages </a></li>
          
          <li class="<?php echo Request::is('admin/shop')?'active':''; ?>"><a href="<?php echo $url;?>/admin/shop"><i class="fa fa-shopping-cart"></i> Shop </a></li>
          
          <li class="<?php echo Request::is('admin/settings')?'active':''; ?>"><a href="<?php echo $url;?>/admin/settings"><i class="fa fa-cog"></i> Settings </a></li>
            </ul>
      </div>
    </div>