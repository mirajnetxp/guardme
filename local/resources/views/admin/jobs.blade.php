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
                                        <option value="Job Ended">Job Ended</option>
                                        <option value="Job Ended But Not Mark as compelete">Job Ended But Not Mark as compelete</option>
                                        <option value="Deleted">Deleted</option>
                                        <option value="Stoped by admin">Stoped by admin</option>
                                        <option value="Active">Active</option>
                                        <option value="Started">Started</option>
                                        <option value="Pushed by user">Pushed by user</option>
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
                                        @php($startedTime=new Carbon($jobSchArry[0]['start']))
                                        @php($jobEnded=$jobEndTime->addHour(36))
                                        @php($ended=false)
                                        @if($presentTime->gt($jobEnded))
                                            @php($ended=true)
                                        @endif
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>  <a href="{{route('view.job',['id'=>$Job->id])}}">{{$Job->title}}</a></td>
                                            <td>

                                                @if($ended && $Job->status==0)
                                                    <span class="badge pull-right">
                                                        Job Ended
                                                    </span>
                                                @endif
                                                @if($ended && $Job->status==1)
                                                    <span class="badge pull-right">
                                                        Job Ended But Not Mark as compelete
                                                    </span>
                                                @endif
                                                @if(!$ended && $Job->is_pause==0 && $Job->status==0)
                                                    <span class="badge pull-right">
                                                        Deleted
                                                    </span>
                                                @endif

                                                @if($Job->is_pause==1 && $Job->status==0)
                                                    <span class="badge pull-right">
                                                        Stoped by admin
                                                    </span>
                                                @endif
                                                @if($Job->is_pause==0 && $Job->status==1 && $presentTime->lt($startedTime))
                                                    <span class="badge pull-right">
                                                        Active
                                                    </span>
                                                @endif
                                                @if($Job->is_pause==0 && $Job->status==1 && $presentTime->gt($startedTime) && !$ended)
                                                    <span class="badge pull-right">
                                                       Started
                                                    </span>
                                                @endif
                                                @if($Job->is_pause==1 && $Job->status==1 )
                                                    <span class="badge pull-right">
                                                        Pushed by user
                                                    </span>
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

                                                    @if($Job->is_pause==1 && $Job->status==0)
                                                        <a href="{{route('admin.job.start',['id'=>$Job->id])}}"
                                                           class="btn btn-success">
                                                            Start
                                                        </a>
                                                    @endif

                                                    @if((($Job->is_pause==0 && $Job->status==1) ||($Job->is_pause==1 && $Job->status==1)) && $presentTime->lt($startedTime) )
                                                        <a href="{{route('admin.job.stop',['id'=>$Job->id])}}"
                                                           class="btn btn-danger">
                                                            Stop
                                                        </a>
                                                    @endif

                                                    @if((($Job->is_pause==1 && $Job->status==0)  || ($Job->is_pause==0 && $Job->status==1) || ($Job->is_pause==1 && $Job->status==1)) & $presentTime->lt($startedTime))
                                                        <a href="{{route('admin.job.delete',['id'=>$Job->id])}}"
                                                           class="btn btn-danger">
                                                            Delete
                                                        </a>
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

        $('.markascomp').click(function () {
            var job_id = $(this).attr('data-job-id');
            var th = this;
            $.ajax({
                url: "/admin/mark-job-as-compelete",
                method: "POST",
                data: {"_token": "{{csrf_token()}}", jobId: job_id},
                dataType: 'json',
                success: function (d) {
                    window.location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {

                }
            });
        })

        $('.jobclose').click(function () {
            var job_id = $(this).attr('data-job-id');
            var th = this;
            $.ajax({
                url: "/admin/job/close",
                method: "POST",
                data: {"_token": "{{csrf_token()}}", jobId: job_id},
                dataType: 'json',
                success: function (d) {
                    window.location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {

                }
            });
        })
    });
</script>
</body>
</html>
