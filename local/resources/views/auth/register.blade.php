@extends('layouts.app')

@section('content')

    @include('header')

    <section class="job-bg user-page">
        <div class="container">
            <div class="row text-center">
                <!-- user-login -->
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="user-account job-user-account">
                        <h2>Register</h2>

                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"
                                       required autofocus placeholder="Username">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                <input id="email" type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" required placeholder="E-Mail Address">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" class="form-control" name="password" required
                                       placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">

                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required placeholder="Confirm Password">

                            </div>


                            <div class="form-group">

                                <input id="phoneno" type="text" class="form-control" name="phoneno" required
                                       placeholder="Phone No">

                            </div>


                            <select name="gender" class="form-control" required>

                                <option value="">Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>

                            <select name="usertype" class="form-control bottom-mrgn-4" required>
                                <option value="">Are you an employer</option>
                                <option value="0">Yes</option>
                                <option value="2">No</option>
                            </select>


                            <span><i>You can open an employer account any time you want by filling out the employer form on the dashboard.</i></span>


                            <div class="checkbox">
                                <label class="pull-left"> <input type="checkbox" name="securityagree"
                                                                 class="validate[required]" value="securityagree"
                                                                 required/> I agree to the security personnel <a
                                            href='https://guarddme.com/terms-and-condition' target='_blank'>terms and
                                        conditions.</a> </label>
                            </div>

                            <div class="checkbox">
                                <label class="pull-left">
                                    <input type="checkbox" name="employeragree" class="validate[required]"
                                           value="employeragree" required/> I agree to the employer <a
                                            href='https://guarddme.com/terms-and-condition' target='_blank'>terms and
                                        conditions.</a>
                                </label>
                            </div>
                            <div class="checkbox">
                                <label class="pull-left checked">
                                    <input type="checkbox" checked="checked" name="newsletter" value="true"/>
                                    Subscribe to newsletter.

                                </label>
                            </div>
                            <div class="clearfix"></div>
                            <button type="submit" class="btn">
                                Register
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @include('footer')
@endsection
