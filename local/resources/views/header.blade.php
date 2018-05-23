<?php
use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();
//var_dump(Route::current()->uri());
//dd(Route::current()->uri());
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();                
$new_email = '';                
if(isset(Auth::user()->verification)){
    $new_email = Auth::user()->verification->new_email;
}
?>

 <header id="header" class="clearfix">

@if($isVerified = (Auth::check() && ! Auth::user()->verified && ! session('need_email_confirmation')))
<div class="alert alert-danger" role="alert" style="position: fixed;top: 0px;left: 0px;width: 100%;z-index:9999;border-radius:0px;padding:5px;">
	<div class="container">
		<div class="pull-left" style="margin-top: 8px">
			A confirmation email was sent to <strong>{{ $changedEmail = $new_email ?: Auth::user()->email }}</strong>.

			@if($new_email)
				Your email will not be changed until you confirm!
			@else
				Please check your inbox and verify your email address.
			@endif
		</div>
		<div class="pull-right"><a href="{!! route('user.resend_verification') !!}" class="btn btn-default">Resend confirmation</a></div>
	</div>
</div>
@endif


<nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>

          <a class="navbar-brand" href="<?php echo $url;?>">
		   <?php if(!empty($setts[0]->site_logo)){?>

		  <img src="<?php echo $url.'/local/images/settings/'.$setts[0]->site_logo;?>"  class="img-responsive" alt="<?php echo $setts[0]->site_name;?>" />
		   <?php } else {?>
		   <?php echo $setts[0]->site_name;?>
		   <?php } ?>
		  </a>
        </div>

        <div class="navbar-left">
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav">
		  	<!--<li class="active"><a href="#">Join as a pro</a></li>-->
            <?php if (Auth::guest()) {?>
				<!-- Added by Ninja 20180331 start here-->
				<li class="@if(Route::current()->uri()=='/') {{ 'active' }} @endif"><a href="<?php echo $url;?>/">Home</a></li>
				<li class="@if(Route::current()->uri()=='search') {{ 'active' }} @endif"><a href="<?php echo $url;?>/search">Hire Security</a></li>
				<li><a href="{{ route('find.jobs') }}">Find Jobs</a></li>
				
				<!-- Added by Ninja 20180331 end here-->
           		
            <?php } else { ?>
            <?php
                $sellmail = Auth::user()->email;
                $shcount = DB::table('shop')
                        ->where('seller_email', '=',$sellmail)
                        ->count();
            ?>
			<!-- Added by Ninja 20180331 start here-->
				<li class="@if(Route::current()->uri()=='/') {{ 'active' }} @endif"><a href="<?php echo $url;?>/">Home</a></li>
                <li class="@if(Route::current()->uri()=='addcompany') {{ 'active' }} @endif"><a href="<?php if($shcount == 0 && Auth::user()->admin == 0){?><?php echo $url;?>/addcompany<?php } else { ?><?php echo $url;?>/account<?php } ?>">Dashboard</a></li>
				<li><a href="<?php echo $url;?>/search">Hire Security</a></li>
				<li><a href="{{ route('find.jobs') }}">Find Jobs</a></li>
				
							
			<!-- Added by Ninja 20180331 end here-->
			
			<?php } ?>
          </ul>
        </div> <!-- /.nav-collapse -->
        </div>
		

        <div class="nav-right">				
			<ul class="sign-in">
				<?php if (Auth::guest()) {?>
				<li><i class="fa fa-user"></i></li>
				<li><a href="<?php echo $url;?>/login">Login</a></li>
            	<li><a href="<?php echo $url;?>/register">Sign Up</a></li>
            	
            	<?php }else{?>
			
			<li><a href="{{url('jobs/saved')}}"><i class="fa fa-heart-o"></i></a></li>
			<li><a href="<?php echo $url;?>/#"><i class="fa fa-envelope-o"></i></a></li>
			<li><a href="<?php echo $url;?>/#"><i class="fa fa-bell-o"></i></a></li>
			<li class="dropdown li-last">
              <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">  Hi, {{ Auth::user()->name }}<b class="caret"></b></a>
              <ul class="dropdown-menu">
               <?php if(Auth::check()) { ?>
                <?php if(Auth::user()->admin==1) {?>

								<li><a href="{{ url('admin/') }}" target="_blank">Admin Dashboard</a></li>
						<?php } ?>

						<?php if(Auth::user()->admin==0) {?>
						<!-- <li><a href="<?php echo $url;?>/dashboard">My Dashboard</a></li> -->
						<li><a href="{{url('/wallet')}}">Wallet</a></li>
						<!-- <li><a href="<?php echo $url;?>/dashboard">Freelancer Profile</a></li>
						<li><a href="<?php echo $url;?>/my_bookings">My Bookings</a></li> -->
						<?php } ?>


			    <?php if(Auth::user()->admin==2) {

					$sellmail = Auth::user()->email;
			    	 $shcount = DB::table('shop')
					 ->where('seller_email', '=',$sellmail)
					 ->count();
					?>
                                <!--
						<li><a href="<?php echo $url;?>/dashboard">Freelancer Profile</a></li>
						<li><a href="<?php echo $url;?>/my_bookings">My Bookings</a></li>

                                /services to menu changed the URL to /post-job

                                <li <?php if(empty($shcount)){?>class="disabled"<?php } ?>><a href="<?php echo $url;?>/post-job" <?php if(empty($shcount)){?>class="disabled"<?php } ?>>Post job</a></li>
                                -->
						<!--
						<li><a href="<?php echo $url;?>/myorder">My Order</a></li>
						<li><a href="<?php if(empty($shcount)){?><?php echo $url;?>/addshop<?php } else { ?><?php echo $url;?>/shop<?php } ?>">My Shop</a></li>
						<li <?php if(empty($shcount)){?>class="disabled"<?php } ?>><a href="<?php echo $url;?>/services" <?php if(empty($shcount)){?>class="disabled"<?php } ?>>My Services</a></li>
						<li <?php if(empty($shcount)){?>class="disabled"<?php } ?>><a href="<?php echo $url;?>/gallery" <?php if(empty($shcount)){?>class="disabled"<?php } ?>>Shop Gallery</a></li>
						-->
						<li><a href="{{url('/wallet')}}">Wallet</a></li>

								<?php } ?>
						<li><a href="<?php echo $url;?>/support/tickets">Support</a></li>
							<?php } ?>
		                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout</a></li>
		                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		                                            {{ csrf_field() }}
		                                        </form>
              </ul>
            </li>
            
            	<?php } ?>
			</ul><!-- sign-in -->					
			@if(Auth::guest() || Auth::user()->admin==0)
				<a href="{{URL::route('job.create')}}" class="btn nav-btn">Post a Job</a>
			@endif
			
		</div>
       
      </div> <!-- /.container -->
    </nav> <!-- /.navbar -->

@if($isVerified)
	<style type="text/css">
		body {
			padding-top:46px;
		}
		#banner > #overlays {
			top:46px;
		}
	</style>
@endif
</header>