<!DOCTYPE html>
<html lang="en">
<head>
    @include('style')
    <style src="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"></style>
    <style src="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"></style>
    <style src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"></style>
    <style type="text/css">
        button {
            background: #00a651;
            color: white;
            padding-top: 10px;
            margin: 0;
            border: none;
            border-radius: 5px;
            padding-left: 20px;
            padding-right: 20px;
            height: 40px;
        }
    </style>
</head>
<body>


<!-- fixed navigation bar -->

@include('header')

<!-- slider -->


<section class=" job-bg ad-details-page">
    <div class="container">
        <div class="breadcrumb-section">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li>Wallet</li>
            </ol>
            <h2 class="title">Wallet</h2>
        </div>
        <div class="banner-form banner-form-full job-list-form">
            <form method="get" action="{{ url('/wallet/jobs/find') }}" id="formID">
                <input type="text" class="form-control" placeholder="Job search" name="keyword"
                       value="{{old('keyword')}}">

                <button type="submit" class="btn btn-primary" value="Search">Search</button>
            </form>
        </div>


        <div class="adpost-details post-resume">


            <div class="row">
                <div class="col-md-8">
                    <div class="section postdetails">
                        <div class="description-info">
                            <h2>Wallet</h2>

                            <div class="row">
                                <form method="get" action="{{ url('/wallet/jobs/find') }}" id="filters">
                                    <div class="col-sm-12">
                                        <label class="col-sm-2">Transaction Date:</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="start_date date-picker form-control"
                                                   name="start_date" placeholder="Start Date" required="true"
                                                   value="{{old('start_date')}}">
                                            <span class="text-danger error-span"></span>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="end_date date-picker form-control" name="end_date"
                                                   placeholder="End Date" required="true" value="{{old('end_date')}}">
                                            <span class="text-danger error-span"></span>
                                        </div>
                                        <div class="col-sm-1">
                                            <button type="submit" value="GO" class="btn btn-primary">GO</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <table class="display nowrap table" id="table">
                                <thead>
                                <tr>
                                    <th>Reference Number</th>
                                    <th>Job Title</th>
                                    <th>Amount</th>
                                    <th>Transaction Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jobs as $job)
                                    <tr>
                                        <td>{{$job->id}}</td>
                                        <td><a href="{{url('wallet/invoice/'.$job->id)}}">{{$job->title}}</a></td>
                                        <td>
                                            @if(Auth::user()->admin == 2)
                                                {{$job->amount / $job->number_of_freelancers}}
                                            @else
                                                {{$job->amount}}
                                            @endif
                                        </td>
                                        <td>{{date('d/m/Y',strtotime($job->created_at))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="section job-short-info center balance">
                        <h5>Escrow Balance</h5>
                        <ul>
                            <li>
                                <p class="font-35">£

                                    @if($wallet_data['escrow_balance']==0)
                                        0.00
                                    @else
                                        {{ $wallet_data['escrow_balance'] }}
                                    @endif
                                </p>
                            </li>
                        </ul>
                    </div>

                    <div class="section quick-rules job-postdetails">
                        <h4>Wallet Tips</h4>
                        <ul>
                            <li>
                                Your Escrow balance is the money you have on hold. It is not available for wihdrawal.
                            </li>
                            <li>
                                Your credit balance is money available for withdrawal. We run an automatic sweep every
                                Monday and Friday to clear all credit balances.
                            </li>
                            <li>
                                Please update your bank or Paypal details in the settings page to ensure that you get
                                paid all credit balances.
                            </li>
                            <li>
                                Freelancers receive a job fee only. There is no charge on this fee and it is their
                                responsibility to settle their taxes.
                            </li>
                            <li>
                                All transactions that are paid into the GuardME marketplace is subject to VAT and an
                                admin fee. This is usually paid during the creation of a job.
                            </li>
                            <li>
                                All refunds will include a VAT and admin fees.
                            </li>
                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>


@include('footer')

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">

    $(document).ready(function () {
        var table = $('#table').DataTable({
            dom: 'Bfrtip',
            searching: false,
            sorting: true,
            buttons: [
                'csv', 'excel', 'pdf',
            ],
            page_length: 50,
        });

        $('#table_paginate').css('display', 'none');

        $('#table tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            window.location = "{{url('/wallet/invoice/')}}" + '/' + data[0];

        });

        var date = new Date();

        $('.date-picker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        });


    });
</script>
</body>
</html>
