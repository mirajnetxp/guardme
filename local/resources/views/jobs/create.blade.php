@extends('jobs.template')

@section('bread-crumb')
    <div class="breadcrumb-section">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li><a href="{{URL::to('/')}}">Home</a></li>
            <li>Create Job</li>
        </ol><!-- breadcrumb -->                        
        <h2 class="title">Create Job</h2>
    </div>
@endsection

@section('script')
        <script>
            $(document).ready(function(){
                if(gm_nxturl != null)
                {
                    //alert('lll');
                    //console.log('ii'+nxturl);
                    window.location.href = gm_nxturl;

                }
                else{
                    steps_check();
                }
              $("#create_job_form").on("submit", function(e){
                    formErrors = new Errors();
                    e.preventDefault();
                    $.ajax({
                        url: $("#create_job_form").attr("action"),
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(data) {
                            var nextUrl = "{{ route('job.schedule', ":id") }}";
                            nextUrl = nextUrl.replace(":id", data.id);

                            
                            var step = {wstep1:'completed'};
                            sessionStorage.setItem('steps',JSON.stringify(step));
                            sessionStorage.setItem('nxturl',nextUrl);
                            sessionStorage.setItem('nxtstep','wstep2');
                            
                            
                            window.location.href = nextUrl;
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

@section('content')
 

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('shared.message')

    
     <form id="create_job_form" method="POST" action="{{ route('api.create.job') }}">

        <div class="form-group row">
            <label class="col-sm-3" for="job_title">Job Title</label>
            <div class="col-sm-9">
                <input type="text" name="title" class="form-control title" id="job_title" placeholder="Job Title">
                <span class="error-span text-danger"></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3" for="security_category">Security Category</label>
            <div class="col-sm-9">
                <select type="text" name="security_category" class="form-control" id="security_category">
                    <option value="0">Please Select Security Category</option>
                    @foreach($all_security_categories as $sec_cat)
                        <option value="{{ $sec_cat->id }}">{{ $sec_cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3" for="security_category">Industry</label>
            <div class="col-sm-9">
                <select type="text" name="business_category" class="form-control" id="business_category">
                    <option value="0">Please Select Business Category</option>
                    @foreach($all_business_categories as $bus_cat)
                        <option value="{{ $bus_cat->id }}">{{ $bus_cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
            <div class="form-group row">
                <label class="col-sm-3">Enter Postcode</label>
                <div class="col-sm-9">
                    <div id="postcode_lookup"></div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3">Address line1</label>
                <div class="col-sm-9">
                    <input id="line1" name="line1" class="form-control text-input validate[required]" type="text" placeholder="Address line1" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Address line2</label>
                <div class="col-sm-9">
                    <input id="line2" name="line2" class="form-control text-input" type="text" placeholder="Address line2" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Address line3</label>
                <div class="col-sm-9">
                    <input id="line3" name="line3" class="form-control text-input" type="text" placeholder="Address line3" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Town</label>
                <div class="col-sm-9">
                    <input id="town" name="town" class="form-control text-input validate[required]" type="text" placeholder="Town" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Country</label>
                <div class="col-sm-9">
                    <input id="country" name="country" class="form-control text-input  validate[required]" type="text" placeholder="Country" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3">Postcode</label>
                <div class="col-sm-9">
                    <input id="postcode" name="postcode" class="form-control text-input  validate[required]" type="text" placeholder="Postalcode" value="">
                    <input id="addresslat" name="addresslat" class="form-control text-input" type="hidden" value="">
                    <input id="addresslong" name="addresslong" class="form-control text-input" type="hidden" value="">
                </div>
            </div>

        
        <div class="form-group row address">

            <label class="col-sm-3" for="description">Description</label>
            <div class="col-sm-9">
                <textarea name="description" rows="10" class="form-control description" id="description" placeholder="Description"></textarea>
                <span class="error-span text-danger"></span>
            </div>
        </div>
        <div class="buttons pull-right">
            <button type="submit" class="btn btn-primary pull-right">Next</button>
        </div>
        <div class="clearfix"></div>
    </form>

@endsection
