@extends("tickets.template")

@section('content')

    @php
        $pageTitle = 'Ticket: ' . $ticket->title;
    @endphp

    @if (!session('errors') && session('type'))
        <div class="alert alert-success">
            @if (session('type') == 'state')
                Ticket was closed
            @elseif (session('type') == 'status')
                Ticket status was changed to {{ $statuses[$ticket->status] }}
            @endif
        </div>
    @endif

    @php
        $user = auth()->user();
		$userId = $user->id;
        $firstMessage = $messages->shift() ?? null;
    @endphp
    <div class="breadcrumb-section">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Home</a></li>
                <li><a href="{{ URL::to('/support/tickets') }}">Tickets</a></li>
                 <li>{{$ticket->title}}</li>
            </ol>                       
            <h2 class="title">{{$ticket->title}}</h2>
    </div>
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">
                {{ isset($categories[$ticket->category_id]) ? $categories[$ticket->category_id] : 'Undefined category' }}@if ($userId == $ticket->responsible_id) from {{ ucfirst($ticket->userCreate->name) }}@endif, <strong>{{ $firstMessage->date_time_create }}</strong>
            </div>
        </div>
        <div class="panel-body">
            {{ $firstMessage->message }}
        </div>
        <div class="panel-footer">
            <div>
                <div class="pull-right">
                    <span class="label label-{{ $statusClasses[$ticket->status] }}">{{ $statuses[$ticket->status] }}</span>
                    @if ($ticket->state == 0)
                        <span class="label label-success">{{ $stateOf[$ticket->state] }}</span>
                    @else
                        <span class="label label-warning">{{ $stateOf[$ticket->state] }}</span>
                    @endif
                </div>

                <strong>
                    Responsible user: {{ ($ticket->responsible_id) ? $ticket->userResponsible->name : 'Not assigned' }}
                </strong>
            </div>
            
            <div class="clearfix">
                @if ($firstMessage->files)
                    <strong>Attachments: </strong>
                    @foreach ($firstMessage->files as $firstMessageFile)
                        <a href="{{ asset('files/ticket_attachments/' . $firstMessageFile->name ) }}" type="application/file">{{ $firstMessageFile->name }}</a>&nbsp;&nbsp;
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="section job-ad-item">
        <a class="btn btn-secondary" href="{{ URL::to('/support/tickets') }}">&larr; Back to Tickets</a>
    </div>
    <div>
        @if ($userId == $ticket->responsible_id && $ticket->state)
            <form action="{{ route('ticket.update', $ticket->id) }}" method="POST" class="update-ticket-form">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <select name="status" class="form-control change-status-input">
                    @foreach ($statuses as $statusId => $statusValue)
                        <option @if ($statusId == $ticket->status) selected @endif value="{{ $statusId }}">{{ $statusValue }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="id" value="{{ $ticket->id }}">
                <input type="hidden" name="type" value="state_status">
                <input type="submit" name="change_status" value="Change Status" class="btn btn-primary">
                <input type="submit" name="change_state" value="Close" class="btn btn-success">
            </form>
        @endif
    </div>

    @if ($messages->count())
        <hr />
        @foreach ($messages as $message)
        <?php
            $isCurrentUserMessage = $message->user->id == $userId;
        ?>

        <div class="ticket-message panel @if (!$isCurrentUserMessage) reply @endif">
            <div class="panel-body">
                @if ($isCurrentUserMessage)
                    <div class="message-author text-success">
                        You, {{ $message->date_time_create }}
                    </div>
                @else
                    <div class="message-author">
                        {{ $message->user->name }}, {{ $message->date_time_create }}
                    </div>
                @endif
                <div class="message-content">
                    {{ $message->message }}
                </div>
                <? $files = $message->files ?>
                @if ($files->count())
                    <div class="message-files">
                        <hr />
                        <strong>Attachments: </strong>
                        @foreach ($files as $file)
                            <div>
                                <a href="{{ asset('files/ticket_attachments/' . $file->name ) }}" type="application/file">{{ $file->name }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
    @endif
    
    @if (($userId == $ticket->user_id || $userId == $ticket->responsible_id) && $ticket->state)
        <hr />
        <div class="panel">
            <div class="panel-body">
                <form id="send-message-form" action="{{ route('tickets.messages.store', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="3" placeholder="Type a message here ... "></textarea>
                    </div>
                    <div class="form-group">
                        <div class="input_files">

                        </div>
                        <span class="add_file btn btn-default">Add input file</span>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Send Message" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        #page-wrapper {
            position: relative;
        }
        .footer {
            left: 0 !important;
        }
        .change-status-input {
            max-width: 200px;
            margin-right: 10px;
            display: inline-block;
        }
        .update-ticket-form .btn {
            margin-right: 10px;
        }
        .update-ticket-form {
            margin-top: 20px;
        }
        h1 {
            padding-top: 20px !important;
        }
        .message-author {
            font-weight: 800;
        }
        .label {
            margin-left: 3px;
            margin-right: 3px;
        }
        .ticket-message.reply {
            margin-left: 40px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var countFiles = 0;
        $('.add_file').on('click', function() {
            if (countFiles < 10) {
                var html = '<div class="form-group"><input type="file" name="files['+ countFiles++ +']"/></div>';
                $('.input_files').append(html);
            }
        });
    </script>

@endpush