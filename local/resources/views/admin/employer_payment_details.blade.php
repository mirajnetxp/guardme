<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<div class="loading hide">Loading&#8230;</div>
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
                            <h4 class="title">Payment Details</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>
                            <br>
                            <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <table align="center" width="800" border="1" cellpadding="50">
                                    <tr>
                                        <td>Employer Name</td>
                                        <td>{{ $payment->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $payment->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>{{ $payment->type }}</td>
                                    </tr>
                                    <tr>
                                        <td>Job Title</td>
                                        <td>{{ $payment->job_title }}</td>
                                    </tr>
                                    <tr>
                                        <td>Transaction Status</td>
                                        <td>{{ $payment->credit_payment_status }}</td>
                                    </tr>
                                    @if (!empty($amount_details))
                                        <tr><td colspan="2" align="center"><strong>Amount Details</strong></td></tr>
                                        @foreach($amount_details as $key => $value)
                                            <tr>
                                                <td>{{ $key }}</td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                        @else
                                        <tr><td>Amount Details</td>
                                        <td>{{ $payment->extra_details }}</td></tr>
                                    @endif
                                    <tr>
                                        <td>Total Amount</td>
                                        <td>{{ $payment->amount }}</td>
                                    </tr>
                                </table>
                                @if ($payment->credit_payment_status == 'paid')
                                    <button class="btn btn-danger complete-refund-button" style="margin-left: 120px; margin-top: 10px;">Complete Refund</button>
                                @endif
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
            $(".complete-refund-button").on("click", function(){
                var url = "{{ route('complete.refund', ['transaction_id' => $payment->transaction_id]) }}";
                var elem = $(this);
                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function(data){
                        $(".success-message").text(data[0]);
                        $(".success-message").removeClass("hide");
                        elem.addClass("hide");
                        elem.siblings('.pause-job-button').removeClass('hide');
                    },
                    error: function(data){
                        $(".error-message").text(data.responseJSON[0]);
                        $(".error-message").removeClass("hide");
                    }
                })
            });
        });


    </script>

</body>
</html>
