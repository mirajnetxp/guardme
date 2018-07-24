<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')
    <style>
        .dataTables_filter {
            display: none;
        }

        .fa-base {
            display: none;
        }

        .send-message {
            position: fixed;
            right: 44px;
            bottom: 10px;
            z-index: 999999999999;
            background-color: green;
            color: white;
            padding: 12px;
        }

        .send-message a {
            color: white;
        }

        .send-message:hover {
            background-color: black;
        }
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

	<?php $url = URL::to( "/" ); ?>

    <!-- /top navigation -->
        <div class="send-message">
            <a href="javascript:void(0);"> Send Message
                <i class="fa fa-envelope"></i></a>
        </div>
        <!-- page content -->
        <div class="content">
            <!-- top tiles -->

            <style>
                div.dataTables_wrapper div.dataTables_filter input {
                    border: 1px solid #000;
                }
            </style>


            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="padding:15px;">
                        <div class="header">
                            <h4 class="title">Newsletter</h4>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table id="datatable-newsletter" class="table table-striped table-bordered dt-responsive nowrap"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    {{--<th></th>--}}
                                    <th>Sn</th>
                                    <th>Email</th>
                                    {{--<th>Status</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @php($i=1)
                                @foreach($allNewsletter as $Newsletter)
                                    <tr>
                                        {{--<td></td>--}}
                                        <td>{{$i++}}</td>
                                        <td>{{$Newsletter->email}}</td>
                                        {{--<td></td>--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


            </div>
            <!-- /page content -->
        </div>
        @include('admin.footer')
    </div>
    <div id="myModel" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="sendmessageform" action="{{$url}}/admin/message" method="post">
                    {{csrf_field()}}
                    <div class="modal-header">
                        <h5 class="modal-title">Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea name="message" class="form-control" id="" cols="30" rows="10"
                                  placeholder="Enter message here" require></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/js/date-time-picker/bootstrap-datetimepicker.min.js"></script>
<script src="/js/date-time-picker/bootstrap-datetimepicker.uk.js"></script>
<script src="{{asset('/js/moment.js')}}"></script>

<script>
    //    User Filtering
    $(document).ready(function () {
        var selected_user;
        var table = $('#datatable-newsletter').DataTable();

        {{--$('#gender').on('change', function () {--}}
            {{--table--}}
                {{--.columns(1)--}}
                {{--.search(this.value)--}}
                {{--.draw();--}}
        {{--});--}}
        {{--$('#location_filter').on('keyup', function () {--}}
            {{--table--}}
                {{--.columns(2)--}}
                {{--.search(this.value)--}}
                {{--.draw();--}}
        {{--});--}}
        {{--$('#date_reset').click(function () {--}}
            {{--$('#date_filter_max').val('')--}}
            {{--$('#date_filter_min').val('')--}}
            {{--table.draw();--}}
        {{--})--}}

        {{--$.fn.dataTable.ext.search.push(--}}
            {{--function (settings, data, dataIndex) {--}}
                {{--var min = moment($('#date_filter_max').val()).format('YYYYMMDD');--}}
                {{--var max = moment($('#date_filter_min').val()).format('YYYYMMDD');--}}
                {{--var age = parseFloat(data[3]) || 0; // use data for the age column--}}

                {{--if (( isNaN(min) && isNaN(max) ) ||--}}
                    {{--( isNaN(min) && age <= max ) ||--}}
                    {{--( min <= age && isNaN(max) ) ||--}}
                    {{--( min <= age && age <= max )) {--}}
                    {{--return true;--}}
                {{--}--}}
                {{--return false;--}}
            {{--}--}}
        {{--);--}}

        {{--$('#date_filter_max,#date_filter_min').on('change', function () {--}}
            {{--table.draw();--}}
        {{--});--}}

        {{--$(".send-message").click(function (e) {--}}

            {{--e.preventDefault();--}}
            {{--userid = [];--}}
            {{--$("input:checkbox[name=selecteduser]:checked").each(function () {--}}
                {{--userid.push($(this).val());--}}
            {{--});--}}

            {{--if (userid.length == 0) {--}}
                {{--alert("Please select user");--}}
                {{--return;--}}
            {{--}--}}

            {{--$("#sendmessageform")[0].reset();--}}
            {{--selected_user = userid;--}}
            {{--$("#myModel").modal('show');--}}


        {{--});--}}
        {{--$("#sendmessageform").submit(function (e) {--}}
            {{--e.preventDefault();--}}
            {{--data = $(this).serialize();--}}
            {{--data += "&user=" + JSON.stringify(selected_user);--}}
            {{--var request = $.ajax(--}}
                {{--{--}}
                    {{--url: "{{$url}}/admin/message",--}}
                    {{--data: data,--}}
                    {{--method: "POST",--}}

                {{--}--}}
            {{--);--}}
            {{--request.done(function (msg) {--}}
                {{--$("#myModel").modal('hide');--}}
                {{--alert("Message send successfully");--}}
            {{--});--}}

        {{--});--}}
        {{--//   End User  Filtering--}}
    });


</script>
</body>
</html>
