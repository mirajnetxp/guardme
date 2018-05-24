@extends('layouts.dashboard-template')
  


@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Company Info</li>
        </ol>                       
        <h2 class="title">
           Company Info</h2>
    </div>
@endsection

@section('content')
    <div class="profile job-profile">
        <div class="user-pro-section">
            <div class="profile-details section">

                <h2>
                    Company Info
                </h2>
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


                <form method="POST" action="{{ route('update-company') }}" id="formID" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="company_id" value="{{$editprofile[0]->company->id}}">

                        <div class="form-group{{ $errors->has('shop_name') ? ' has-error' : '' }}">
                            <label>Name</label>
                            <input id="shop_name" type="text" class="form-control validate[required] text-input" name="shop_name" value="{{$editprofile[0]->company->shop_name}}" autofocus>
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
                                    @if($editprofile[0]->company->business_categoryid==$cat->id) {{"selected=selected"}} @endif>{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label >Phone NO</label>
                            <input id="phone" type="text" class=" form-control  text-input" name="phone" value="<?php echo $editprofile[0]->company->shop_phone_no;?>">
                        </div>
                        <div class="form-group{{ $errors->has('company_email') ? ' has-error' : '' }}">
                            <label for="company_email">E-Mail Address</label>
                            <input id="company_email" type="text" class="trackprogress form-control validate[required,custom[email]] text-input" name="company_email" value="<?php echo $editprofile[0]->company->company_email;?>">

                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input id="address" type="text" class=" form-control  text-input" name="address" value="<?php echo $editprofile[0]->company->address;?>">

                        </div>


                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class=" form-control" >{{$editprofile[0]->company->description}}</textarea>

                        </div>

                            <div class="buttons pull-right">
                                <button type="submit" class="btn">Update</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
@endsection