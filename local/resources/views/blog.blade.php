<!DOCTYPE html>
<html lang="en">
<head>

   @include('style')
	
    
    <style>
    .list-group {border-radius: 0;}
.list-group .list-group-item {background-color: transparent;overflow: hidden;border: 0;border-radius: 0;padding: 0 16px;}
.list-group .list-group-item .row-picture,
.list-group .list-group-item .row-action-primary {float: left;display: inline-block;padding-right: 16px;padding-top: 8px;}
.list-group .list-group-item .row-picture img,
.list-group .list-group-item .row-action-primary img,
.list-group .list-group-item .row-picture i,
.list-group .list-group-item .row-action-primary i,
.list-group .list-group-item .row-picture label,
.list-group .list-group-item .row-action-primary label {display: block;width: 56px;height: 56px;}
.list-group .list-group-item .row-picture img,
.list-group .list-group-item .row-action-primary img {background: rgba(0, 0, 0, 0.1);padding: 1px;}
.list-group .list-group-item .row-picture img.circle,
.list-group .list-group-item .row-action-primary img.circle {border-radius: 100%;}
.list-group .list-group-item .row-picture i,
.list-group .list-group-item .row-action-primary i {background: rgba(0, 0, 0, 0.25);border-radius: 100%;text-align: center;line-height: 56px;font-size: 20px;color: white;}
.list-group .list-group-item .row-picture label,
.list-group .list-group-item .row-action-primary label {margin-left: 7px;margin-right: -7px;margin-top: 5px;margin-bottom: -5px;}
.list-group .list-group-item .row-content {display: inline-block;width: calc(100% - 92px);min-height: 66px;}
.list-group .list-group-item .row-content .action-secondary {position: absolute;right: 16px;top: 16px;}
.list-group .list-group-item .row-content .action-secondary i {font-size: 20px;color: rgba(0, 0, 0, 0.25);cursor: pointer;}
.list-group .list-group-item .row-content .action-secondary ~ * {max-width: calc(100% - 30px);}
.list-group .list-group-item .row-content .least-content {position: absolute;right: 16px;top: 0px;color: rgba(0, 0, 0, 0.54);font-size: 14px;}
.list-group .list-group-item .list-group-item-heading {color: rgba(0, 0, 0, 0.77);font-size: 20px;line-height: 29px;}
.list-group .list-group-separator {clear: both;overflow: hidden;margin-top: 10px;margin-bottom: 10px;}
.list-group .list-group-separator:before {content: "";width: calc(100% - 90px);border-bottom: 1px solid rgba(0, 0, 0, 0.1);float: right;}

.bg-profile{background-color: #3498DB !important;height: 150px;z-index: 1;}
.bg-bottom{height: 100px;margin-left: 30px;}
.img-profile{display: inline-block !important;background-color: #fff;border-radius: 6px;margin-top: -50%;padding: 1px;vertical-align: bottom;border: 2px solid #fff;-moz-box-sizing: border-box;box-sizing: border-box;color: #fff;z-index: 2;}
.row-float{margin-top: -40px;}
.explore a {color: green; font-size: 13px;font-weight: 600}
.twitter a {color:#4099FF}
.img-box{box-shadow: 0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);border-radius: 2px;border: 0;}

    
    </style>

</head>
<body> 
    <!-- fixed navigation bar -->
    @include('header')

    <!-- slider -->
    
	<section class="clearfix job-bg  ad-profile-page">
		<div class="container">
			<div class="breadcrumb-section">
				<ol class="breadcrumb">
					<li><a href="{{URL::to('/')}}">Home</a></li>
					<li><?php echo "Blog"; ?></li>
				</ol>						
				<h2 class="title"><?php echo  "Guarddme Blog"; ?></h2>
			</div>
			<div class="career-objective section">
				<div class="user-pro-section">
					<!-- profile-details -->
				<!--	<div class="profile-details section">
						<h2><?php // echo  "Blog"; ?></h2>
					</div> -->
				</div>
                
                
               
                
   	<div class="row">
      @foreach($posts as $post) 
		<div class="row"> 
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <a href="{!! $post['link'] !!}">
                            <img src="{!! $post['image'] !!}" class="img-responsive img-box img-thumbnail"> 
                        </a>
                    </div> 
                    <div class="col-xs-12 col-sm-9 col-md-9">
                     
                        <h4><a href="{!! $post['link'] !!}">{!! $post['title'] !!}</a></h4>
                        <p>{!! $post['content'] !!}</p>
                   
                          <div class="list-group">
                                <div class="list-group-item">
                                  
                                    <div class="row-content">
                                      
                                        <small>
                                            <i class="glyphicon glyphicon-time"></i> <span>Posted on: &nbsp;&nbsp;</span>   <span>{!! date('F j,Y : H:s:i ',strtotime($post['date']))!!} </span>
                                           
                                        </small>
                                    </div>
                                </div>
                            </div>
                    </div> 
                </div>
                <hr>
       @endforeach 
           
    


	</div>
			
            
            
            
            
            
            
			</div>
		</div>
	</section>


      @include('footer')
</body>
</html>