<!DOCTYPE html>
<html lang="en">
<head>

    @include('admin.title')

    @include('admin.style')
    <style>
        .form-control{
            box-shadow: 0 0 1px 0px #425982 !important;
        }
    </style>

</head>

<body>
<?php $url = URL::to( "/" ); ?>
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
                            <h4 class="title">Edit Companies</h4>
                            <!-- <p class="category">Here is a subtitle for this table</p> -->
                        </div>
                        <div class="col-sm-12">
                            <form method="POST" action="{{ route('update-company') }}" id="formID" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <input type="hidden" name="company_id" value="{{$editshop->id}}">

                                <div class="form-group{{ $errors->has('shop_name') ? ' has-error' : '' }}">
                                    <label>Name</label>
                                    <input id="shop_name" type="text" class="form-control validate[required] text-input" name="shop_name" value="{{$editshop->shop_name}}" autofocus>
                                    @if ($errors->has('shop_name'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('shop_name') }}</strong>
                                </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select id="shop_category" name="shop_category" class="form-control">
                                        @foreach($b_cats as $cat)
                                        <option value="{{$cat->id}}"
                                        @if($editshop->business_categoryid==$cat->id) {{"selected=selected"}} @endif>{{$cat->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label >Phone NO</label>
                                    <input id="phone" type="text" class="form-control  text-input" name="phone" value="{{$editshop->shop_phone_no}}">
                                </div>
                                <div class="form-group{{ $errors->has('company_email') ? ' has-error' : '' }}">
                                    <label for="company_email">E-Mail Address</label>
                                    <input id="company_email" type="text" class="trackprogress form-control validate[required,custom[email]] text-input" name="company_email" value="{{$editshop->company_email}}">

                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input id="address" type="text" class="form-control  text-input" name="address" value="{{$editshop->address}}">

                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control" >{{$editshop->description}}</textarea>

                                </div>

                                <div class="buttons pull-right">
                                    <button type="submit" class="btn">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- /page content -->

        @include('admin.footer')
    </div>
</div>


</body>
</html>
