 <!-- footer content -->
        <footer>
          <div class="pull-right">
            Copyright &copy; <?php echo date('Y');?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->


	<!-- jQuery -->

    {!!Html::script('local/resources/assets/admin/assets/js/jquery-1.10.2.js')!!}
	<!-- Bootstrap -->

	{!!Html::script('local/resources/assets/admin/assets/js/bootstrap.min.js')!!}
    <!-- FastClick -->
	{!!Html::script('local/resources/assets/admin/assets/js/bootstrap-checkbox-radio.js')!!}

    <!-- NProgress -->


    <!-- Chart.js -->

	{!!Html::script('local/resources/assets/admin/assets/js/bootstrap-notify.js')!!}
    <!-- gauge.js -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
	{!!Html::script('local/resources/assets/admin/assets/js/paper-dashboard.js')!!}
    <!-- bootstrap-progressbar -->



    <!-- iCheck -->

  {!!Html::script('local/resources/assets/admin/vendors/Chart.js/dist/Chart.min.js')!!}
  {!!Html::script('local/resources/assets/admin/js/jquery.dataTables.min.js')!!}
  {!!Html::script('local/resources/assets/admin/js/dataTables.bootstrap.min.js')!!}
  {!!Html::script('local/resources/assets/admin/js/canvasjs.min.js')!!}

	{!!Html::script('local/resources/assets/admin/build/js/custom.min.js')!!}
 <script>
     $(document).ready(function(){
         $(document).ajaxStart(function(){
             $(".loading").removeClass("hide");

         });
         $(document).ajaxStop(function(){
             $(".loading").addClass("hide");
         });

         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
     })
 </script>