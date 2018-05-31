<?php $url = URL::to("/"); ?>
<!DOCTYPE html>
<html lang="en">
<head>


<meta name="csrf-token" content="{{ csrf_token() }}">

   @include('style')  
        <script src="<?php echo $url;?>/js/lockr.js" type="text/javascript" charset="utf-8"></script>
        <script>
               window.verificationConfig =  {
                  url  : "{{ url('/') }}"
              }
              //Lockr.prefix = 'lockr';
              //store.clearAll();

               var gm_steps  = JSON.parse(sessionStorage.getItem('steps'));

               var gm_nxturl  = sessionStorage.getItem('nxturl');

               var gm_nxtstep  = sessionStorage.getItem('nxtstep');

              function steps_check()
              {
               
                    if(gm_nxturl !=null)
                    {
                        //alert(lockr_nxturl);

                        if(gm_nxturl=='{{URL::current()}}')
                            $('#'+gm_nxtstep).addClass('current');
                    }                  
                      //alert(store_steps);
                    if(gm_steps == null)
                    {
                        $('#wstep1').addClass('current');
                    }
                    else{
                        console.log(gm_steps);
                        $.each(gm_steps,function(key,val){
                            if(val=='completed')
                            $('#'+key).addClass('completed');

                        });
                      }
              }
             
              

          </script>
         @yield('script')


</head>
<body>

    

    @include('header')


     <section class=" job-bg ad-details-page">
        <div class="container">

            @yield('bread-crumb')

            <div class="job-postdetails">
                <div class="row">
                    <div class="col-md-8">
                        <div class="section postdetails clearfix">
                            <div class="wizard clearfix">
                                <a class="col-xs-10 col-md-quarter" id="wstep1"><span class="badge badge-inverse">1</span> Edit Job Form</a>
                               
                            </div>
                            @yield('content')
                        </div>
                    </div>
                    @include('jobs.sidebar')
                </div>
            </div>
        </div>
    </section>
   @include('footer')
    
</body>
</html>