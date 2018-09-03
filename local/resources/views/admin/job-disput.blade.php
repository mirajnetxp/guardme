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
                            <h4 class="title">Job Disput list</h4>
                        </div>

                        <div class="content table-responsive table-full-width">

                            <table class="table" style="max-width: 700px">
                                <thead>
                                <tr>
                                    <td>Job Title</td>
                                    <td>Dispute Ticket</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($disputJobs as $disputJob)
                                    <tr>
                                        <td>
                                            <a href="{{route('view.job',['id'=>$disputJob->id])}}">{{$disputJob->title}}</a>
                                        </td>
                                        <td>

                                            @foreach($disputJob->tickets as $ticket)
                                                <li class="list-group-item">
                                                    <a href="{{route('ticket.show',['id'=>$ticket->id])}}"
                                                       class="btn btn-success">Ticket</a>

                                                    <span class="badge">
                                                        @if($ticket->status==1)
                                                            Awaiting your feedback
                                                        @endif
                                                        @if($ticket->status==0)
                                                            Processing
                                                        @endif
                                                        @if($ticket->status==2)
                                                            Resolved
                                                        @endif
                                                        @if($ticket->status==3)
                                                            External Arbitrator
                                                        @endif
                                                    </span>
                                                    <span class="badge">{{$ticket->state?'Open':'Close'}}</span>
                                                </li>
                                            @endforeach

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
        <!-- /page content -->

        @include('admin.footer')
    </div>
</div>


</body>
</html>
