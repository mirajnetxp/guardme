@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Jobs</li>
        </ol>
        <h2 class="title">
            Payment Requests</h2>
    </div>
@endsection
@section('content')

    <div class="section trending-ads latest-jobs-ads">
        <h4>Payment Requests</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        @include('shared.message')
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Job Title</th>
                <th>Freelancer Name</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($payment_requests as $k => $payment_request)
                    <tr>
                        <td>{{ $k + 1 }}</td>
                        <td>{{ $payment_request->title }}</td>
                        <td>{{ $payment_request->freelancer_name }}</td>
                        <td>{{ snakeToString($payment_request->type) }}</td>
                        <td><a href="{{ route('payment.request.details', ['id' => $payment_request->id]) }}"><button class="btn btn-success">View Details</button></a></td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

@endsection