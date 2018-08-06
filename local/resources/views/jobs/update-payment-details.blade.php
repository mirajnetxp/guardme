@extends('jobs.template')

@section('bread-crumb')
    <div class="breadcrumb-section">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="{{URL::to('/')}}">Home</a></li>
            <li><a href="URL::route('job.create')">Create Job</a></li>
            <li>Payment Details</li>
        </ol><!-- breadcrumb -->
        <h2 class="title">Payment Details</h2>
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
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @include('shared.message')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Item</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Daily working hours</td>
            <td>{{ $jobDetails['daily_working_hours'] }}</td>
        </tr>
        <tr>
            <td>Monthly working days</td>
            <td>{{ $jobDetails['monthly_working_days'] }}</td>
        </tr>
        <tr>
            <td>Per hour rate</td>
            <td>{{ $jobDetails['per_hour_rate'] }}</td>
        </tr>
        <tr>
            <td>Total working hours per month</td>
            <td>{{ $jobDetails['total_working_hours_per_month'] }}</td>
        </tr>
        <tr>
            <td>Basic Total</td>
            <td>{{ $jobDetails['basic_total'] }}</td>
        </tr>
        <tr>
            <td>VAT Fee 20%</td>
            <td>{{ $jobDetails['vat_fee'] }}</td>
        </tr>
        <tr>
            <td>Admin Fee 14.99%</td>
            <td>{{ $jobDetails['admin_fee'] }}</td>
        </tr>
        <tr>
            <td>Grand Total</td>
            <td>{{ $jobDetails['grand_total'] }}</td>
        </tr>
        </tbody>
    </table>
    @if(!$jobDetails['job_activation_status'])
        @if($currentCost < $costAfterEdit)
            <form action="{{ route('create.extra.paypal.payment', ['id' => $id]) }}" method="post">
                {{ csrf_field() }}
                <input type="submit" class="btn pull-right btn-success" value="Pay with Paypal">
            </form>
        @elseif($currentCost >= $costAfterEdit)
            <form method="post" id="activate_job" action="{{ route('api.update.activate.job', ['id' => $id]) }}">
                <input type="submit" class="btn pull-right btn-success" value="Save Change">
            </form>
        @endif
    @endif
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            if (gm_nxturl != null && gm_nxturl != '{{URL::current()}}') {
                //alert('lll');
                window.location.href = gm_nxturl;

            }
            else {
                steps_check();
            }

            $("form#activate_job").on("submit", function (e) {
                formErrors = new Errors();
                e.preventDefault();
                $.ajax({
                    url: $(this).attr("action"),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {

                        var nextUrl = "{{ route('job.confirmation') }}";

                        var step = JSON.parse(sessionStorage.getItem('steps'));
                        step.wstep4 = 'completed';
                        sessionStorage.setItem('steps', JSON.stringify(step));

                        sessionStorage.setItem('nxturl', nextUrl);
                        sessionStorage.setItem('nxtstep', 'wstep5');
                        window.location.href = nextUrl;
                    },
                    error: function (data) {

                        var errors = data.responseJSON;
                        formErrors.record(errors);
                        formErrors.load();
                    }
                });
            });
        });
    </script>
@endsection