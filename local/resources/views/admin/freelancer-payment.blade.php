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
                            <h4 class="title">Payment</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>
                        <hr>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            <li class="active"><a href="#home" role="tab" data-toggle="tab">Bank Payment</a></li>
                            <li><a href="#profile" role="tab" data-toggle="tab">Paypal Payment</a></li>
                        </ul>

                        <div class="content">
                            {{--<form class="form-inline">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="gender" class="control-label">User Type</label>--}}
                            {{--<select name="user_type" id="user_type" class="form-control">--}}
                            {{--<option value="">All</option>--}}
                            {{--<option value="1">Freelancers</option>--}}
                            {{--<option value="2">Employers</option>--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</form>--}}
                        </div>

                        <hr>

                        <div class="row">
                            <form id="paytoallbank" action="/admin/freelancer/pay-to-all" method="post">
                                {{csrf_field()}}
                            </form>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="home">
                                        <h1 class="text-center">Bank Payment</h1>
                                        <p class="text-center">

                                        <button form="paytoallbank" class="btn btn-success" >Pay to all</button>
                                        </p>
                                        <table id="datatable-bank-payment" class="table">
                                            <thead>
                                            <tr>
                                                <td>Sno</td>
                                                <td>Username</td>
                                                <td>Bank Details</td>
                                                <td>Credit</td>
                                                <td>Actions</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php($i=1)
                                            @foreach($allFreeLancers as $free)
                                                @if($free->paymentmethod['method_type']=='bank')

                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$free->name}}</td>
                                                        <td>
                                                            @php($detail=json_decode($free->paymentmethod['method_details']))
                                                            <p>
                                                                Bank Name : {{$detail->bank_name}}
                                                                <br>
                                                                Account Name : {{$detail->ac_name}}
                                                                <br>
                                                                Short code : {{$detail->sort_code}}
                                                                <br>
                                                                Account Number : {{$detail->ac_number}}
                                                            </p>

                                                        </td>

                                                        <td>$ &nbsp;{{$free->credit}}</td>
                                                        <td>
                                                            <button class="btn btn-success">Pay</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="profile">
                                        <h2 class="text-center">Paypal Payment</h2>
                                        <table id="datatable-payple-payment" class="table">
                                            <thead>
                                            <tr>
                                                <td>Sno</td>
                                                <td>Username</td>
                                                <td>Email</td>
                                                <td>Credit</td>
                                                <td>Actions</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php($i=1)
                                            @foreach($allFreeLancers as $free)
                                                @if($free->paymentmethod['method_type']=='payple')
                                                    <tr>
                                                        <td>{{$i++}}</td>
                                                        <td>{{$free->name}}</td>
                                                        <td>{{$free->email}}</td>
                                                        <td>$ &nbsp;{{$free->credit}}</td>
                                                        <td>

                                                            <button class="btn btn-success">Pay</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
        //    User Filtering
        $(document).ready(function () {
            var table = $('#datatable-us-veri').DataTable();
            $('#gender').on('change', function () {
                table
                    .columns(4)
                    .search(this.value)
                    .draw();
            });

            $('#bank-pay-to-all').click(function () {

                $.ajax({
                    url: "/admin/freelancer/pay-to-all",
                    method: "POST",
                    dataType: 'json',
                    success: function (d) {
                        console.log(d);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        if (typeof xhr.responseText == "undefined") {
                            root.mess = "Internet Connection is Slow or Disconnect";
                            root.retry = "Retry";
                            window.ajaxcurrent = this;
                            return
                        }
                    }
                });
            })
        });


    </script>

</body>
</html>
