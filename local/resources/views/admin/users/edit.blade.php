@extends('admin/layout/default')
@section('title', 'Edit Profile')
@section('content')
  <?php $url = URL::to("/"); ?>
        <div class="content">
            <div class="container-fluid">
                
                <div class="row">

                    <div class="col-md-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                  
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
        <div class="card">
          <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.addbalance') }}"novalidate>
              <?php
              use Responsive\User;$luser = User::where('id', $users[0]->id)->first();
              ?>
              <div class="header">
                  <h4 class="title">Current balance: {{ $luser->getBalance() }}</h4>
                </div>
              <div class="item form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="balance">Add points to balance
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="number" min="0" value="0" id="balance" name="balance" required="required" class="form-control border-input col-md-7 col-xs-12">
                  </div>
              </div>
              <input type="hidden" name="user" value="{{ $users[0]->id }}">
                  {{ csrf_field() }}

              <div class="ln_solid"></div>
              <div class="form-group">
                  <div class="col-md-6 col-md-offset-3">
                      <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
                  </div>
              </div>

          </form>
                     <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.edituser') }}" enctype="multipart/form-data" novalidate>
                       {{ csrf_field() }}  
                        <div class="header">
                          <h4 class="title">Edit User</h4>
                        </div>
                        <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Username <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" class="form-control border-input col-md-7 col-xs-12"  name="name" value="<?php echo $users[0]->name; ?>" required="required" type="text">
                           @if ($errors->has('name'))
                                      <span class="help-block" style="color:red;">
                                          <strong>{{ $errors->first('name') }}</strong>
                                      </span>
                                  @endif
               </div>
                        </div>
                        <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="email" id="email" name="email" required="required" value="<?php echo $users[0]->email; ?>" class="form-control border-input col-md-7 col-xs-12">
                @if ($errors->has('email'))
                                      <span class="help-block" style="color:red;">
                                          <strong>{{ $errors->first('email') }}</strong>
                                      </span>
                                  @endif
                          </div>
                        </div>
                        
                       
  <?php if($userid==1) {?>           
                        <div class="item form-group">
                          <label for="password" class="control-label col-md-3">Password <span class="required">*</span></label> 
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="password" type="text" name="password" value=""  class="form-control border-input col-md-7 col-xs-12">
                
                          </div>
                        </div>
              
  <?php } ?>
              
              
              <input type="hidden" name="savepassword" value="<?php echo $users[0]->password;?>">
              
              
                        
                        <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone">Phone <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="tel" id="phone" name="phone" required="required" data-validate-length-range="8,20" class="form-control border-input col-md-7 col-xs-12" value="<?php echo $users[0]->phone; ?>">
                          </div>
                        </div>
              <input type="hidden" name="id" value="<?php echo $users[0]->id; ?>">
              
              
              
               <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="photo">Photo <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="file" id="photo" name="photo" class="form-control border-input col-md-7 col-xs-12">
                @if ($errors->has('photo'))
                                      <span class="help-block" style="color:red;">
                                          <strong>{{ $errors->first('photo') }}</strong>
                                      </span>
                                  @endif
                          </div>
                        </div>
               <?php $url = URL::to("/"); ?>
              <?php 
               $userphoto="/userphoto/";
              $path ='/local/images'.$userphoto.$users[0]->photo;
              if($users[0]->photo!=""){
              ?>
              <div class="item form-group" align="center">
              <div class="col-md-6 col-sm-6 col-xs-12">
              <img src="<?php echo $url.$path;?>" class="thumb" width="100">
              </div>
              </div>
              <?php } else { ?>
              <div class="item form-group" align="center">
              <div class="col-md-6 col-sm-6 col-xs-12">
              <img src="<?php echo $url.'/local/images/nophoto.jpg';?>" class="thumb" width="100">
              </div>
              </div>
              <?php } ?>
              
              <input type="hidden" name="currentphoto" value="<?php echo $users[0]->photo;?>">
              
              
              
              
              
              <?php if($users[0]->admin!=1){?>
               <div class="item form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="usertype">User Type <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="usertype" required="required" class="form-control border-input col-md-7 col-xs-12">
              <option value=""></option>
                   <option value="0" <?php if($users[0]->admin==0){?> selected="selected" <?php } ?>>Customer</option>
                   <option value="2" <?php if($users[0]->admin==2){?> selected="selected" <?php } ?>>Seller</option>
              </select>
                            
                          </div>
                        </div>
              <?php } ?>
              
              
              <?php if($users[0]->admin==1){?>
              
              <input type="hidden" name="usertype" value="<?php echo $users[0]->admin;?>">
              
              <?php } ?>
              
                       
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-md-offset-3">
                            <a href="<?php echo $url;?>/admin/users" class="btn btn-primary">Cancel</a>
                
                
                <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button> 
                  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>
                            <button id="send" type="submit" class="btn btn-info btn-fill btn-wd">Submit</button>
                <?php } ?>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    
                    
                </div>
            </div>
      
@endsection
