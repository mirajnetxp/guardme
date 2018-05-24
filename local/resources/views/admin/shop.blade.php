<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')
    <style>
        .dataTables_filter {
            display: none;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <!-- <div class="main_container"> -->
    <div class="sidebar" data-background-color="white" data-active-color="danger">
        <div class="sidebar-wrapper">
            @include('admin.sitename');

            <!-- <div class="clearfix"></div> -->

            <!-- menu profile quick info -->
        @include('admin.welcomeuser')
        <!-- /menu profile quick info -->

            <!-- <br /> -->

            <!-- sidebar menu -->
        @include('admin.menu')




        <!-- /sidebar menu -->

            <!-- /menu footer buttons -->

            <!-- /menu footer buttons -->
        </div>
    </div>


    <div class="main-panel">
        <!-- top navigation -->
    @include('admin.top')

	<?php $url = URL::to( "/" ); ?>


    <!-- /top navigation -->

        <!-- page content -->
        <div class="content">
            <!-- top tiles -->

            <style>
                div.dataTables_wrapper div.dataTables_filter input {
                    border: 1px solid #000;
                }
            </style>


            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="padding:15px;">
                        <!-- <div class="x_title">
                          <h2>Shop</h2>
                          <ul class="nav navbar-right panel_toolbox">

                             <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                          </ul>
                          <div class="clearfix"></div>

                        </div> -->
                        <div class="header">
                            <h4 class="title">Companies</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>
                        <div class="content">

                            <form class="form-inline">
                                <div class="form-group">
                                    <label class="col-md-3 col-lg-3" for="location_filter" class="control-label">Location:</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="location" id="location_filter">
                                    </div>
                                </div>
                            </form>
                            <form class="form-inline">
                                <div class="col-md-3 col-lg-3">
                                    Registration date range :
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">Max</label>
                                    <input type="date" class="form-control daterangepicker" id="date_filter_max"
                                           name="reg_date_max">
                                </div>
                                <div class="form-group">-</div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">Min</label>
                                    <input type="date" class="form-control daterangepicker" id="date_filter_min"
                                           name="reg_date_min">
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label"> </label>
                                    <input type="button" class="btn btn-info" id="date_reset" value="Reset">
                                </div>
                            </form>
                        </div>
                        <div class="content table-responsive table-full-width">


                            <table id="datatable-company"
                                   class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Shop Name</th>
                                    <th class="hidden">address</th>
                                    <th class="hidden">created at</th>
                                    <th>Featured</th>
                                    <th>Status</th>
                                    <th>Total Balance</th>
                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>
								<?php
								$i = 1;
								foreach ($shop as $viewshop) { ?>
                                <tr>
                                    <td><?php echo $i;?></td>

                                    <td><?php echo $viewshop->shop_name;?></td>

                                    <td class="hidden"><?php echo $viewshop->address;?></td>
                                    <td class="hidden">{{date("Ymd",strtotime($viewshop->created_at) )}}</td>

                                    <td><?php echo $viewshop->featured;?></td>

                                    <td><?php echo $viewshop->status;?></td>

                                    <td> -</td>

                                    <td>

										<?php if(config( 'global.demosite' ) == "yes"){?>
                                        <a href="#" class="btn btn-success btndisable">Edit</a> <span
                                                class="disabletxt">( <?php echo config( 'global.demotxt' );?> )</span>
										<?php } else { ?>
                                        <a href="<?php echo $url;?>/admin/edit-shop/{{ $viewshop->id }}"
                                           class="btn btn-success">Edit</a>
                                        @if($viewshop->status=='approved')
                                            <a href="<?php echo $url;?>/admin/suspend/{{ $viewshop->id }}"
                                               class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to suspend this?')">suspend</a>
                                        @else
                                            <a href="<?php echo $url;?>/admin/unsuspend/{{ $viewshop->id }}"
                                               class="btn btn-success"
                                               onclick="return confirm('Are you sure you want to unsuspend this?')">unsuspend</a>
                                        @endif
										<?php } ?>
										<?php if(config( 'global.demosite' ) == "yes"){?>
                                        <a href="#" class="btn btn-danger btndisable">Delete</a> <span
                                                class="disabletxt">( <?php echo config( 'global.demotxt' );?> )</span>
										<?php } else { ?>
                                        <a href="<?php echo $url;?>/admin/shop/{{ $viewshop->id }}"
                                           class="btn btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
										<?php } ?>
                                    </td>
                                </tr>
								<?php $i ++;} ?>

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- /page content -->

        @include('admin.footer')
    </div>
</div>
<script src="{{asset('/js/moment.js')}}"></script>
<script>
    //    User Filtering
    $(document).ready(function () {
        var table = $('#datatable-company').DataTable({});

        $('#location_filter').on('keyup', function () {
            table
                .columns(2)
                .search(this.value)
                .draw();
        });
        $('#date_reset').click(function () {
            $('#date_filter_max').val('')
            $('#date_filter_min').val('')
            table.draw();
        })

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = moment($('#date_filter_max').val()).format('YYYYMMDD');
                var max = moment($('#date_filter_min').val()).format('YYYYMMDD');
                var age = parseFloat(data[3]) || 0; // use data for the age column

                if (( isNaN(min) && isNaN(max) ) ||
                    ( isNaN(min) && age <= max ) ||
                    ( min <= age && isNaN(max) ) ||
                    ( min <= age && age <= max )) {
                    return true;
                }
                return false;
            }
        );

        $('#date_filter_max,#date_filter_min').on('change', function () {
            table.draw();
        });
        //   End User  Filtering
    });


</script>
</body>
</html>
