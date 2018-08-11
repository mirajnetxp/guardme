@extends('jobs.template')

@section('bread-crumb')
    <div class="breadcrumb-section">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="{{URL::to('/')}}">Home</a></li>
            <li><a href="{{URL::route('job.create')}}">Create Job</a></li>
            <li>Update Schedule</li>
        </ol><!-- breadcrumb -->
        <h2 class="title">Update Schedule</h2>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('shared.message')

    <form id="create_job_schedule" method="POST" action="{{ route('api.schedule.update.job', ['id' => $id]) }}">
        <input type="hidden" id="extraAmount" name="extraAmount">
        <div class="form-group row">
            <label class="col-sm-3" for="hours_per_day">Number of freelancers you need</label>
            <div class="col-sm-9">
                <input type="text" value="{{$job->number_of_freelancers}}"
                       placeholder="Number of freelacers you want to hire for this job"
                       class="number_of_freelancers form-control" name="number_of_freelancers">
                <span class="text-danger error-span"></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3" for="hours_per_day">Number of working hours</label>
            <div class="col-sm-9">
                <select name="working_hours" class="form-control working_hours" id="hours_per_day">
                    <option value="">Please select number of working hours</option>
                    @for($i = 1; $i <=8; $i++)
                        <option {{$job->daily_working_hours==$i?"selected":''}} value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <span class="text-danger error-span"></span>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3" for="days_per_month">Number of working days</label>
            <div class="col-sm-9">
                <select name="working_days" class="form-control working_days" id="days_per_month">
                    <option value="">Please select number of working days</option>
                    @for($i = 1; $i <= 30; $i++)
                        <option {{$job->monthly_working_days==$i?"selected":''}} value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <span class="text-danger error-span"></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3" for="pay_per_hour">Pay per hour - GBP</label>
            <div class="col-sm-9">
                <select name="pay_per_hour" class="form-control pay_per_hour " id="pay_per_hour">
                    <option value="">Please select per hour</option>
                    @for($i = 8; $i <= 20; $i++)
                        <option {{$job->per_hour_rate==$i?"selected":''}} value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <span class="text-danger error-span"></span>
            </div>
        </div>


        <label class="col-sm-3" for="">Current schedule and cost</label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <tbody>
                @foreach($job->schedules as $schedule)
                    <tr>
                        <td>{{date('d M Y',strtotime($schedule->start))}}</td>
                        <td>{{date('h:m a',strtotime($schedule->start))}}
                            to {{date('h:m a',strtotime($schedule->end))}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>Cost</td>
                    <td>{{'$ '.$currentTotalCost}}</td>
                </tr>
                </tbody>
            </table>
            {{--<p class="text-center">--}}
            {{--<span class="btn btn-info change-schedule" >Click hear to change schedule.</span>--}}
            {{--</p>--}}
        </div>


        <div class="hidden">

            <h2 class="text-center text-info">Extra cost Calculations</h2>
            <hr>
            <label class="col-sm-3" for="">Details</label>
            <div class="col-sm-9">
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
                    <tr>
                        <td class="aditional-for-job-text">Your gahe to pay additional</td>
                        <td class="aditional-for-job"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <h2 class="text-center text-info">Update Schedule</h2>
            <hr>
            <p class="text-center text-success">N.B: if you don't want to change the current schedule keep this field
                blank.</p>
            <label for=""></label>
            <div class="schedule_items">

            </div>
        </div>

        <button type="submit" class="btn btn-primary pull-right">Next</button>
    </form>

@endsection
<div class="schedule_items_clone_code hide">
    <div class="form-group row">
        <label class="col-sm-3">Start/End</label>
        <div class="col-sm-9">
            <div class="col-sm-6">
                <input type="text" class="start_date_time date-time-picker form-control" name="start_date_time[]">
                <span class="text-danger error-span"></span>
            </div>
            <div class="col-sm-6">
                <input type="text" readonly="readonly" class="end_date_time form-control" name="end_date_time[]">
                <span class="text-danger error-span"></span>
            </div>

        </div>
    </div>
</div>
@section('script')
    <script>
        $(document).ready(function () {


            $("body").on("change", ".start_date_time", function () {
                var itemIndex = $(".start_date_time").index(this);
                var number_of_days = $("select[name='working_days']").val();
                var working_hours = $("select[name='working_hours']").val();
                var end_time = moment($(this).val(), 'YYYY-MM-DD HH:mm').add(working_hours, 'h').format('YYYY-MM-DD HH:mm');
                $(this).parent().siblings().find('.end_date_time').val(end_time);
                if (itemIndex == 1) {
                    var st_date = $(this).val();
                    for (i = 2; i < $('.start_date_time').length; i++) {
                        var current_st_date = moment(st_date, 'YYYY-MM-DD HH:mm').add(1, 'd').format('YYYY-MM-DD HH:mm');
                        $(".start_date_time").eq(i).val(current_st_date);
                        $(".start_date_time").eq(i).trigger("change");
                        st_date = current_st_date;
                    }
                }
            });


            // trigger price dropdown when number of days changed so that schedule items get updated.
            $("select[name='working_days']").on("change", function () {
                $(".hidden").removeClass('hidden');
                $("select[name='pay_per_hour']").trigger('change');
            });

            $("input[name='number_of_freelancers']").on("change", function () {
                $(".hidden").removeClass('hidden');
                $("select[name='pay_per_hour']").trigger('change');
            });

            $("select[name='working_hours']").on("change", function () {
                $(".hidden").removeClass('hidden');
                $("select[name='pay_per_hour']").trigger('change');
            });

            // var lockr_nxturl = Lockr.get('nxturl');
            if (gm_nxturl != null && gm_nxturl != '{{URL::current()}}') {
                //alert('lll');
                window.location.href = gm_nxturl;

            }
            else {
                steps_check();
            }

            // calculations
            $("select[name='pay_per_hour']").on("change", function () {
                $(".hidden").removeClass('hidden');


                var numberOfFreelancers = $("input[name='number_of_freelancers']").val();
                var hoursPerDay = $("select[name='working_hours']").val();
                var workingDays = $("select[name='working_days']").val();
                var payPerHour = $(this).val();
                // make calculations
                var totalWorkingHours = (hoursPerDay * workingDays) * numberOfFreelancers;
                var basicTotal = (totalWorkingHours * payPerHour);
                var VATFee = (basicTotal * 20) / 100;
                var adminFee = (basicTotal * 14.99) / 100;
                var grandTotal = basicTotal + VATFee + adminFee;
                $(".total-working-hours").text(totalWorkingHours);
                $(".vat-fee").text(VATFee);
                $(".admin-fee").text(adminFee);
                $(".grand-total-for-job").text(grandTotal);

                var extra = grandTotal -
                {{$currentTotalCost}}
                if (extra >= 1) {
                    $("#extraAmount").val(extra)
                    $(".aditional-for-job-text").text("You have to pay extra.");
                    $(".aditional-for-job").text(extra);
                } else if (extra <= -1) {
                    $("#extraAmount").val(extra)
                    $(".aditional-for-job-text").text("You will get  refund.");
                    $(".aditional-for-job").text(extra * (-1));
                } else {
                    $("#extraAmount").val(0)
                    $(".aditional-for-job-text").text("Unchanged");
                    $(".aditional-for-job").text("0");
                }

                // add schedule items
                var item_htm = $(".schedule_items_clone_code").html();
                var item_specific_html = item_htm;
                var all_schedule_items_html = '';
                for (i = 0; i < workingDays; i++) {
                    item_specific_html = item_htm.replace("Start/End", 'Day ' + parseInt(i + 1));
                    all_schedule_items_html += item_specific_html;
                }
                $(".schedule_items").html(all_schedule_items_html);
                jQuery('.date-time-picker').datetimepicker({
                    //language:  'uk',
                    weekStart: 1,
                    todayBtn: 1,
                    autoclose: 1,
                    todayHighlight: 1,
                    startView: 2,
                    forceParse: 0,
                    showMeridian: 1,
                    minuteStep: 5
                });
            });


            $("form#create_job_schedule").on("submit", function (e) {
                formErrors = new Errors();
                e.preventDefault();
                $.ajax({
                    url: $(this).attr("action"),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {

                        var nextUrl = "{{ route('job.update.broadcast', ['id' => $id]) }}";

                        var step = JSON.parse(sessionStorage.getItem('steps'));
                        step.wstep2 = 'completed';
                        sessionStorage.setItem('steps', JSON.stringify(step));

                        sessionStorage.setItem('nxturl', nextUrl);
                        sessionStorage.setItem('nxtstep', 'wstep3');
                        window.location.href = nextUrl;
                    },
                    error: function (data) {
                        var errors = data.responseJSON;
                        formErrors.record(errors);
                        formErrors.load();
                    }
                });
            });
        })
        ;
    </script>
@endsection
