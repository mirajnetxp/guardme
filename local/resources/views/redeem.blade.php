@extends('layouts.dashboard-template')
  


@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="{{URL::to('account')}}">Home</a></li>
            <li><a href="{{URL::to('referral')}}">Referrals</a></li>
            <li>Redeem</li>
        </ol>                       
        <h2 class="title"> Redeem</h2>
    </div>
@endsection

@section('content')
    <div class="resume-content">
        <div class="profile-details section clearfix">
            <h2>Redeem</h2>
            <div class="form-group">
                <b>Total Earned: </b><span>{{ $user->getBalance() }}</span>
                <a href="/redeem" class="btn">Redeem</a>
            </div>
            <div class="row">
                 @foreach ($items as $item)
                    <div class="col-md-3">
                        <div class="shop-list-page">
                            <div class="shop_pic">
                                <img src="{{ URL::to('/') }}/local/images/{{ $item->image }}"
                                     class="img-responsive imgservice">
                            </div>
                            <div class="col-lg-12 shop_content">
                                <h4 class="sv_shop_style"><a
                                            href="{{ URL::to('/redeem/' . $item->id) }}">{{ $item->title }}</a>
                                </h4>
                                {{--<img src="http://guardme3.loc/local/images/nostar.png" alt="rated 0 stars" class="star_rates">--}}
                                <h3>{{ $item->price }} points</h3>

                                <div align="center"><a href="{{ URL::to('/redeem/' . $item->id) }}"
                                                       class="btn btn-success radiusoff">Redeem</a></div>
                            </div>
                        </div>
                    </div>
                @endforeach 
            </div>

           
        </div>
    </div>
@endsection
