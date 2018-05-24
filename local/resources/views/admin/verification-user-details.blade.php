<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')


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
                        <div class="header">
                            <h3 class="pull-left">User Details</h3>
                            <h3 class="pull-right ">
                                @if(!$user->doc_verified)
                                    <a class="btn btn-success"
                                       href="{{route('admin.user.doc.approved',['id'=>$user->id])}}">Verification
                                        Approved</a>
                                @else
                                    <a class="btn btn-danger"
                                       href="{{route('admin.user.doc.decline',['id'=>$user->id])}}">Unverified</a>
                                @endif
                            </h3>
                        </div>
                    </div>
                    <hr>
                    <form class="form-horizontal" style="padding: 10px" method="get" action=""
                          enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-4">Nationality</label>
                            <input class="form-control disabled trackprogress text-input" value="{{$user->nation_id}}">
                        </div>

                        <div id="visa_no_field" class="form-group">
                            <label class="col-md-4">Visa Number</label>
                            <input id="visa_no" type="text" class="trackprogress form-control text-input"
                                   name="{{$user->visa_no}}" value="">
                        </div>


                        <div id="niutr_no_field" class="form-group">
                            <label class="col-md-4">NI or UTR Number</label>
                            <input id="niutr_no" type="text" class="form-control text-input" name="niutr_no"
                                   value="{{$user->niutr_no}}">
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">Work Category</label>
                            <div class="col-md-2"> Door Supervisor</div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">SIA Number</label>
                            <div class="col-md-3">
                                @if($user->sia_licence)
                                    {{$user->sia_licence}}
                                @else
                                    Not Provided
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">SIA Expiry</label>
                            <div class="col-md-3">
                                {{date('d M ,Y', strtotime($user->sia_expirydate) )}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">Passport photograph</label>
                            @if($user->passphoto)
                                <img height="200" width="200"
                                     src="{{asset('/local/images/userdoc/'.$user->passphoto)}}">
                            @else
                                Not Uploaded
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="col-md-4">Passport information page</label>
                            @if($user->pass_page)
                                <img height="200" width="200"
                                     src="{{asset('/local/images/userdoc/'.$user->pass_page)}}">
                            @else
                                Not Uploaded
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">Visa page or Residency stamp</label>
                            @if($user->visa_page)
                                <img height="200" width="200"
                                     src="{{asset('/local/images/userdoc/'.$user->visa_page)}}">
                            @else
                                Not Uploaded
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">SIA License</label>
                            @if($user->sia_doc)
                                <img height="200" width="200"
                                     src="{{asset('/local/images/userdoc/'.$user->sia_doc)}}">
                            @else
                                Not Uploaded
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">Proof of Address</label>
                            @if($user->address_proof)
                                <img height="200" width="200"
                                     src="{{asset('/local/images/userdoc/'.$user->address_proof)}}">
                            @else
                                Not Uploaded
                            @endif
                        </div>

                    </form>
                </div>

            </div>

        </div>


        <!-- /page content -->

        @include('admin.footer')
    </div>
</div>


</body>
</html>
