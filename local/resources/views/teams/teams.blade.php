@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Jobs</li>
        </ol>
        <h2 class="title">
            My Teams</h2>
    </div>
@endsection
@section('content')

    <div class="section trending-ads latest-jobs-ads">
        <h4>My Teams</h4>

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
        <a href="{{ route('create.team') }}"><button class="btn btn-success pull-right">Create Team</button></a>
        <br>
        <br>
        <br>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Team name</th>
                <th>Team Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($teams as $i => $team)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->description }}</td>
                    <td><a href="{{ route('team.detail', ['team_id' => $team->id]) }}"><button class="btn btn-success">View Team</button></a></td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

@endsection