
<!DOCTYPE html>
<html lang="en">
<head>


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    @include('style')

    <style type="text/css">
        .noborder ul,li { margin:0; padding:0; list-style:none;}
        .noborder .label { color:#000; font-size:16px;}

        h1{
            font-size: 30px;
            color: #000;
            text-transform: uppercase;
            font-weight: 300;
            text-align: center;
            margin-bottom: 15px;
        }
        table{
            width:100%;
            table-layout: fixed;

        }
        .tbl-header{
            background-color: #25b7c4;
        }
        .tbl-content{
            overflow-x:auto;
            margin-top: 0px;
            border: 1px solid #25b7c4;
        }
        th{
            padding: 20px 15px;
            text-align: left;
            font-weight: 500;
            font-size: 12px;
            color: #fff;
            text-transform: uppercase;
        }
        .tr
        {
            background: #25c481
        }
        td{
            padding: 15px;
            text-align: left;
            vertical-align:middle;
            font-weight: 300;
            font-size: 12px;
            color: #fff;
            border-bottom: solid 1px #25b7c4;
        ;
        }


        /* demo styles */

        @import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);


        /* follow me template */
        .made-with-love {
            margin-top: 40px;
            padding: 10px;
            clear: left;
            text-align: center;
            font-size: 10px;
            font-family: arial;
            color: #fff;
        }

        .made-with-love i {
            font-style: normal;
            color: #F50057;
            font-size: 14px;
            position: relative;
            top: 2px;
        }
        .made-with-love a {
            color: #fff;
            text-decoration: none;
        }
        .made-with-love a:hover {
            text-decoration: underline;
        }


        /* for custom scrollbar for webkit browser*/

        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px #25b7c4;
        ;
        }
        ::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px #25b7c4);
        }
    </style>

    <script >

        function set_loc(id,val)
        {
            $('#loc_id').val(id);
            $('#loc_val').val(val);
        }
        function set_cat(id,val)
        {
            $('#cat_id').val(id);
            $('#cat_val').val(val);
        }
        $(document).ready(function(){
            //$('.content-data').hide();
            $('.skeleton').show();

        });

        $(window).load(function(){
            $('.content-data').show();
            $('.skeleton').hide();
        });

    </script>

</head>
<body>

<?php $url = URL::to("/"); ?>

<!-- fixed navigation bar -->
@include('header')
@if(isset($feedbacks))
<section class="job-bg page job-list-page">
    <div class="container">
        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li>Feedback</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">Feedback :</h2>
        </div>

        <div class="category-info">
            <div class="row">
                <!-- recommended-ads -->
                <div class="col-sm-12 col-md-12">
                    <section>
                        <!--for demo wrap-->
                        <h1>Feedback</h1>
                        <div class="tbl-header">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                <tr>
                                    <th>appearance</th>
                                    <th>punctuality</th>
                                    <th>customer focused</th>
                                    <th>security conscious</th>
                                    <th>rating</th>
                                    <th>message</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tbl-content">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tbody>
                                @if(count($feedbacks) != 0)
                                @foreach($feedbacks as $feed)
                                    <tr class="tr">
                                        <td>{{ $feed->appearance }}</td>
                                        <td>{{ $feed->punctuality }}</td>
                                        <td>{{ $feed->customer_focused }}</td>
                                        <td>{{ $feed->security_conscious }}</td>
                                        <td>
                                            <?php
                                            for($i=1;$i<=5;$i++)
                                            {
                                                if($feedback >= $i)
                                                {
                                                    echo '<i class="fas fa-star" style="color: #ff0;font-size: 20px"></i>';
                                                }
                                                else
                                                {
                                                    echo '<i class="far fa-star" style="color: #000;font-size: 20px"></i>';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{{ $feed->message }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <h1>No Data To view</h1>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </section>

                </div>




            </div>
        </div>


    </div>
</section>
@endif
@if(isset($jobs))
    <section class="job-bg page job-list-page">
        <div class="container">
            <div class="breadcrumb-section">
                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <li><a href="{{URL::to('/')}}">Home</a></li>
                    <li>Open Jobs</li>
                </ol><!-- breadcrumb -->
                <h2 class="title">Open Jobs :</h2>
            </div>

            <div class="category-info">
                <div class="row">
                    <!-- recommended-ads -->
                    <div class="col-sm-12 col-md-12">
                        <section>
                            <!--for demo wrap-->
                            <h1>All Open Jobs</h1>
                            <div class="tbl-header">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                    <tr>
                                        <th>title</th>
                                        <th>address 1</th>
                                        <th>address 2</th>
                                        <th>created_by</th>
                                        <th>post code</th>
                                        <th>hour worked</th>
                                        <th>description</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="tbl-content">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                    @if(count($jobs) != 0)
                                        @foreach($jobs as $job)
                                            <tr class="tr">
                                                <td>{{ $job->title }}</td>
                                                <td>{{ $job->address_line1 . ' , ' . $job->address_line2 . ' , ' . $job->address_line3}}</td>
                                                <td>{{ $job->country . ' , ' . $job->city_town }}</td>
                                                <td>{{ $job->created_by }}</td>
                                                <td>{{ $job->post_code }}</td>
                                                <td>{{ $job->daily_working_hours }}</td>
                                                <td>{{ $job->description }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <h1>No Data To view</h1>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </section>

                    </div>




                </div>
            </div>


        </div>
    </section>
@endif
<script type="text/javascript">
    $(document).ready(function ($) {
        $('#hidden_post_code').on('blur', function() {
            if ($(this).val()!=''){
                $('.post_code').val($(this).val());
            }
        });
    });
    function getDistanceLength(distanceval){
        $('.distance').val(distanceval);
    }
    $(window).on("load resize ", function() {
        var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
        $('.tbl-header').css({'padding-right':scrollWidth});
    }).resize();
</script>

@include('footer')
</body>
</html>