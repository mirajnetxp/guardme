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

        /*--------- END --------*/
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
                            <div class="row">

                            </div>
                            <table class="display nowrap table">
                                <tr>
                                    <td><h3>Profile Visibility</h3></td>
                                    <td>
                                        <h3>
                                            <label class="switch">
                                                <input id="visibality" name="visibality"
                                                       {{auth()->user()->freelancerSettings->visible==1?'checked':''}} type="checkbox">
                                                <div class="slider round"></div>
                                            </label>
                                        </h3>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="section job-short-info center balance">

                    </div>

                    <div class="section quick-rules job-postdetails">
                        <h4>Heading1</h4>
                        <ul>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                        </ul>
                    </div>


                    <div class="section quick-rules job-postdetails">
                        <h4>Heading2</h4>
                        <ul>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </li>
                            <li>
                                lorem ipsum dolor sit amet, consectetur adipiscing elit
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
    $("#visibality").click(function () {

        $.ajax({
            url: "{{URL::to('/')}}/settings/visibality",
            method: "GET",
            dataType: 'json',
            success: function (d) {

                if (d == '101') {
                    alert('Your Profile is now Public')
                } else {
                    alert('Your Profile is now Private')
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
</script>
</body>
</html>