<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('local/resources/assets/admin/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('local/resources/assets/admin/img/favicon.png') }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <?php 
  		$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
	?>
	<title><?php echo $setts[0]->site_name;?></title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('local/resources/assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{{ asset('local/resources/assets/admin/css/animate.min.css') }}" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="{{ asset('local/resources/assets/admin/css/paper-dashboard.css') }}" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{ asset('local/resources/assets/admin/css/demo.css') }}" rel="stylesheet" />


    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{{ asset('local/resources/assets/admin/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('local/resources/assets/admin/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">

</head>
<body>

<div class="wrapper">
    @include('admin._partials.menu')

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">@yield('title')</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        
                        <li class="dropdown">
                        	
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php 
									 $url = URL::to("/");
									  $logphoto=Auth::user()->photo;
									 if($logphoto!=""){?>
						                <img src="<?php echo  $url;?>/local/images/userphoto/<?php echo $logphoto;?>" alt="..." class="img-circle profile_img">
									 <?php } else { ?>
									   <img src="{{asset('local/resources/assets/img/user.png')}}" alt="..." class="img-circle profile_img">
							<?php } ?>
                  <p>&nbsp;{{ Auth::user()->name }}</p>
                  <b class="caret"></b>
                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="<?php echo $url;?>/admin/edituser/{{Auth::user()->id}}"> Edit Profile</a></li>
                    <li>
                      <a href="<?php echo $url;?>/admin/settings">
                       
                        <span>Settings</span>
                      </a>
                    </li>
                   
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
					<i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
					
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                              </ul>
                        </li>
            
                    </ul>

                </div>
            </div>
        </nav>

@yield('content')



        <footer class="footer">
            <div class="container-fluid">
                
                <div class="copyright pull-right">
                    Copyright &copy; <script>document.write(new Date().getFullYear())</script>
                </div>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="{{ asset('local/resources/assets/admin/js/jquery-1.10.2.js') }}" type="text/javascript"></script>
  <script src="{{ asset('local/resources/assets/admin/js/bootstrap.min.js') }}" type="text/javascript"></script>

  <!--  Checkbox, Radio & Switch Plugins -->
  <script src="{{ asset('local/resources/assets/admin/js/bootstrap-checkbox-radio.js') }}"></script>

  <!--  Charts Plugin -->
  <script src="{{ asset('local/resources/assets/admin/js/chartist.min.js') }}"></script>

    <!--  Notifications Plugin    -->
    <script src="{{ asset('local/resources/assets/admin/js/bootstrap-notify.js') }}"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
  <script src="{{ asset('local/resources/assets/admin/js/paper-dashboard.js') }}"></script>
  <script src="{{ asset('local/resources/assets/admin/js/jquery.dataTables.min.js') }}"></script>

  <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="{{ asset('local/resources/assets/admin/js/demo.js') }}"></script>
  <script src="{{ asset('local/resources/assets/admin/js/canvasjs.min.js') }}"></script>

</html>