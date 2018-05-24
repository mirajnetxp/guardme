@extends('layouts.dashboard-template')
  


@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Verification</li>
        </ol>                       
        <h2 class="title">
            @if($editprofile[0]->admin == 2)
                {{'Freelancer'}}
            @elseif($editprofile[0]->admin == 0)
                {{'Employer'}}
            @endif
         Verification</h2>
    </div>
@endsection

@section('content')
    <div class="profile job-profile">
        <div class="user-pro-section">
            <div class="profile-details section">
                <h2>Verification</h2>
                @if(Session::has('success'))
                    <div class="alert alert-success" style="padding: 10px">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('dashboard') }}" id="formID" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="verification_page" value="yes">

                    <input type="hidden" name="id" value="<?php echo $editprofile[0]->id; ?>">
                    <input type="hidden" name="currentphoto" value="<?php echo $editprofile[0]->photo;?>">
                    <input type="hidden" name="currentpassphoto" value="<?php echo $editprofile[0]->passphoto;?>">
                    <input type="hidden" name="currentaddressproof" value="<?php echo $editprofile[0]->address_proof;?>">
                    <input type="hidden" name="currentsiadoc" value="<?php echo $editprofile[0]->sia_doc;?>">
                    <input type="hidden" name="currentvisapage" value="<?php echo $editprofile[0]->visa_page;?>">
                    <input type="hidden" name="currentpasspage" value="<?php echo $editprofile[0]->pass_page;?>">

                    <div class="form-group">
                        <label for="nationality" >Nationality</label>
                        <select name="nationality" id="nationality" class="trackprogress form-control text-input">
                            <option value=""></option>
                            @if($countries->count())
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" <?php if($country->id==$editprofile[0]->nation_id){?> selected="selected" <?php } ?>>{{$country->name}}</option>                                                    
                                @endforeach
                            @endif
                        </select>                               
                    </div>
                    <div id="visa_no_field" class="form-group{{ $errors->has('visa_no') ? ' has-error' : '' }}">
                        <label for="visa_no">Visa Number</label>
                        <input id="visa_no" type="text" class="trackprogress form-control text-input" name="visa_no" value="<?php echo $editprofile[0]->visa_no;?>">
                        @if ($errors->has('visa_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('visa_no') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div id="niutr_no_field" class="form-group{{ $errors->has('niutr_no') ? ' has-error' : '' }}">
                        <label for="niutr_no" >NI or UTR Number</label>
                        <input id="niutr_no" type="text" class="form-control text-input" name="niutr_no" value="<?php echo $editprofile[0]->niutr_no;?>">
                        @if ($errors->has('niutr_no'))
                            <span class="help-block">
                                <strong>{{ $errors->first('niutr_no') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('work_category') ? ' has-error' : '' }}">
                        <label for="work_category">Work Category</label>
                        <div class="radio-box"> 
                        @if ($editprofile[0]->work_category == 1)
                           <div class="col-md-2"> {{ Form::radio('category', 1, true,['class'=>'height-10']) }} Door Supervisor</div>
                        @else
                           <div class="col-md-2">  {{ Form::radio('category', 1,'',['class'=>'height-10']) }} Door Supervisor</div>
                        @endif
                        @if ($editprofile[0]->work_category == 2)
                             <div class="col-md-2">{{ Form::radio('category', 2, true,['class'=>'height-10']) }} Security Guard</div>
                        @else
                             <div class="col-md-2">{{ Form::radio('category', 2,'',['class'=>'height-10']) }} Security Guard</div>
                        @endif
                        @if ($editprofile[0]->work_category == 3)
                             <div class="col-md-2">{{ Form::radio('category', 3, true,['class'=>'height-10']) }} Close Protection</div>
                        @else
                             <div class="col-md-2">{{ Form::radio('category', 3,'',['class'=>'height-10']) }} Close Protection</div>
                        @endif
                        </div>                                
                    </div>   
                    <div class="form-group{{ $errors->has('sia_license') ? ' has-error' : '' }}">
                        <label for="sia_licence" >SIA Number</label>
                        <input id="sia_licence" type="text" class="trackprogress form-control text-input" name="sia_licence" value="<?php echo old('sia_licence',$editprofile[0]->sia_licence);?>">

                        @if ($errors->has('sia_licence'))
                            <span class="help-block">
                                <strong>{{ $errors->first('sia_licence') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('sia_expirydate') ? ' has-error' : '' }}">
                        <label for="sia_expirydate" >SIA Expiry</label>
                        <?php
                            echo Form::input('date', 'sia_expirydate', old('siaexpiry',$editprofile[0]->sia_expirydate), ['class' => 'trackprogress form-control', 'placeholder' => 'Expiry Date']);
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="passphoto" >Passport photograph</label>
                        <input type="file" id="passphoto" name="passphoto" class="trackprogress form-control">
                        @if ($errors->has('passphoto'))
                            <span class="help-block" style="color:red;">
                                <strong>{{ $errors->first('passphoto') }}</strong>
                            </span>
                        @endif
                        @if (!empty($editprofile[0]->passphoto))
                            <span class="info-block" style="color:green;">
                                <strong>Document already uploaded</strong>
                            </span>
                        @endif                                
                    </div>
                        
                    <div class="form-group">
                        <label for="pass_page" >Passport information page</label>
                        <input type="file" id="pass_page" name="pass_page" class="trackprogress form-control">
                        @if ($errors->has('pass_page'))
                            <span class="help-block" style="color:red;">
                                <strong>{{ $errors->first('pass_page') }}</strong>
                            </span>
                        @endif
                        @if (!empty($editprofile[0]->pass_page))
                            <span class="info-block" style="color:green;">
                                <strong>Document already uploaded</strong>
                            </span>
                        @endif                                
                    </div>
                    <div class="form-group">
                        <label for="visa_page" >Visa page or Residency stamp</label>
                        <input type="file" id="visa_page" name="visa_page" class="trackprogress form-control">
                        @if ($errors->has('visa_page'))
                            <span class="help-block" style="color:red;">
                                <strong>{{ $errors->first('visa_page') }}</strong>
                            </span>
                        @endif
                        @if (!empty($editprofile[0]->visa_page))
                            <span class="info-block" style="color:green;">
                                <strong>Document already uploaded</strong>
                            </span>
                        @endif                                
                    </div>
                    <div class="form-group">
                        <label for="sia_doc">SIA License</label>
                        <input type="file" id="sia_doc" name="sia_doc" class="trackprogress form-control">
                        @if ($errors->has('sia_doc'))
                            <span class="help-block" style="color:red;">
                                <strong>{{ $errors->first('sia_doc') }}</strong>
                            </span>
                        @endif
                        @if (!empty($editprofile[0]->sia_doc))
                            <span class="info-block" style="color:green;">
                                <strong>Document already uploaded</strong>
                            </span>
                        @endif                                
                    </div>
                    <div class="form-group">
                        <label for="address_proof">Proof of Address</label>
                        <input type="file" id="address_proof" name="address_proof" class="trackprogress form-control">
                        @if ($errors->has('address_proof'))
                            <span class="help-block" style="color:red;">
                                <strong>{{ $errors->first('address_proof') }}</strong>
                            </span>
                        @endif
                        @if (!empty($editprofile[0]->address_proof))
                            <span class="info-block" style="color:green;">
                                <strong>Document already uploaded</strong>
                            </span>
                        @endif                                
                    </div>
                    <div class="form-group">
                        <div class="alert alert-info" role="alert">
                            After uploading, please take your Passport and SIA licence to the nearest post office for verification. This process will cost you £8.50.<br>
                            Once certified, send a copy of the document to your our Licenced Partner. Visit the verification page for more information.
                        </div>
                    </div>
                    <div class="preferences-settings section">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="agree" class="trackprogress validate[required]" value="agree" /> I Agree to the GuardME terms and conditions
                            </label>
                        </div>
                    </div>
                    <div class="buttons pull-right">
                        <?php if(config('global.demosite')=="yes"){?>
                                <button type="button" class="btn btndisable">Update</button> <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
                            <?php } else { ?>
                                <button type="submit" class="btn">
                                    Update
                                </button>
                            <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection