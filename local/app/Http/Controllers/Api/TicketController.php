<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Http\Traits\TicketTrait;
use Responsive\Http\Traits\MessageTrait;
use Responsive\Http\Repositories\TicketRepository;
use Responsive\Http\Repositories\TicketMessageRepository;
use Responsive\Http\Repositories\TicketFileMessageRepository;
use Responsive\Ticket;

class TicketController extends Controller {
	use TicketTrait, MessageTrait;

	private $ticket;

	private $ticketMessages;

	private $ticketFileMessage;

	public function __construct( TicketRepository $ticket, TicketMessageRepository $ticketMessages, TicketFileMessageRepository $ticketFileMessage ) {
		$this->ticket            = $ticket;
		$this->ticketMessages    = $ticketMessages;
		$this->ticketFileMessage = $ticketFileMessage;
	}

	public function index() {
		return response()->json( [
			'status'  => 200,
			//'test'  => 'test message',
			'tickets' => $this->getTickets(),
		] );
	}

	public function store( Request $request ) {
		// if (!$this->checkRole([config('guardme.acl.Job_Seeker'), config('guardme.acl.Employer')])) {
		//     abort(404);
		// }

		$errors = $this->ticketStore( $request );

		return response()->json( [
			'status' => $errors ? 500 : 200,
			'errors' => $errors,
		] );
	}

	public function show( $id ) {
		// if ($this->checkRole([config('guardme.acl.Job_Seeker'), config('guardme.acl.Employer')])
		//     && $this->validationShow($id)) {
		//     abort(404);
		// }

		return response()->json( [
			'status'            => 200,
			'ticket'            => $this->ticket->find( $id ),
			'ticketMessages'    => $this->ticketMessages->getByTicketId( $id ),
			'ticketFileMessage' => $this->ticketFileMessage->getByTicketId( $id ),
		] );
	}

	public function update( Request $request, $id ) {
		$errors = $this->updateTicket( $request, auth()->guard( 'api' )->user()->id );

		return response()->json( [
			'status' => $errors ? 500 : 200,
			'errors' => $errors,
		] );
	}

	public function openTickets() {
		$u_id = auth()->user()->id;
		$openTickets=Ticket::where('user_id',$u_id)
							->where('state',1)
							->get();

		return response()->json($openTickets);

	}
}
