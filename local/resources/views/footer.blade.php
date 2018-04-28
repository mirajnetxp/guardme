<?php 
use Illuminate\Support\Facades\Route;
$ncurrentPath= Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
?>
<footer id="footer" class="clearfix">

	<!-- footer-top -->
	<section class="footer-top clearfix">
		<div class="container">
			<div class="row">
				<!-- footer-widget -->
				<div class="col-sm-3">
					<div class="footer-widget">
						<h3>Quik Links</h3>
						<ul>
							<li><a href="<?php echo $url;?>">Home</a></li>
							<li><a href="<?php echo $url;?>/about">About Us</a></li>
							<li><a href="<?php echo $url;?>/terms-conditions">Term & Conditions</a></li>
							<li><a href="<?php echo $url;?>/privacy-policy">Privacy Policy</a></li>
							<li><a href="<?php echo $url;?>/contact">Contact Us</a></li>


		
							
						</ul>
					</div>
				</div><!-- footer-widget -->

				<!-- footer-widget -->
					<div class="col-sm-3">
						<div class="footer-widget">
							<h3>Customers</h3>
							<ul>
								<li><a href="<?php echo $url;?>/register">Signup </a></li>
								<li><a href="<?php echo $url;?>/login">Login </a></li>
								<li><a href="<?php echo $url;?>/how-it-works">How it works </a></li>
								<li><a href="<?php echo $url;?>/safety">Safety </a></li>
								<li><a href="<?php echo $url;?>/service-guide">Service Guide </a></li>
								<li><a href="<?php echo $url;?>/how-to-pages">How To Pages </a></li>
								<li><a href="<?php echo $url;?>/success-stories">Success stories </a></li>
							</ul>
						</div>
					</div><!-- footer-widget -->
				
				<!-- footer-widget -->
					<div class="col-sm-3">
						<div class="footer-widget social-widget">
							<h3>Follow us on</h3>
							<ul>
								<li><a href="<?php echo $setts[0]->site_facebook;?>" target="_blank"><i class="fa fa-facebook-official"></i>Facebook</a></li>
								<li><a href="<?php echo $setts[0]->site_twitter;?>" target="_blank"><i class="fa fa-twitter-square"></i>Twitter</a></li>
								<li><a href="<?php echo $setts[0]->site_gplus;?>" target="_blank"><i class="fa fa-google-plus-square"></i>Google+</a></li>
								<li><a  href="<?php echo $setts[0]->site_pinterest;?>" target="_blank"><i class="fa fa-pinterest-square"></i>Pinterest</a></li>
								<li><a  href="<?php echo $setts[0]->site_pinterest;?>" target="_blank"><i class="fa fa-instagram"></i>Instagram</a></li>

								
							</ul>
						</div>
					</div>
					<!-- footer-widget -->
					<div class="col-sm-3">
						<div class="footer-widget news-letter">
							<h3>Newsletter</h3>
							<p>Jobs is Worldest leading Portal platform that brings!</p>
							<!-- form -->
							<form action="#">
								<input type="email" class="form-control" placeholder="Your email id">
								<button type="submit" class="btn btn-primary">Sign Up</button>
							</form><!-- form -->			
						</div>
					</div><!-- footer-widget -->
		</div>
	</section>


	<div class="footer-bottom clearfix text-center">
			<div class="container">

				<p><?php if($setts[0]->site_copyright!=""){?><?php echo $setts[0]->site_copyright; } else {?>&copy; <?php echo date('Y');?>. All Rights Reserved.<?php } ?></p>
			</div>
		</div><!-- footer-bottom -->


	  
        
      </footer>

   
    <!-- add javascripts -->
    
	<?php if($ncurrentPath=="index" or $ncurrentPath=="/"){?>
<script>
    $(document).ready(function(){
      $(window).scroll(function() {
      if($(window).width() > 1200 )
	   {
	  // check if scroll event happened
        if ($(document).scrollTop() > 50) { // check if user scrolled more than 50 from top of the browser window
          $(".navbar-fixed-top").css("background-color", "#F4F4F4");
		   
          $(".navbar-fixed-top li a").css("color","#000000");
		  
		  
		  $(".navbar-fixed-top li.dropdown .open a").css("color","#000000");
		  // if yes, then change the color of class "navbar-fixed-top" to white (#f8f8f8)
        } else {
          $(".navbar-fixed-top").css("background-color", "transparent"); 
		  
		   $(".navbar-fixed-top li a").css("color","#ffffff");
		   $(".navbar-fixed-top li.dropdown .open a").css("color","#000000");
		  // if not, change it back to transparent
        }
		
		
		
		}
		
      });
    });
</script>
	<?php } else  {?>
	<script>
    $(document).ready(function(){
      $(window).scroll(function() {
       if($(window).width() > 1200 )
	   {
	  // check if scroll event happened
        if ($(document).scrollTop() > 50) { // check if user scrolled more than 50 from top of the browser window
          $(".navbar-fixed-top").css("background-color", "#F4F4F4");
          $(".navbar-fixed-top li a").css("color","#000000");
		  // if yes, then change the color of class "navbar-fixed-top" to white (#f8f8f8)
        } else {
          $(".navbar-fixed-top").css("background-color", "#ffffff"); 
		   $(".navbar-fixed-top li a").css("color","#000000");
		  // if not, change it back to transparent
        }
		}
		
      });
    });
</script>
	<?php } ?>
    <!-- Only used for Script Tutorial's Demo site. Please ignore and remove. -->
  <script type="text/javascript">
	$(document).ready(function() {
  $('#media').carousel({
    pause: true,
    interval: false,
  });
});




</script>
	
	
	<script type="text/javascript" src="js/jquery.flexisel.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://getaddress.io/js/jquery.getAddress-2.0.7.min.js"></script>
	
	
	<script src="<?php echo $url;?>/js/jquery.multiselect.js"></script>
<script>
$('#langOpt').multiselect({
    columns: 1,
    placeholder: 'Select Services'
});
var pgsVal = 0;
$( ".trackprogress" ).each(function(i, obj) {
    if($(this).val().length >0){
        pgsVal += 4;
    }
});
console.log(pgsVal);
$( "#progressbar" ).progressbar({
    value: pgsVal,
    create: function(event, ui) {$(this).find('.ui-widget-header').css({'background-color':'#5cb85c'})}
});
$('.trackprogress').change(function() {
    var pbVal = 0;
    $( ".trackprogress" ).each(function(i, obj) {
        if($(this).val().length >0){
            pbVal += 4;
        }
    });
    $( "#progressbar" ).progressbar( "option", "value", pbVal );
    return false; 
});
$('#postcode_lookup').getAddress({
    api_key: 'ZTIFqMuvyUy017Bek8SvsA12209',
    input_id : 'address_id',
    input_name : 'address_id',
    input_class :'form-control validate[required]',
    button_class : 'btn btn-primary',
    dropdown_class:'form-control',
    <!--  Or use your own endpoint - api_endpoint:https://your-web-site.com/getAddress, -->
    output_fields:{
        line_1: '#line1',
        line_2: '#line2',
        line_3: '#line3',
        post_town: '#town',
        county: '#county',
        postcode: '#postcode'
    },
<!--  Optionally register callbacks at specific stages -->                                                                                                               
    onLookupSuccess: function(data){/* Your custom code */
        console.log(data);
        $('#addresslat').val(data.latitude);
        $('#addresslong').val(data.longitude);
        $('#country').val('UK');
    },
    onLookupError: function(){/* Your custom code */},
    onAddressSelected: function(elem,index){/* Your custom code */}
});
$("select#nationality").change(function(){    
    $( "select#nationality option:selected").each(function(){        
        if($(this).attr("value")=="229"){
            $("#visa_no_field").hide();
            $("#niutr_no_field").show();
        } else {
            $("#visa_no_field").show();
            $("#niutr_no_field").hide();
        }
    });
}).change();
</script>

	<?php /* ?><script src="{{ asset('js/app.js') }}"></script><?php */?>