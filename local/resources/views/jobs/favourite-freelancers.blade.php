@extends('layouts.dashboard-template')



@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li>Jobs</li>
        </ol>
        <h2 class="title">
            My Favourite Freelancers</h2>
    </div>
@endsection
@section('content')

    <div class="section trending-ads latest-jobs-ads">
        <h4>My Favourite Freelancers</h4>

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
        @foreach($freelancers as $freelancer)
            <div class="job-ad-item">
                <div class="item-info">
                    <div class="item-image-box">
                        <div class="item-image">
                            <a href="{{ route('person-profile', $freelancer->id) }}"><img align="center" class="img-responsive" src="{{ URL::to("/")}}/local/images/userphoto/{{ $freelancer->photo }}" alt="{{$freelancer->name}}"/></a>
                        </div><!-- item-image -->
                    </div>

                    <div class="ad-info">
                        <span><a href="{{ route('person-profile', $freelancer->id) }}" class="title">{{ $freelancer->name }}</a></span>
                    </div><!-- ad-info -->
                    <button class="btn btn-info pull-right add-to-team-modal-button" data-freelancer_id = "{{ $freelancer->id }}" data-freelancer_name="{{ $freelancer->name }}">Add to team</button>
                </div><!-- item-info -->
            </div>
        @endforeach
    </div>
    <!-- Modal -->
    <div class="modal fade" id="add_to_team_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add freelancer to team</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="alert alert-danger error-message hide" role="alert">

                            </div>
                            <div class="alert alert-success success-message hide" role="alert">

                            </div>
                            <form id="add_member_to_team" method="POST" action="{{ route('api.add.member.to.team') }}">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <input type="text" name="freelancer_name" readonly class="form-control" placeholder="Freelancer Name">
                                        <input type="hidden" name="freelancer_id" readonly class="form-control freelancer_id">
                                        <span class="error-span text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <select type="text" name="team_id" class="form-control team_id">
                                            <option value="0">Please select team</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error-span text-danger"></span>
                                    </div>
                                </div>
                                <div class="buttons">
                                    <button type="submit" class="btn btn-success btn-small">Submit</button>
                                </div>
                                <div class="clearfix"></div>

                            </form>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $(".add-to-team-modal-button").on("click", function () {
                var freelancer_id = $(this).attr('data-freelancer_id');
                var freelancer_name = $(this).attr('data-freelancer_name');
                $("input[name='freelancer_name']").val(freelancer_name);
                $("input[name='freelancer_id']").val(freelancer_id);
                $("#add_to_team_modal").modal('show');
            });
            $("form#add_member_to_team").on("submit", function(e){
                formErrors = new Errors();
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        $(".success-message").text(data[0]);
                        $(".success-message").removeClass("hide");
                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status == 422) {
                            var errors = data.responseJSON;
                            formErrors.record(errors);
                            formErrors.load();
                        } else {
                            $(".error-message").text(data.responseJSON[0]);
                            $(".error-message").removeClass("hide");
                        }


                    }
                })
            });

        });
    </script>
@endsection