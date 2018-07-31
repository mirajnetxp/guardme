<!DOCTYPE html>
<html lang="en">
<head>


    @include('style')


</head>
<body>

<?php $url = URL::to( "/" ); ?>

<!-- fixed navigation bar -->
@include('header')

<section class="job-bg page ad-profile-page">
    <div class="container">
        <div class="breadcrumb-section">
            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li><a href="{{URL::to('personnel-search')}}">Security Personnel</a></li>
                <li>Profile</li>
            </ol><!-- breadcrumb -->
            <h2 class="title">@php $flag = false; @endphp
                @if($person->firstname!='')

                    @php  $flag = false;  @endphp

                    @foreach($person->applications as $row)
                        @if(auth()->user()->id == $row->apply_to &&  $row->is_hired == '1')
                            @php
                                $flag = true;
                                break;
                            @endphp

                        @endif
                    @endforeach

                    @if($flag)
                        {{$person->firstname.' '.$person->lastname.auth()->user()->id.' a' }}
                    @else
                        {{$person->firstname.' ********'}}
                    @endif

                @else
                    {{'********'}}
                @endif
            </h2>
        </div>
        <div class="resume-content">
            <div class="row profile section clearfix">
                <div class="col-lg-6 col-md-6 ">
                    <div class="profile-logo">
						<?php $photo_path = '/local/images/userphoto/' . $person->photo;?>
                        @if($person->photo!="")
                            <img class="img-responsive" src="<?php echo $url . $photo_path;?>" alt="Image">
                        @else
                            <img class="img-responsive" src="<?php echo $url . '/local/images/nophoto.jpg';?>"
                                 alt="Image">
                        @endif
                    </div>
                    <div class="profile-info">
                        <h1>@php $flag = false; @endphp
                            @if($person->firstname!='')

                                @php  $flag = false;  @endphp

                                @foreach($person->applications as $row)
                                    @if(auth()->user()->id == $row->apply_to &&  $row->is_hired == '1')
                                        @php
                                            $flag = true;
                                            break;
                                        @endphp

                                    @endif
                                @endforeach

                                @if($flag)
                                    {{$person->firstname.' '.$person->lastname.auth()->user()->id.' a' }}
                                @else
                                    {{$person->firstname.' ********'}}
                                @endif

                            @else
                                {{'********'}}
                            @endif
                        </h1>
                        <address>
                            <p>@if($person->person_address)
                                    City: {{$person->person_address->citytown}}
                                @endif
                                <br>
                                GPS: {{($person->freelancerSettings->gps==1)?'Active':'Inactive'}}
                                @if($person->sec_work_category)
                                    Category: {{$person->sec_work_category->name}}
                                @endif
                            </p>
                        </address>
                    </div>
                    @if(!empty($work_history['aggregate_rating']))
                        <p><span class="stars" data-rating="{{ $work_history['aggregate_rating'] }}"
                                 data-num-stars="5"></span>
                            <strong>{{  $work_history['aggregate_rating'] }}</strong></p>
                    @endif

                </div>
                <div class="col-lg-6 col-md-6">
                @if(auth()->user()->admin==0)
                    <!-- Button trigger modal -->
                        <button class="btn btn-info pull-right" data-toggle="modal" data-target="#hireMe">
                            Hire Me
                        </button>

                        <!-- Modal -->
                        <div style="margin-top: 30px" class="modal fade" id="hireMe" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span
                                                    aria-hidden="true">&times;</span><span
                                                    class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Open Jobs</h4>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="list-group">
                                            @if($openJobs)
                                                @foreach($openJobs as $openJob)
                                                    <li class="list-group-item">
                                                        <form class="hireBy">
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="freelancer_id"
                                                                   value="{{$person->id}}">
                                                            <input type="hidden" name="job_id" value="{{$openJob->id}}">
                                                            <input class="pull-right  btn-small" style="background: #4CAF50;
                                                                                                        color: #fff;
                                                                                                        border: 1px solid #4caf50;
                                                                                                        border-radius: 4px;
                                                                                                        box-shadow: 0 0 1px 0px black;
                                                                                                        text-shadow: 0 0 0px black;"
                                                                   type="button" data-toggle="modal"
                                                                   data-target="#model{{$openJob->id}}" value="Hire">
															<?php
															$data['date'] = $openJob->schedules->toArray();
															?>
                                                            @if(auth()->user()->admin==0)
                                                            <!-- Modal -->
                                                                <div style="margin-top: 20px"
                                                                     class="modal modal-ter fade"
                                                                     id="model{{$openJob->id}}"
                                                                     tabindex="-1" role="dialog"
                                                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal"><span
                                                                                            aria-hidden="true">&times;</span><span
                                                                                            class="sr-only">Close</span>
                                                                                </button>
                                                                                <h4 class="modal-title"
                                                                                    id="myModalLabel">Terms
                                                                                    and Conditions</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p class="text-justify">
                                                                                <h2>Contract Between Employer and
                                                                                    Security
                                                                                    Personnel</h2>
                                                                                <br>
                                                                                <strong>{{$data['employerCompany']}}
                                                                                    also known
                                                                                    as the "Company"</strong>
                                                                                <br>
                                                                                <br>
                                                                                <strong>{{$data['freelancerName']}} also
                                                                                    known
                                                                                    as the "Security Personnel"</strong>
                                                                                <br>
                                                                                <br>


                                                                                <h4>1. STATUS OF THIS AGREEMENT</h4>
                                                                                <p>
                                                                                    This contract governs your
                                                                                    engagement from
                                                                                    time to time by the Company as a
                                                                                    casual
                                                                                    worker. This is
                                                                                    not an employment
                                                                                    contract and does not confer any
                                                                                    employment
                                                                                    rights on you (other than those to
                                                                                    which
                                                                                    workers are
                                                                                    entitled). In
                                                                                    particular, it does not create any
                                                                                    obligation on the Company to provide
                                                                                    work to
                                                                                    you and by entering
                                                                                    into this
                                                                                    contract you confirm your
                                                                                    understanding that
                                                                                    the Company makes no promise or
                                                                                    guarantee of
                                                                                    a minimum
                                                                                    level of work to
                                                                                    you and you will work on a flexible,
                                                                                    "as
                                                                                    required" basis. It is the intention
                                                                                    of both
                                                                                    you and the
                                                                                    Company that there
                                                                                    be no mutuality of obligation
                                                                                    between the
                                                                                    parties at any time when you are not
                                                                                    performing an
                                                                                    assignment.
                                                                                </p>
                                                                                <br>


                                                                                <h4>2. COMPANY'S DISCRETION AS TO WORK
                                                                                    OFFERED</h4>
                                                                                <p>
                                                                                    It is entirely at the Company's
                                                                                    discretion
                                                                                    whether to offer you work and it is
                                                                                    under no
                                                                                    obligation
                                                                                    to provide work
                                                                                    to you at any time. The Company
                                                                                    reserves the
                                                                                    right to give or not give work to
                                                                                    any person
                                                                                    at any
                                                                                    time and is under
                                                                                    no obligation to give any reasons
                                                                                    for such
                                                                                    decisions.
                                                                                </p>
                                                                                <br>

                                                                                <h4>3. NO PRESUMPTION OF CONTINUITY</h4>
                                                                                <p>
                                                                                    Each offer of work by the Company
                                                                                    which you
                                                                                    accept shall be treated as an
                                                                                    entirely
                                                                                    separate and
                                                                                    severable engagement
                                                                                    (an assignment). The terms of this
                                                                                    contract
                                                                                    shall apply to each assignment but
                                                                                    there
                                                                                    shall be no
                                                                                    relationship
                                                                                    between the parties after the end of
                                                                                    one
                                                                                    assignment and before the start of
                                                                                    any
                                                                                    subsequent
                                                                                    assignment. The fact that
                                                                                    the Company has offered you work, or
                                                                                    offers
                                                                                    you work more than once, shall not
                                                                                    confer
                                                                                    any legal
                                                                                    rights on you and,
                                                                                    in particular, should not be
                                                                                    regarded as
                                                                                    establishing an entitlement to
                                                                                    regular work
                                                                                    or conferring
                                                                                    continuity of
                                                                                    employment.
                                                                                </p>
                                                                                <br>

                                                                                <h4>4. ARRANGEMENTS FOR WORK</h4>
                                                                                <p>
                                                                                    If the Company wants to offer you
                                                                                    any work
                                                                                    it will contact you by telephone
                                                                                    and/or
                                                                                    text. You must
                                                                                    provide accurate
                                                                                    contact details to the Company when
                                                                                    requested. You are under no
                                                                                    obligation to
                                                                                    accept any work
                                                                                    offered by the Company
                                                                                    at any time. If you accept an
                                                                                    assignment,
                                                                                    you must inform the Company
                                                                                    immediately if
                                                                                    you will be
                                                                                    unable to complete
                                                                                    it for any reason. The Company
                                                                                    reserves the
                                                                                    right to terminate an assignment at
                                                                                    any time
                                                                                    for
                                                                                    operational reasons.
                                                                                    You will be paid for all work done
                                                                                    during
                                                                                    the assignment up to the time it is
                                                                                    terminated.
                                                                                </p>
                                                                                <br>

                                                                                <h4>5. WORK</h4>
                                                                                <p>
                                                                                    The Company may offer you work from
                                                                                    time to
                                                                                    time as [Details of anticipated
                                                                                    position(s)]. The
                                                                                    precise description
                                                                                    and nature of your work may be
                                                                                    varied with
                                                                                    each assignment and you may be
                                                                                    required to
                                                                                    carry out
                                                                                    other duties as
                                                                                    necessary to meet business needs.
                                                                                    You will
                                                                                    be informed of the requirements at
                                                                                    the start
                                                                                    of each
                                                                                    assignment. Before
                                                                                    offering you an assignment the
                                                                                    Company will
                                                                                    require certain documents from you
                                                                                    in order
                                                                                    to satisfy
                                                                                    itself that you
                                                                                    are legally entitled to work in the
                                                                                    UK. You
                                                                                    confirm that you are legally
                                                                                    entitled to
                                                                                    work in the UK
                                                                                    without any
                                                                                    additional immigration approvals and
                                                                                    agree
                                                                                    to notify the Company immediately if
                                                                                    you
                                                                                    cease to be so
                                                                                    entitled at any
                                                                                    time.
                                                                                </p>
                                                                                <br>

                                                                                <h4>6. PLACE OF WORK</h4>
                                                                                <p>
                                                                                    The Company may offer you work at
                                                                                    various
                                                                                    locations. You will be informed of
                                                                                    the
                                                                                    relevant place of
                                                                                    work for each
                                                                                    assignment.
                                                                                </p>
                                                                                <br>

                                                                                <h4>7. HOURS OF WORK</h4>
                                                                                <p>
                                                                                    Your hours of work will vary
                                                                                    depending on
                                                                                    the operational requirements of the
                                                                                    Company.
                                                                                    You will be
                                                                                    informed of the
                                                                                    required hours for each assignment.
                                                                                    You will
                                                                                    be entitled to an unpaid lunch break
                                                                                    of one
                                                                                    hour where
                                                                                    your assignment
                                                                                    requires you to work more than six
                                                                                    hours in
                                                                                    any one day.
                                                                                </p>
                                                                                <br>

                                                                                <h4>8. PAY</h4>
                                                                                <p>
                                                                                    You will only be paid for the hours
                                                                                    that you
                                                                                    work. The Company's current rate of
                                                                                    pay for
                                                                                    casual
                                                                                    workers is £[Amount]
                                                                                    an hour (gross). You will be paid
                                                                                    monthly in
                                                                                    arrears at the end of each month
                                                                                    directly
                                                                                    into your
                                                                                    bank account for
                                                                                    the hours worked in the previous
                                                                                    month. The
                                                                                    Company will make all necessary
                                                                                    deductions
                                                                                    from your
                                                                                    salary as required
                                                                                    by law and shall be entitled to
                                                                                    deduct from
                                                                                    your pay or other payments due to
                                                                                    you any
                                                                                    money which
                                                                                    you may owe to the
                                                                                    Company at any time.
                                                                                </p>
                                                                                <br>

                                                                                <h4>9. HOLIDAYS</h4>
                                                                                <p>
                                                                                    Your holiday entitlement will depend
                                                                                    on the
                                                                                    number of hours that you actually
                                                                                    work and
                                                                                    be pro-rated
                                                                                    on the basis of
                                                                                    a full-time entitlement of [28]
                                                                                    days'
                                                                                    holiday during each full holiday
                                                                                    year
                                                                                    (including public
                                                                                    holidays in England
                                                                                    and Wales). The Company's holiday
                                                                                    year runs
                                                                                    between [Date] and [Date]. At the
                                                                                    end of
                                                                                    each assignment
                                                                                    the Company
                                                                                    will pay you in lieu of any accrued
                                                                                    but
                                                                                    untaken holiday for the holiday year
                                                                                    in
                                                                                    which the assignment
                                                                                    ends. If you
                                                                                    have taken more holiday than your
                                                                                    accrued
                                                                                    entitlement at the date that your
                                                                                    assignment
                                                                                    ends, the
                                                                                    Company shall be
                                                                                    entitled to make deduction from any
                                                                                    payment
                                                                                    due to you in respect of such
                                                                                    entitlement.
                                                                                </p>
                                                                                <br>


                                                                                <h4>10. SICKNESS</h4>
                                                                                <p>
                                                                                    If you have accepted an offer of
                                                                                    work but
                                                                                    are subsequently unable to work the
                                                                                    hours
                                                                                    agreed, you must
                                                                                    notify the HR
                                                                                    department of the reason for your
                                                                                    absence as
                                                                                    soon as possible but no later than
                                                                                    9am on
                                                                                    the first day
                                                                                    of absence. If
                                                                                    you satisfy the qualifying
                                                                                    conditions laid
                                                                                    down by law, you will be entitled to
                                                                                    receive
                                                                                    statutory
                                                                                    sick pay (SSP) at
                                                                                    the prevailing rate in respect of
                                                                                    any period
                                                                                    of sickness or injury during an
                                                                                    assignment,
                                                                                    but you
                                                                                    will not be
                                                                                    entitled to any other payments from
                                                                                    the
                                                                                    Company during such period.
                                                                                </p>
                                                                                <br>

                                                                                <h4>11. COMPANY RULES AND
                                                                                    PROCEDURES</h4>
                                                                                <p>
                                                                                    During each assignment you are
                                                                                    required at
                                                                                    all times to comply with the
                                                                                    relevant
                                                                                    Company rules,
                                                                                    policies and
                                                                                    procedures in force from time to
                                                                                    time [and
                                                                                    which are available on our intranet.
                                                                                </p>
                                                                                <br>

                                                                                <h4>12. CONFIDENTIAL INFORMATION</h4>
                                                                                <p>
                                                                                    You shall not use or disclose to any
                                                                                    person,
                                                                                    either during or at any time after
                                                                                    your
                                                                                    engagement by
                                                                                    the Company, any
                                                                                    confidential information about the
                                                                                    business
                                                                                    or affairs of the Company, or about
                                                                                    any
                                                                                    other matters
                                                                                    which may come to
                                                                                    your knowledge as a result of
                                                                                    carrying out
                                                                                    assignments. For the purposes of
                                                                                    this
                                                                                    clause,
                                                                                    confidential information
                                                                                    means any information or matter
                                                                                    which is not
                                                                                    in the public domain and which
                                                                                    relates to
                                                                                    the affairs
                                                                                    of the company.
                                                                                    The restriction in this clause does
                                                                                    not
                                                                                    apply to:
                                                                                    • prevent you from making a
                                                                                    protected
                                                                                    disclosure within the meaning of
                                                                                    section 43A
                                                                                    of the Employment
                                                                                    Rights Act
                                                                                    1996; or
                                                                                    • use or disclosure that has been
                                                                                    authorised
                                                                                    by the Company or is required by law
                                                                                    or in
                                                                                    the course
                                                                                    of your duties.

                                                                                </p>
                                                                                <br>

                                                                                <h4>13. COMPANY PROPERTY</h4>
                                                                                <p>
                                                                                    All documents, manuals, hardware and
                                                                                    software provided for your use by
                                                                                    the
                                                                                    Company, and any data or
                                                                                    documents
                                                                                    (including copies) produced,
                                                                                    maintained or
                                                                                    stored on the Company's computer
                                                                                    systems or
                                                                                    other
                                                                                    electronic equipment
                                                                                    (including mobile phones), remain
                                                                                    the
                                                                                    property of the Company. Any Company
                                                                                    property in your
                                                                                    possession and any
                                                                                    original or copy documents obtained
                                                                                    by you
                                                                                    in the course of your work for the
                                                                                    Company
                                                                                    shall be
                                                                                    returned to the HR
                                                                                    department at any time on request
                                                                                    and in any
                                                                                    event at the end of each assignment.
                                                                                </p>
                                                                                <br>

                                                                                <h4>14. TERMINATION</h4>
                                                                                <p>
                                                                                    If you wish your name to be removed
                                                                                    from the
                                                                                    Company's staff bank of zero hours
                                                                                    workers
                                                                                    you should
                                                                                    inform the HR
                                                                                    department as soon as possible. The
                                                                                    Company
                                                                                    may remove your name from its staff
                                                                                    bank of
                                                                                    zero hours
                                                                                    workers if you
                                                                                    are unable to accept an assignment
                                                                                    on two
                                                                                    consecutive occasions. The Company
                                                                                    may
                                                                                    terminate this
                                                                                    contract immediately
                                                                                    by giving notice in writing to you
                                                                                    if it
                                                                                    reasonably considers that you have
                                                                                    committed
                                                                                    any serious
                                                                                    breach of its
                                                                                    terms or committed any act of gross
                                                                                    misconduct. Non-exhaustive examples
                                                                                    of gross
                                                                                    misconduct include
                                                                                    dishonesty,
                                                                                    theft, fighting, misuse of drugs or
                                                                                    alcohol
                                                                                    or any other acts or omissions which
                                                                                    might
                                                                                    bring the
                                                                                    Company into
                                                                                    disrepute.
                                                                                </p>
                                                                                <br>

                                                                                <h4>15. GOVERNING LAW</h4>
                                                                                <p>
                                                                                    This contract will be governed by
                                                                                    the law of
                                                                                    England and Wales
                                                                                </p>
                                                                                <br>

                                                                                <P>
                                                                                    Signed by Company
                                                                                    representative {{$data['employerName']}}
                                                                                    <br>
                                                                                    @if(count($data['date'])>1)
                                                                                        Job Date
                                                                                        - {{date('d M, Y',strtotime($data['date'][0]['start']))}}
                                                                                        to {{date('d M, Y',strtotime(end($data['date'])['start'])
                                                                                    )}}
                                                                                    @else
                                                                                        Job Date
                                                                                        - {{date('d M, Y',strtotime($data['date'][0]['start']))}}
                                                                                    @endif
                                                                                </P>


                                                                                <P>
                                                                                    Signed by Security
                                                                                    Personnel {{$data['freelancerName']}}
                                                                                    <br>
                                                                                    @if(count($data['date'])>1)
                                                                                        Job Date
                                                                                        - {{date('d M, Y',strtotime($data['date'][0]['start']))}}
                                                                                        to {{date('d M, Y',strtotime(end($data['date'])['start']))}}
                                                                                    @else
                                                                                        Job Date
                                                                                        - {{date('d M, Y',strtotime($data['date'][0]['start']))}}
                                                                                    @endif

                                                                                </P>
                                                                                </p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button style="background-color: #f44336" type="button"
                                                                                        class="btn btn-danger"
                                                                                        data-dismiss="modal">Decline
                                                                                </button>
                                                                                <button type="submit"
                                                                                        class="btn btn-success">Accept
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </form>
                                                        {{$openJob->title}}
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="career-objective section">
                <div class="icons">
                    <i class="fa fa-drivers-license-o" aria-hidden="true"></i>
                </div>
                <div class="career-info profile-info">
                    <h3>Security Licence</h3>

                    <address>
                        <p>Licence Type: SIA <br>
                            @if($person->sia_licence !='')Valid:
                            <i class="fa fa-check-circle-o ico-30 green"></i>
                            @endif
                            <br>
                            Expiry Date:@if($person->sia_expirydate !='')
                                {{$person->sia_expirydate}}
                            @else
                                {{'NA'}}
                            @endif
                        </p>
                    </address>
                </div>
            </div>
            <div class="work-history section">
                <div class="icons">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                </div>
                <div class="work-info">
                    <h3>Feedback</h3>
                    <ul>
                        @if(!empty($work_history['project_ratings']))
                            @foreach($work_history['project_ratings'] as $item)
                                <li>
                                    <h4>{{ $item['job_title'] }} <span>{{ $item['date_range'] }}</span></h4>
                                    <p><span class="stars" data-rating="{{ $item['star_rating'] }}"
                                             data-num-stars="5"></span> <strong>{{ $item['star_rating'] }}</strong></p>
                                    <p>{{ $item['feedback_message'] }}</p>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div><!-- career-objective -->
        </div>
    </div>

</section>

@include('footer')
<script>
    /*read only star rating to display only*/

    $(document).ready(function () {
        $('.hireBy').submit(function () {
            event.preventDefault();
            var form_data = $(this).serialize();
            $.ajax({
                url: "{{route('job.hair.by')}}",
                method: "POST",
                data: form_data,
                dataType: 'json',
                success: function (d) {
                    $('.modal-ter').modal('hide')
                    alert(d)
                },
                error: function (xhr, textStatus, errorThrown) {
                    $('.modal-ter').modal('hide')
                    console.log(xhr.responseText);
                    alert(xhr.responseText)
                }
            });
        })


    });

    $.fn.stars = function () {
        return $(this).each(function () {

            var rating = $(this).data("rating");
            if (rating == '5.00') {
                rating = 5;
            }
            var numStars = $(this).data("numStars");

            var fullStar = new Array(Math.floor(rating + 1)).join('<i class="fa fa-star"></i>');

            var halfStar = ((rating % 1) !== 0) ? '<i class="fa fa-star-half-empty"></i>' : '';

            var noStar = new Array(Math.floor(numStars + 1 - rating)).join('<i class="fa fa-star-o"></i>');

            $(this).html(fullStar + halfStar + noStar);

        });
    };
    $('.stars').stars();
</script>
</body>
</html>
