@extends('layouts.dashboard-template')
  

@section('bread-crumb')
    <div class="breadcrumb-section">
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li>Support</li>
        </ol>                       
        <h2 class="title">Support</h2>
    </div>
@endsection
	
@section('content')


		<div class="adpost-details post-resume">
			

			<div class="row">
				<div class="col-md-8">
					<div class="section postdetails">
						<div class="description-info">
							<h2>Support</h2>
		               <a class="btn btn-secondary" href="{{ Route('ticket.create') }}">Create ticket</a>
<br>
					<table class="ui table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Responsible</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach($tickets as $ticket)
                        	<tr>
                        		<td>
                                    <a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->title }}</a>
                                </td>
                                <td>
                                	@if ($ticket->state == 0)
                                         <span class="label label-success">{{ 'Closed' }}</span>
                                    @else
                                         <span class="label label-warning">{{ 'Opened' }}</span>
                                    @endif
                                </td>
                                <td>
                                	@if (isset($statuses[$ticket->status]))
                                        <span class="label label-{{ $statusClasses[$ticket->status] }}">{{ $statuses[$ticket->status] }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($ticket->responsible_id)
                                        {{ $ticket->userResponsible->name }}
                                    @else
                                        <span>No responsible</span>
                                    @endif
                                </td>
                        	</tr>
                        	@endforeach
                        </tbody>
                    </table>
@endsection