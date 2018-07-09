@extends('layouts.app')

@section('content')

    @include('header')

    <section class="job-bg user-page">
        <div class="container">
            <div class="row text-center">
                <!-- user-login -->
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="user-account job-user-account">
                        <h2>
                            Welcome to GuardMe <br>
                            <small>Hello, {{$new_user->name}}</small>
                        </h2>

                        <form method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="uid" value="{{$new_user->id}}">
                            @if(!$new_user->gender)
                                <select name="gender" class="form-control" required>
                                    <option value="">Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            @endif
                                <select name="usertype" class="form-control bottom-mrgn-4" required>
                                    <option value="">Are you an employer</option>
                                    <option value="0">Yes</option>
                                    <option value="2">No</option>
                                </select>


                                <span><i>You can open an employer account any time you want by filling out the employer form on the dashboard.</i></span>



                                <div class="checkbox">
                                    <label class="pull-left">
                                        <input type="checkbox" name="securityagree" class="validate[required]"
                                               value="securityagree" required/> I agree to the security personnel <a
                                                href='https://guarddme.com/terms-and-condition' target='_blank'>terms
                                            and conditions.</a> </label>
                                </div>

                                <div class="checkbox">
                                    <label class="pull-left">
                                        <input type="checkbox" name="employeragree" class="validate[required]"
                                               value="employeragree" required/> I agree to the employer <a
                                                href='https://guarddme.com/terms-and-condition' target='_blank'>terms
                                            and conditions.</a>
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
