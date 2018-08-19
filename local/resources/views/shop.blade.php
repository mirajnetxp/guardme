@extends('layouts.dashboard-template')
@section('script')
    <script src="{{ asset('js/vue_axios.js') }}"></script>
    <script src="{{ asset('js/phone.min.js') }}"></script>
@endsection
@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Profile Details</li>
        </ol>
        <h2 class="title">
            @if($editprofile[0]->admin == 2)
                {{'Freelancer'}}
            @elseif($editprofile[0]->admin == 0)
                {{'Employer'}}
            @endif
            Profile</h2>
    </div>
@endsection

@section('content')
    <div class="profile job-profile">
        <div class="user-pro-section">
            <div class="profile-details section">

                <h2>
                    @if($editprofile[0]->admin == 2)
                        {{'Profile'}}
                    @elseif($editprofile[0]->admin == 0)
                        {{'Employer'}}
                    @endif Details
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


                <div class="alert alert-info" role="alert">
                    Please complete your profile below. You will only be eligible to apply for work after your profile
                    is complete and your documents are approved.
                </div>
                <form method="POST" action="{{ route('dashboard') }}" id="formID" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" name="profile_page" value="yes">

                    <input type="hidden" name="id" value="<?php echo $editprofile[0]->id; ?>">
                    <input type="hidden" name="usertype" value="<?php echo $editprofile[0]->admin;?>">
                    <input type="hidden" name="savepassword" value="<?php echo $editprofile[0]->password;?>">
                    @if(count($address) >0)
                        <input type="hidden" id="addresslat" name="addresslat" value="{{$address[0]->latitude}}">
                        <input type="hidden" id="addresslong" name="addresslong" value="{{$address[0]->longitude}}">
                    @else
                        <input type="hidden" id="addresslat" name="addresslat" value="">
                        <input type="hidden" id="addresslong" name="addresslong" value="">
                    @endif

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>Username</label>
                        <input id="name" type="text" class="trackprogress form-control validate[required] text-input"
                               name="name" value="<?php echo $editprofile[0]->name;?>" autofocus>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                        <label for="firstname">First Name</label>
                        <input id="firstname" type="text"
                               class="trackprogress form-control validate[required] text-input" name="firstname"
                               value="<?php echo $editprofile[0]->firstname;?>" autofocus>
                        @if ($errors->has('firstname'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                        <label for="lastname">Last Name</label>
                        <input id="lastname" type="text"
                               class="trackprogress form-control validate[required] text-input" name="lastname"
                               value="<?php echo $editprofile[0]->lastname;?>" autofocus>
                        @if ($errors->has('lastname'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">E-Mail Address</label>
                        <input id="email" type="text"
                               class="trackprogress form-control validate[required,custom[email]] text-input"
                               name="email" value="<?php echo $editprofile[0]->email;?>">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('address_id') ? ' has-error' : '' }}">
                        <label for="address">Address</label>

                        <div id="postcode_lookup"></div>
                        <br/>
                        <div class="pull-left left-112"><label>&nbsp;</label>
                            <p>Please fetch your address detail using your postcode</p></div>
                        @if ($errors->has('address_id'))
                            <span class="help-block">
                                            <strong>{{ $errors->first('address_id') }}</strong>
                                        </span>
                        @endif
                    <!-- Add to your existing form -->
                        @if(count($address) >0)
                            <input id="line1" name="line1"
                                   class="trackprogress form-control text-input validate[required] addr-line"
                                   type="text" placeholder="Address line1" value="{{$address[0]->line1}}">
                            <input id="line2" name="line2" class="trackprogress form-control text-input addr-line"
                                   type="text" placeholder="Address line2" value="{{$address[0]->line2}}">
                            <input id="line3" name="line3" class="trackprogress form-control text-input addr-line"
                                   type="text" placeholder="Address line3" value="{{$address[0]->line3}}">
                            <input id="town" name="town"
                                   class="trackprogress form-control text-input validate[required] addr-line"
                                   type="text" placeholder="Town" value="{{$address[0]->citytown}}">
                            <input id="country" name="country"
                                   class="trackprogress form-control text-input  validate[required] addr-line"
                                   type="text" placeholder="Country" value="{{$address[0]->country}}">
                            <input id="postcode" name="postcode"
                                   class="trackprogress form-control text-input  validate[required] addr-line"
                                   type="text" placeholder="Postalcode" value="{{$address[0]->postcode}}">

                        @else
                            <input id="line1" name="line1"
                                   class="trackprogress form-control text-input validate[required] addr-line"
                                   type="text" placeholder="Address line1" value="">
                            <input id="line2" name="line2" class="trackprogress form-control text-input addr-line"
                                   type="text" placeholder="Address line2" value="">
                            <input id="line3" name="line3" class="trackprogress form-control text-input addr-line"
                                   type="text" placeholder="Address line3" value="">
                            <input id="town" name="town"
                                   class="trackprogress form-control text-input validate[required] addr-line"
                                   type="text" placeholder="Town" value="">
                            <input id="country" name="country"
                                   class="trackprogress form-control text-input  validate[required] addr-line"
                                   type="text" placeholder="Country" value="">
                            <input id="postcode" name="postcode"
                                   class="trackprogress form-control text-input  validate[required] addr-line"
                                   type="text" placeholder="Postalcode" value="">
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="trackprogress form-control" name="password"
                               value="">
                    </div>
                    <div id='phoneVue'>

                        <h4 class="text-center page-title">
                            <i class="fa fa-phone"></i>

                            <template v-if="action === 'new'"> Phone verification</template>
                            <template v-if="action === 'unbind'"> Remove phone number</template>
                            <template v-if="action === 'confirm'"> SMS Confirmation</template>
                        </h4>

                        <div class="form-group phone-input">
                            <label class="control-label">
                                Phone Number
                                <template v-if="action === 'confirm'">(<a href="#" @click.prevent="change">change</a>)
                                </template>
                            </label>

                            <input class="form-control" type="text" v-model="phone" id='phone' name='phone'
                                   value="{{old('phone', $editprofile[0]->phone)}}"
                                   :disabled="action === 'unbind' || (action === 'confirm' && user.phone_verified)"/>
                        </div>

                        <div v-if="action === 'confirm'" class="form-group" id="confirmation-code">
                            <template v-if="action === 'confirm'">
                                <label class="control-label col-md-4">Confirmation code</label>

                                <input class="form-control" type="text" v-model="code"/>

                            </template>
                        </div>
                        <div class="form-group">

                            <div class="col-md-6 col-md-offset-2 alert alert-info">
                                <strong>Info!</strong> Please add your phone number in this format +447654321000
                            </div>
                            <a href="#" @click.prevent="send" class="btn  text pull-right">
                                <template v-if="action === 'confirm'">OK!</template>
                                <template v-else-if="action === 'unbind'">Remove Phone Number</template>
                                <template v-else-if="action === 'new'">Send confirmation code</template>
                            </a>
                        </div>
                    </div>
                    @if($editprofile[0]->admin == 2)
                        <div class="form-group">
                            <label for="gender">Gender</label>

                            <select name="gender" class="trackprogress form-control validate[required] text-input">
                                <option value=""></option>
                                <option value="male"
								        <?php if($editprofile[0]->gender == 'male'){?> selected="selected" <?php } ?>>
                                    Male
                                </option>
                                <option value="female"
								        <?php if($editprofile[0]->gender == 'female'){?> selected="selected" <?php } ?>>
                                    Female
                                </option>
                            </select>
                        </div>
                        <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                            <label for="dob">DOB</label>
							<?php
							echo Form::input( 'date', 'dob', old( 'dob', $editprofile[0]->dob ), [
								'class'       => 'trackprogress validate[required] form-control',
								'placeholder' => 'dob'
							] );
							?>
                            @if ($errors->has('dob'))
                                <span class="help-block" style="color:red;">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                            @endif
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="photo">Avatar</label>
                        <input type="file" id="photo" name="photo"
                               class="trackprogress form-control {{empty($editprofile[0]->photo)?'validate[required]':''}}"
                               value="{{old('photo', $editprofile[0]->photo)}}">
                        @if ($errors->has('photo'))
                            <span class="help-block" style="color:red;">
                                            <strong>{{ $errors->first('photo') }}</strong>
                                        </span>
                        @endif
                    </div>
                    <input type="hidden" name="currentphoto" value="<?php echo $editprofile[0]->photo;?>">
                    <div class="buttons pull-right">
						<?php if(config( 'global.demosite' ) == "yes"){?>
                        <button type="button" class="btn btndisable">Update</button>
                        <span class="disabletxt">( <?php echo config( 'global.demotxt' );?> )</span>
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


    <!-- Modal -->
    <div class="modal fade" id="jobCompeleteAlert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" style="margin-top: 25px">
        <div class="modal-dialog" style="min-width: 800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Mark job as completed:</h4>
                </div>
                <div class="modal-body">
                    @foreach($AllExpJobs as $ExpJob)
                        <div class="single-end-job-div" style="padding: 10px;">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <h3>{{$ExpJob->title}}</h3>
                                    @php($ExpJobSch=$ExpJob->schedules->toArray())
                                    @php($jobEndDate=end($ExpJobSch))
                                    <p>
                                        <i class="fa fa-clock-o"></i>
                                        Job End Date : {{date('M d, Y',strtotime($jobEndDate['end']))}}</p>
                                </div>

                            </div>

                            <div class="row">
                                <div style="box-shadow: 0 0 1px black;border-radius: 5px;"
                                     class="col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
                                    @if(count($ExpJob->ja)==0)
                                        <p class="text-center">You haven't hired any freelancer for this job.</p>
                                    @endif
                                    <table style="width: 100%">
                                        @foreach($ExpJob->ja as $ja)
                                            <tr>
                                                <td style="padding: 10px">{{$ja->user_name}}</td>
                                                <td style="padding: 10px" class="text-right">
                                                    @if($ja->completion_status =='2')
                                                        <button class="btn btn-danger " disabled
                                                                data-ja-id="{{route('api.fill.dispute',['ja_id'=>$ja->id])}}">
                                                            Dispute fill for this freelancer
                                                        </button>
                                                    @else
                                                        <button class="btn btn-danger dispute-button"
                                                                data-job-title="{{$ExpJob->title}}"
                                                                data-job-id="{{$ExpJob->id}}"
                                                                data-ja-id="{{route('api.fill.dispute',['ja_id'=>$ja->id])}}">
                                                            Dispute
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <h3 class="text-center">
                                        <button class="btn btn-success">
                                            Mark this job as complete
                                        </button>
                                    </h3>
                                </div>
                            </div>
                            <br>
                        </div>
                        <hr style="box-shadow: 0 0 1px black;">
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function () {
            @if(count($AllExpJobs)>0)
            $('#jobCompeleteAlert').modal('show')


            $('.dispute-button').click(function () {

                var th = this;

                $.ajax({
                    url: $(this).attr('data-ja-id'),
                    method: "POST",
                    data: {job_title: $(this).attr('data-job-title'), job_id: $(this).attr('data-job-id')},
                    dataType: 'json',
                    success: function (d) {
                        console.log(d);
                        if (d == '101') {
                            $(th).prop('disabled', true);
                            $(th).html('Dispute fill for this freelancer')
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {

                    }
                });
            });


            @endif
        });
    </script>





@endsection