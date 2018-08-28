<!DOCTYPE html>
<html lang="en">
<?php
use Carbon\Carbon;
?>
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
                            <h4 class="title">Job's</h4>
                            <hr>
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="Jobtype" class="control-label">Type:</label>
                                    <select name="Jobtype" id="Jobtype" class="form-control">
                                        <option value="">Pick an option...</option>
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Pushed">Pushed</option>
                                    </select>
                                </div>

                            </form>
                        </div>

                        <div class="content table-responsive table-full-width">
                            <div class="content table-responsive table-full-width">


                                <table id="datatable-job"
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
                                    @php($presentTime=Carbon::now())
                                    @foreach($allJobs as $Job)

                                        @php($jobSchArry=$Job->schedules->toArray())
                                        @php($jobEndTime=new Carbon(end($jobSchArry)['end']))
                                        @php($jobEnded=$jobEndTime->addHour(36))
                                        @php($ended=false)
                                        @if($presentTime->gt($jobEnded))
                                            @php($ended=true)
                                        @endif
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td> {{$Job->title}}</td>
                                            <td>
                                                @if($Job->is_pause==1)
                                                    Pushed
                                                @elseif($Job->status==0 && !$ended)
                                                    Closed
                                                @elseif($Job->status==0 && $ended)
                                                    Ended
                                                @else
                                                    Open
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    @if($ended && $Job->status==1)
                                                        <button type="button" class="btn btn-success markascomp"
                                                                data-job-id="{{$Job->id}}"
                                                        >Mark as compelete
                                                        </button>
                                                    @endif
                                                    @if($Job->is_pause==1 )
                                                        <button type="button" class="btn btn-info jobclose"
                                                                data-job-id="{{$Job->id}}"
                                                        >Resume
                                                        </button>

                                                    @elseif($Job->status==1)
                                                        <button type="button" class="btn btn-success jobclose"
                                                                data-job-id="{{$Job->id}}"
                                                        >Push
                                                        </button>
                                                    @endif
                                                    @if($Job->status==1)
                                                        <button type="button" class="btn btn-danger jobclose"
                                                                data-job-id="{{$Job->id}}"
                                                        >Close
                                                        </button>
                                                    @endif
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

<script>
    $(document).ready(function () {
        var table = $('#datatable-job').DataTable({});

        $('#Jobtype').on('change', function () {
            table
                .columns(2)
                .search(this.value)
                .draw();
        });
    });
</script>
</body>
</html>
