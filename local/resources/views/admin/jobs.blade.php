<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')

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
        <style>
            div.dataTables_wrapper div.dataTables_filter input {
                border: 1px solid #000;
            }
        </style>

        <!-- /top navigation -->

        <!-- page content -->
        <div class="content">
            <!-- top tiles -->

            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="padding:15px;">
                        <!-- <div class="x_title">
                          <h2>Pages</h2>
                          <ul class="nav navbar-right panel_toolbox">

                             <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                          </ul>
                          <div class="clearfix"></div>

                        </div> -->
                        <div class="header">
                            <h4 class="title">Pages</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>

                        <div class="content table-responsive table-full-width">
                            <div class="content table-responsive table-full-width">


                                <table id="datatable-responsive"
                                       class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Sno</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i=0)
                                    @foreach($allJobs as $Job)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td> {{$Job->title}}</td>
                                            <td>
                                                @if($Job->is_pause==1)
                                                    Pushed
                                                @elseif($Job->status==0)
                                                    Closed
                                                @else
                                                    Open
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success">Mark as compelete</button>
                                                    <button type="button" class="btn btn-danger">Close</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- /page content -->

        @include('admin.footer')
    </div>
</div>


</body>
</html>
