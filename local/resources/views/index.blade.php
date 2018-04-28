<?php
use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$setid=1;
$setts = DB::table('settings')
    ->where('id', '=', $setid)
    ->get();
?>
        <!DOCTYPE html>
<html lang="en">
<head>



    @include('style')

</head>
<body>



<!-- fixed navigation bar -->

    @include('header')


<!-- slider -->



    <div id="banner" class="frontpage-blocks">
        <div id="overlays"></div>
        <?php if(!empty($setts[0]->site_banner)){?>
        <img src="https://guarddme.com/img/header-banner.jpg" class="img-responsive banner-class backimg" id="b1">
        <?php } else {?>
        <img src="<?php echo $url;?>/img/banner.jpg" class="img-responsive bannerheight ">
        <?php } ?>

    <div class="clear-both"></div>
        <script>
            var slideIndex = 0;
            //showSlides();

            function showSlides() {
                var i;
                var slides = document.getElementsByClassName("backimg");

                var dots = document.getElementsByClassName("dot");
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slideIndex++;
                if (slideIndex > slides.length) {slideIndex = 1}
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[slideIndex-1].style.display = "block";
                dots[slideIndex-1].className += " active";
                setTimeout(showSlides, 2000); // Change image every 2 seconds
            }
        </script>

    </div>
<div class="clear-both"></div>


<!-- 
<div class="clearfix"></div> -->

<div class="container1 top-mrgn-10 bottom-mrgn-10">
    <div class="clearfix hidden-xs"></div>
    <div class="headerbg">
        <div class="col-md-12 home-font" align="left">
            <h1 class="h-responsive"><strong>Manned Security Freelance Marketplace.
                </strong></h1>

            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="item active">
                        <p class="after-heading" >Looking for security personnel in the UK?</p>
                    </div>

                    <div class="item">
                        <p class="after-heading">Get access to thousands of vetted SIA security personnel.
                        </p>
                    </div>


                </div>
            </div>




            <a href="#"
               title="Start a project" type=""
               class="btn btn-huge blue "
               target="" data-reactid="337">
                <span class="text" title="Start a project" data-reactid="338">Hire Security Personnel</span>
            </a>
        </div>



        <div class="col-md-12" align="left">
            <div class="col-md-7" style="padding-left: 0px">
                <h4 class="h-responsive">Are you SIA licensed or looking for security personnel?</h4>
            </div>
            <div class="col-md-5">

            </div>
        </div>
    </div>
<div class="clear-both"></div>
</div>
<div class="clear-both"></div>







<script>

   /* $(document).ready(function(){
        $(".dropdown").hover(
            function() {
                $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideDown("400");
                $(this).toggleClass('open');
            },
            function() {
                $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true,true).slideUp("400");
                $(this).toggleClass('open');
            }
        );
    });*/
</script>

<script>
    $(document).ready(function() {
        src = "{{ route('searchajax') }}";
        $("#search_text").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: src,
                    dataType: "json",
                    data: {
                        term : request.term
                    },
                    success: function(data) {
                        response(data);

                    }
                });
            },
            minLength: 1,

        });
    });
</script>







<div class="ashbg">

    <div class="clearfix"></div>


    <div class="clearfix"></div>

</div>

<div class="clearfix"></div> 

<div class="page">
    <div class="container">
    <div class="works section job-category-items">
            
            <div class="col-md-12" align="center"><h1>How it works</h1></div>
            <div class="height30"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="col-md-6">
                            <img src="img/how-it-works.png" class="img-responsive" alt="">
                        </div>

                        <div class="col-md-6">
                            <h3 class="">1. Tell us what you need</h3>
                            <p class="">First, select required professional service about what type of pro you’re looking for.</p>
                            <h3 class="">2. Review service providers</h3>
                            <p class="">Within seconds, you’ll receive expert service providers profile with their ratings. choose one among them.</p>
                            <h3 class="">3. Hire the right pro</h3>
                            <p class="">Compare prices, profiles, and reviews, then hire the pro that’s right for you.</p>

                        </div>
                    </div>
                    <div class="col-md-1"></div>

                </div>

            </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>

    <div class="section workshop-traning">
        <div class="section-title">
            <h4>Customers use to get millions of projects done quickly and easily</h4>
            <a href="#" class="btn btn-primary">See all</a> 
        </div>
        <div class="row">
            <div class="col-md-12">
               <!--  <div class="col-md-1"></div> -->
                <div class="nopadding testimons">
                    <div id="flexiselDemotesti">
                        <?php foreach($testimonials as $testimonial){?>
                        <li>
                            <div class="weightbg">
                                <div class="innerbg">
                                    <p><?php echo $testimonial->description;?></p>
                                </div>
                                <div class="user">
                                    <?php
                                    $testimonialphoto="/testimonialphoto/";
                                    $path ='/local/images'.$testimonialphoto.$testimonial->image;
                                    if($testimonial->image!=""){
                                    ?>
                                    <img src="<?php echo $url.$path;?>" class="img-responsive" alt="">
                                    <?php } else {?>
                                    <img src="<?php echo $url.'/local/images/nophoto.jpg';?>"  class="img-responsive">
                                    <?php } ?>
                                    <div class="user-txt">

                                        <h5><?php echo $testimonial->name;?></h5>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div><!-- workshop-traning -->

    <div class="section video workshop-traning">
         
         <div class="section-title">
            <h4>Thousands of professionals are growing their businesses.</h4>
           
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-1"></div>
              <div class="col-md-10">
                  <div class="col-md-8 paddingoff">
                        <img src="img/v1.jpg" class="img-responsive big firstsize" alt="">
                        <div class="titlesection">
                            <h3>Caitlin Sarah</h3>
                            <span>Designer</span>
                        </div>

                    </div>
                    <div class="height10 visible-xs "></div>
                     <div class="col-md-4 paddingoff left10">
                        <div class="justmove col-md-12 paddingoff"><img src="img/v2.jpg" class="img-responsive" alt="">
                            <div class="titlesection">
                                <h3>William Mark</h3>
                                <span>Analyst</span>
                            </div>
                            
                        </div>
                        <div class="height10 hidden-md"></div>
                        <div class="justmove col-md-12 paddingoff"><img src="img/v3.jpg" class="img-responsive" alt="">
                            <div class="titlesection">
                                <h3>Sophie Olivia</h3>
                                <span>Developer</span>
                            </div>
                            
                        </div>
                    </div>
              </div>
                    

                   
            </div>
        </div>
    </div>

    </div>
</div>
<div class="clearfix"></div>










<div class="video">
    <div class="clearfix"></div>
   
</div>


<!-- download -->
    <section id="download" class="clearfix parallax-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h2>Download on App Store</h2>
                </div>
            </div><!-- row -->

            <!-- row -->
            <div class="row">
                <!-- download-app -->
                <div class="col-sm-4">
                    <a href="#" class="download-app">
                        <img src="images/icon/16.png" alt="Image" class="img-responsive">
                        <span class="pull-left">
                            <span>available on</span>
                            <strong>Google Play</strong>
                        </span>
                    </a>
                </div><!-- download-app -->

                <!-- download-app -->
                <div class="col-sm-4">
                    <a href="#" class="download-app">
                        <img src="images/icon/17.png" alt="Image" class="img-responsive">
                        <span class="pull-left">
                            <span>available on</span>
                            <strong>App Store</strong>
                        </span>
                    </a>
                </div><!-- download-app -->

                <!-- download-app -->
                <div class="col-sm-4">
                    <a href="#" class="download-app">
                        <img src="images/icon/18.png" alt="Image" class="img-responsive">
                        <span class="pull-left">
                            <span>available on</span>
                            <strong>Windows Store</strong>
                        </span>
                    </a>
                </div><!-- download-app -->
            </div><!-- row -->
        </div><!-- contaioner -->
    </section><!-- download -->


@include('footer')
</body>
</html>
