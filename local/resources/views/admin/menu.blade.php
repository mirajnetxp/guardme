<?php
use Illuminate\Support\Facades\Route;
$currentPaths = Route::getFacadeRoot()->current()->uri();
$url = URL::to( "/" );

/*$ncounts = DB::table('users')->get();
		foreach($ncounts as $well)
		{
			$we = $well->id;
			$ewe = $well->email;
			DB::update('update shop set user_id="'.$we.'" where seller_email = ?', [$ewe]);
		}*/
?>
<ul class="nav">
    <li><a href="<?php echo $url;?>/admin"><i class="ti-panel"></i>
            <p> Dashboard </p></a></li>
    <li>
        <a href="<?php echo $url;?>/admin/users"><i class="ti-user"></i>
            <p> Users </p></a>
    </li>
    <li><a href="<?php echo $url;?>/admin/payments/employer"><i class="fa fa-money" aria-hidden="true"></i>
            <p>Refund</p></a></li>
    <li>
        <a href="<?php echo $url;?>/admin/Verification"><i class="ti-check"></i>
            <p>Verification</p></a></li>
<!-- <li><a href="<?php echo $url;?>/admin/services"><i class="ti-settings"></i> <p>Services</p> </a></li> -->
<!-- <li><a href="<?php echo $url;?>/admin/subservices"><i class="ti-settings"></i><p> Sub Services</p> </a></li> -->
<!-- <li><a href="<?php echo $url;?>/admin/booking"><i class="ti-book"></i> <p>Booking History </p></a></li> -->
<!-- <li><a href="<?php echo $url;?>/admin/pending_withdraw"><i class="ti-money" ></i><p> Pending Withdrawal </p></a></li> -->
<!-- <li><a href="<?php echo $url;?>/admin/completed_withdraw"><i class="ti-money"></i><p> Completed Withdrawal </p></a></li> -->
<!-- <li><a href="<?php echo $url;?>/admin/testimonials"><i class="ti-comments"></i> <p>Testimonials </p></a></li> -->
    <li><a href="<?php echo $url;?>/admin/referral/items"><i class="ti-gift"></i>
            <p>Loyalty</p></a></li>
    <li><a href="<?php echo $url;?>/admin/pages"><i class="ti-files"></i>
            <p> Pages</p></a></li>
    <li><a href="<?php echo $url;?>/admin/jobs">
            <i class="fa fa-jpy"></i>
            <p> Jobs</p></a>
    </li>
    <li><a href="<?php echo $url;?>/admin/Payment">
            <i class="fa fa-money"></i>
            <p> Payment</p></a>
    </li>
    <li><a href="<?php echo $url;?>/admin/shop"><i class="ti-briefcase"></i>
            <p> Companies </p></a></li>
    <li><a href="<?php echo $url;?>/admin/message"><i class="fa fa-envelope" aria-hidden="true"></i>
            <p>Message </p></a></li>
    <li><a href="<?php echo $url;?>/admin/newsletter"><i class="fa fa-newspaper-o" aria-hidden="true"></i>
            <p>Newsletter </p></a></li>
    <li><a href="<?php echo $url;?>/admin/settings"><i class="ti-settings"></i>
            <p>Settings </p></a></li>
</ul>
