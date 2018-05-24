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
                            <h4 class="title">Verification</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>

                        <div class="content">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="gender" class="control-label">User Type</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">All</option>
                                        <option value="1">Verified</option>
                                        <option value="2">Unverified</option>
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
                                        <td>Username</td>
                                        <td>Email</td>
                                        <td>Phone</td>
                                        <td class="hidden">Status_id</td>
                                        <td>Status</td>
                                        <td>Actions</td>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php($i=1)
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone}}</td>
                                            <td class="hidden">{{$user->doc_verified?'1':'2'}}</td>
                                            <td>{{$user->doc_verified?'Verified':'Unverified'}}</td>

                                            <td>
                                                <a class="btn btn-primary"
                                                   href="{{route('admin.Verification.details',['id'=>$user->id])}}">Details</a>
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
