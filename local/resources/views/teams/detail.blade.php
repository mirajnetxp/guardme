@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Jobs</li>
        </ol>
        <h2 class="title">
            Team Details</h2>
    </div>
@endsection
@section('content')

    <div class="section trending-ads latest-jobs-ads">
        <h4>Team Details</h4>

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
        <div class="row">
            <div class="col-md-4">
                <strong>Team Name:</strong>
                {{ $team->name }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <strong>Team Description:</strong>
                {{ $team->description }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <strong>Team Members:</strong>


                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Freelancer name</th>
                                <th>Freelancer email</th>
                            </tr>
                            </thead>
                            @foreach($team->freelancers as $k => $freelancer)
                                <tbody>
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $freelancer->name }}</td>
                                        <td>{{ $freelancer->email }}</td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>



            </div>
        </div>
    </div>

@endsection