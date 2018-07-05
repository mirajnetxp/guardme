<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')

    <style type="text/css">
        .dataTables_filter {
            display: none;
        }

        /*--------- END --------*/
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




                <!-- /top navigation -->

        <!-- page content -->
        <div class="content">
            <!-- top tiles -->

            <style>
                .form-group img {
                    float: left;
                }
            </style>


            <div class="container-fluid">
                <div class="card" style="padding:15px;">
                    <div class="row">
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <div class="header">
                            <h4 class="title">Payments</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>

                        <div class="content">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="gender" class="control-label">User Type</label>
                                    <select name="user_type" id="user_type" class="form-control">
                                        <option value="">All</option>
                                        <option value="1">Freelancers</option>
                                        <option value="2">Employers</option>
                                    </select>
                                </div>
                            </form>

                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <table id="datatable-us-veri" class="table">
                                    <thead>
                                    <tr>
                                        <td>Sno</td>
                                        <td>Job Title</td>
                                        <td>Username</td>
                                        <td>Email</td>
                                        <td>Amount</td>
                                        <td>Type</td>
                                        <td>Actions</td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php($i=1)
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$payment->job_title}}</td>
                                            <td>{{$payment->name}}</td>
                                            <td>{{$payment->email}}</td>
                                            <td>{{$payment->amount}}</td>
                                            <td>{{$payment->type}}</td>
                                            <td>
                                                <a class="btn btn-primary" href="{{ route('employer.payment.details', ['transaction_id' => $payment->transaction_id]) }}">Details</a>
                                            </td>
                                        </tr>
                                        @php($i++)
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
    <script>
        //    User Filtering
        $(document).ready(function () {
            var table = $('#datatable-us-veri').DataTable();
            $('#gender').on('change', function () {
                table
                        .columns(4)
                        .search(this.value)
                        .draw();
            });
        });


    </script>

</body>
</html>
