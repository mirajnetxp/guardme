@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Verification</li>
        </ol>
        <h2 class="title"> Referrals</h2>
    </div>
@endsection


@section('content')
    <div class="resume-content">

        <div class="profile-details section clearfix">
            <h2>Referrals</h2>

            <div class="form-group">
                {{--<b>Total Earned: </b><span>{{ $user->getBalance() }}</span>--}}
				<?php use Responsive\Referral; ?>
                <b>Total Earned: </b><span>{{Referral::where( 'to', auth()->user()->id)->get()->sum( 'points' ) }}</span>
                <a href="/redeem" class="btn">Redeem</a>
            </div>
            <div class="form-group">
                <label>URL: </label>
                <input type="text" class="form-control" value="{{ URL::to('/') . '/?uid=' . $user->name }}">
            </div>
        </div>
        <div class="career-objective section">
            <div class="icons">
                <i class="fa fa-group" aria-hidden="true"></i>
            </div>
            <div class="career-info">
                <h3>Users</h3>
                <table id="datatable-responsive"
                       class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>E-mail</th>
                        <th>Earned</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php $c = 1; ?>
                    @foreach ($referrals as $referral)
                        <tr>
                            <td>{{ $c++ }}</td>
                            <td>{{$referral['email']}}</td>
                            <td>{{$referral['points']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="work-history section">
            <div class="icons">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
            </div>
            <h3>Items bought</h3>
            <table class="table">
                <tr>
                    <td>#</td>
                    <td>Item name</td>
                    <td>price</td>
                    <td>Status</td>
                </tr>
				<?php $c = 1; ?>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $c++ }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
@endsection





