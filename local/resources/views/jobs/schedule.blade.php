<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('style')
    <style type="text/css">
        .noborder ul,li { margin:0; padding:0; list-style:none;}
        .noborder .label { color:#000; font-size:16px;}
        .update{

            margin-top:10px
        }
    </style>
</head>
<body>

<!-- fixed navigation bar -->
@include('header')

        <!-- slider -->
<div class="clearfix"></div>

<div class="video">
    <div class="clearfix"></div>
    <div class="headerbg">
        <div class="col-md-12" align="center"><h1>Create Schedule</h1></div>
    </div>
    <div class="container" >
        <div style="margin-top: 20px;"></div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(! \Auth::user()->verified)
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        We have already sent email verification to your email. Please check and confirm via the given link. Have not received yet? <a href="{!! route('user.resend_verification') !!}" class="alert-link">Resend email verification</a>.
                    </div>
                </div>
            </div>
        @endif


        @include('shared.message')

        <div class="row">
            <div class="col-md-12">
                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation"><a href="javascript:void(0)">Create job</a></li>
                        <li role="presentation" class="active"><a href="javascript:void(0)">Create Schedule</a></li>
                        <li role="presentation"><a href="javascript:void(0)">Broadcast Job</a></li>
                        <li role="presentation"><a href="javascript:void(0)">Payment</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="height30"></div>
                        <div role="tabpanel" class="tab-pane active" id="create_schedule">
                            <form id="create_job_schedule" method="POST" action="{{ route('api.schedule.job', ['id' => $id]) }}">

                                <div class="form-group">
                                    <label for="hours_per_day">Number of working hours</label>
                                    <select name="working_hours" class="form-control working_hours" id="hours_per_day">
                                        <option value="">Please select number of working hours</option>
                                        @for($i = 1; $i <=8; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-danger error-span"></span>
                                </div>

                                <div class="form-group">
                                    <label for="days_per_month">Number of working days</label>
                                    <select name="working_days" class="form-control working_days" id="days_per_month">
                                        <option value="">Please select number of working days</option>
                                        @for($i = 1; $i <= 30; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-danger error-span"></span>
                                </div>
                                <div class="form-group">
                                    <label for="pay_per_hour">Pay per hour - GBP</label>
                                    <select name="pay_per_hour" class="form-control pay_per_hour" id="pay_per_hour">
                                        <option value="">Please select per hour</option>
                                        @for($i = 8; $i <= 20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-danger error-span"></span>
                                </div>
                                <div class="form-group">
                                    <label>Wallet Debit Frequency</label>
                                    <div class="wallet_debit_frequency">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="wallet_debit_frequency" value="hourly">
                                                Pay Hourly
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="wallet_debit_frequency" value="daily">
                                                Pay Daily
                                            </label>
                                        </div>
                                    </div>
                                    <span class="text-danger error-span"></span>
                                </div>
                                <label for="">Details/Calculations</label>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td>Total working hours</td>
                                        <td class="total-working-hours"></td>
                                    </tr>
                                    <tr>
                                        <td>VAT fee 20%</td>
                                        <td class="vat-fee"></td>
                                    </tr>
                                    <tr>
                                        <td>Admin fee 14.99%</td>
                                        <td class="admin-fee"></td>
                                    </tr>
                                    <tr>
                                        <td>Your total for this job</td>
                                        <td class="grand-total-for-job"></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary pull-right">Next</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="height30"></div>
        <div class="row">

        </div>

    </div>
</div>

<div class="clearfix"></div>
@include('footer')

<script>
    $(document).ready(function(){

        // calculations

        $("select[name='pay_per_hour']").on("change", function(){
            var hoursPerDay = $("select[name='working_hours']").val();
            var workingDays = $("select[name='working_days']").val();
            var payPerHour = $(this).val();
            // make calculations
            var totalWorkingHours = hoursPerDay * workingDays;
            var basicTotal = totalWorkingHours * payPerHour;
            var VATFee = (basicTotal * 20) / 100;
            var adminFee = (basicTotal * 14.99) / 100;
            var grandTotal = basicTotal + VATFee + adminFee;
            $(".total-working-hours").text(totalWorkingHours);
            $(".vat-fee").text(VATFee);
            $(".admin-fee").text(adminFee);
            $(".grand-total-for-job").text(grandTotal);
        });


        $("form#create_job_schedule").on("submit", function(e){
            formErrors = new Errors();
            e.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    var nextUrl = "{{ route('job.broadcast', ['id' => $id]) }}";
                    window.location.href = nextUrl;
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    formErrors.record(errors);
                    formErrors.load();
                }
            });
        });
    });
</script>


</body>
</html>