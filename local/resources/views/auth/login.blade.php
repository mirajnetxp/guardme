@extends('layouts.app')

@section('content')

    @include('header')


    <!-- signin-page -->
    <section class="clearfix job-bg user-page">
        <div class="container">
            <div class="row text-center">
                <!-- user-login -->
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="user-account">
                        <h2>User Login</h2>

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
                        <div class="social-login">
                            <a href="/account/login/facebook"
                               style="">
                                <img width="50" src="/img/if_3_939757.png">
                            </a>

                            <a  href="/account/login/google">
                                <img width="50" src="/img/if_7_939752.png">
                            </a>
                        </div>
                        <hr>
                        <!-- form -->
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                <input id="username" type="text" class="form-control" name="username"
                                       value="{{ old('email') }}" required autofocus placeholder="Username/Email">

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


                            <div class="user-option">
                                <div class="checkbox pull-left">
                                    <label><input type="checkbox"
                                                  name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="forgot-password text-left">
                                <a  href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                            <button type="submit" class="btn">
                                Login
                            </button>
                        </form>


                    </div>
                    <a href="{{URL::route('register')}}" class="btn-primary">Create a New Account</a>
                </div><!-- user-login -->
            </div><!-- row -->
        </div><!-- container -->
    </section><!-- signin-page -->

    @include('footer')
@endsection
