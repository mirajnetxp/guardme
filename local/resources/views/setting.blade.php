<!DOCTYPE html>
<html lang="en">
<head>
    @include('style')
    <style src="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"></style>
    <style src="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"></style>
    <style type="text/css">
        .switch {
            position: relative;
            display: inline-block;
            width: 90px;
            height: 34px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ca2222;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        .slider2 {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ca2222;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        /*.slider:before {*/
        /*position: absolute;*/
        /*content: "";*/
        /*height: 26px;*/
        /*width: 26px;*/
        /*left: 4px;*/
        /*bottom: 4px;*/
        /*background-color: white;*/
        /*-webkit-transition: .4s;*/
        /*transition: .4s;*/
        /*border-radius: 50%;*/
        /*}*/

        input:checked + .slider {
            background-color: #2ab934;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(55px);
        }

        input:checked + .slider2 {
            background-color: #2ab934;
        }

        input:focus + .slider2 {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider2:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(55px);
        }

        /*------ ADDED CSS ---------*/
        .slider:after {
            content: 'Private';
            color: white;
            display: block;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            font-size: 10px;
            font-family: Verdana, sans-serif;
        }

        input:checked + .slider:after {
            content: 'Public';
        }

        .slider2:after {
            content: 'No Consent';
            color: white;
            display: block;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            font-size: 10px;
            font-family: Verdana, sans-serif;
        }

        input:checked + .slider2:after {
            content: 'Consent';
        }

        /*--------- END --------*/
        /* The container */
        .radio-container {
            /*display: block;*/
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default radio button */
        .radio-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom radio button */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 50%;
        }

        /* On mouse-over, add a grey background color */
        .radio-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .radio-container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .radio-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .radio-container .checkmark:after {
            top: 9px;
            left: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
        }

        /* Tooltip container */
        .tooltipc {
            position: relative;
            display: inline-block;
            /*border-bottom: 1px dotted black; !* If you want dots under the hoverable text *!*/
        }

        /* Tooltip text */
        .tooltipc .tooltiptextc {
            visibility: hidden;
            width: 250px;
            background-color: #555;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            bottom: 65%;
            left: 50%;
            margin-left: -125px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* Tooltip arrow */
        .tooltipc .tooltiptextc::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltipc:hover .tooltiptextc {
            visibility: visible;
            opacity: 1;
        }

        .divSty {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
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
                <li>Settings</li>
            </ol>
            <h2 class="title">Settings</h2>
        </div>


        <div class="adpost-details post-resume">


            <div class="row">
                <div class="col-md-8">
                    <div class="section postdetails">
                        <div class="description-info">
                            <h2>Settings</h2>
                            {{--Payment Method--}}
                            <div class="row divSty">

                                <div class="col-md-5">
                                    <h4>Payment Method
                                        <div class="tooltipc">
                                            <span class="glyphicon glyphicon-question-sign"></span>
                                            <span class="tooltiptextc">Add your Paypal or Bank details here. Payment is made every Friday and Monday.</span>
                                        </div>

                                    </h4>

                                </div>
                                <div class="col-md-7">
                                    @if($paymethod)
                                        <h3 class="pmm">
                                            <label class="radio-container">Paypal
                                                <input type="radio" value="payple"
												       <?php if ( $paymethod->method_type == 'payple' )
													       echo 'checked' ?> name="payment_method">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio-container">Bank
                                                <input type="radio" value="bank"
												       <?php if ( $paymethod->method_type == 'bank' )
													       echo 'checked' ?> name="payment_method">
                                                <span class="checkmark"></span>
                                            </label>
                                        </h3>
                                        @if($paymethod->method_type == 'payple')
                                            Payple Email : {{$paymethod->method_details}}
                                        @else
                                            @php($det=json_decode($paymethod->method_details))
                                            <p class="text-center" style="border-bottom: 1px solid;">Bank
                                                details</p>

                                            Bank name : <strong>{{$det->bank_name}}</strong>
                                            <br>
                                            Account Name : <strong>{{$det->ac_name}}</strong>
                                            <br>
                                            Sort Code : <strong>{{$det->sort_code}}</strong>
                                            <br>
                                            Bank Account Number : <strong>{{$det->ac_number}}</strong>
                                        @endif
                                    @else
                                        <h3 class="pmm hidden">
                                            <label class="radio-container">Paypal
                                                <input type="radio" value="payple" name="payment_method">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="radio-container">Bank
                                                <input type="radio" value="bank" name="payment_method">
                                                <span class="checkmark"></span>
                                            </label>
                                        </h3>
                                    @endif
                                    <div>
                                    </div>
                                    {{--Model--}}
                                    <div class="modal fade" id="payment-method-model" tabindex="-1" role="dialog"
                                         aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top: 25px">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">

                                                    <h4 class="modal-title"></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="p_method_detail_form" method="POST"
                                                          action="add/payment/method">
                                                        <input type="hidden" name="method_type" value="payple">

                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    <button type="submit" form="p_method_detail_form"
                                                            class="btn btn-success">Save
                                                    </button>

                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                </div>


                            </div>

                            <div class="row divSty">

                                <div class="col-md-5">
                                    <h4>Profile Visibility
                                        <div class="tooltipc">
                                            <span class="glyphicon glyphicon-question-sign"></span>
                                            <span class="tooltiptextc">Make your profile hidden or visible on the personnel search page.</span>
                                        </div>
                                    </h4>

                                </div>
                                <div class="col-md-7">
                                    <h3>
                                        <label class="switch">
                                            @if($visible)
                                                <input id="visibality" name="visibality"
                                                       type="checkbox" checked>
                                            @else
                                                <input id="visibality" name="visibality"
                                                       type="checkbox">
                                            @endif
                                            <div class="slider round"></div>
                                        </label>
                                    </h3>
                                </div>


                            </div>
                            <div class="row divSty">

                                <div class="col-md-5">
                                    <h4>GPS Tracking
                                        <div class="tooltipc">
                                            <span class="glyphicon glyphicon-question-sign"></span>
                                            <span class="tooltiptextc">Accept or decline GPS app tracking.</span>
                                        </div>
                                    </h4>
                                </div>
                                <div class="col-md-7">
                                    <h3>
                                        <label class="switch" style="width: 140px">
                                            @if($settings->gps)
                                                <input id="gps" name="gps"
                                                       type="checkbox" checked>
                                            @else
                                                <input id="gps" name="gps"
                                                       type="checkbox">
                                            @endif
                                            <div class="slider2 round"></div>
                                        </label>
                                    </h3>
                                </div>

                            </div>
                            @if (auth()->user()->admin == 2)
                                <div class="row">
                                    <div class="col-md-5"><h4>Close your account</h4></div>
                                    <div class="col-md-7">


                                        <h4>
                                            <a href="{{URL::to('delete_account')}}" class="btn"
                                               style="margin-left: 0!important">Close account</a>
                                        </h4>
                                        <p> Please let us know if you are disatisfied with our service before requesting
                                            a closure.
                                            After closing your account, we will be unable to retrieve the data.
                                            If you have a problem that the Licensed Partner has not resolved within our
                                            SLA (72
                                            hours),
                                            send a message to complaints@guaddme.com.</p>

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="section job-short-info center balance">
                        Here you can modify your account settings.
                        Click save when you're done to keep any changes.
                    </div>

                    <div class="section quick-rules job-postdetails">
                        <h4>Close Account</h4>
                        <ul>
                            <li>
                                Our account closure process complies with GDPR regulations.
                            </li>
                            <li>
                                We will close the account within 48 hours of your request.
                            </li>
                            <li>
                                All balances will need to be settled before account closure commences.
                            </li>


                        </ul>
                    </div>


                    <div class="section quick-rules job-postdetails">
                        <h4>GPS Settings</h4>
                        <ul>
                            <li>
                                We will respect your privacy by turning off our GPS tracker if you select the option.
                            </li>
                            <li>
                                But this information will be made available to employers.
                            </li>
                            <li>
                                Some employers may only want to hire freelancers who have this option activated.
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@include('footer')


<script>


    $(document).ready(function () {
        $('.pmm').removeClass('hidden');
        $('input[type=radio][name=payment_method]').change(function () {
            if (this.value == 'payple') {
                $('.modal-title').html('Paypal Email')
                $('#p_method_detail_form').html('{{csrf_field()}}' + '<input type="hidden" name="method_type" value="payple"><div id="paypalEmail">\n' +
                    '                                                            <h5><input type="email" name="payple_email" class="form-control"\n' +
                    '                                                                       placeholder="Paypal email"></h5>\n' +
                    '                                                        </div>')
                $('#payment-method-model').modal('show')

            }
            else if (this.value == 'bank') {
                $('.modal-title').html('Bank Details')
                $('#p_method_detail_form').html('{{csrf_field()}}' + '<input type="hidden" name="method_type" value="bank"><div id="bankDetail">\n' +
                    '                                                            <h5><input type="text" class="form-control"\n' +
                    '                                                                       placeholder="Bank Name" name="bank_name"></h5>\n' +
                    '                                                            <h5><input type="text" class="form-control"\n' +
                    '                                                                       placeholder="Account Name" name="ac_name"></h5>\n' +
                    '                                                            <h5><input type="text" class="form-control"\n' +
                    '                                                                       placeholder="Sort Code" name="sort_code"></h5>\n' +
                    '                                                            <h5><input type="text" class="form-control"\n' +
                    '                                                                       placeholder="Bank Account Number" name="ac_number"></h5>\n' +
                    '                                                        </div>')
                $('#payment-method-model').modal('show')
            }
        });

        $("#visibality").click(function () {

            $.ajax({
                url: "{{URL::to('/')}}/settings/visibality",
                method: "GET",
                dataType: 'json',
                success: function (d) {

                    if (d == '101') {
                        alert('Your profile is now public.')
                        $(this).attr("checked", "true");
                    } else {
                        alert('Your profile is now private.')
                        $(this).attr("checked", "false");
                    }
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

        $("#gps").click(function () {

            $.ajax({
                url: "/settings/gps",
                method: "GET",
                dataType: 'json',
                success: function (d) {

                    if (d == '101') {
                        alert('GPS Tracking is Active')
                        $(this).attr("checked", "true");
                    } else {
                        alert('GPS Tracking is Inactive')
                        $(this).attr("checked", "false");
                    }
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
