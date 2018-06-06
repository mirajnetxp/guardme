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
        <div class="alert alert-danger error-message hide" role="alert">

        </div>
        <div class="alert alert-success success-message hide" role="alert">

        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <form id="create_team_form" method="POST" action="{{ route('api.team.create') }}">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" name="name" class="form-control name" id="team_name" placeholder="Team name">
                                <span class="error-span text-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <textarea rows="7" name="description" class="form-control description" placeholder="Team description"></textarea>
                                <span class="error-span text-danger"></span>
                            </div>
                        </div>
                        <div class="buttons">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <div class="clearfix"></div>

                    </form>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $("#create_team_form").on("submit", function(e){
                formErrors = new Errors();
                e.preventDefault();
                $.ajax({
                    url: $("#create_team_form").attr("action"),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        $(".success-message").text(data[0]);
                         $(".success-message").removeClass("hide");
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        formErrors.record(errors);
                        formErrors.load();
                    }
                });
            });
        });
    </script>
@endsection