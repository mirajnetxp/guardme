<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;


class NotificationController extends Controller {

	public function unread() {
		$user = auth()->user();

		$count = count( $user->unreadNotifications );

		$Notifications = [];


		foreach ( $user->notifications->take( 5 ) as $Notification ) {
			$Notifications[] = $Notification->data;
		}

		return response()->json( [ "count" => $count, "notifications" => $Notifications ] );
	}

	public function message() {
		$user = auth()->user();

		$count = count( $user->unreadNotifications );

		return response()->json( [ "count" => $count ] );
	}

	public function markAsRead() {
		$user = auth()->user();

		$user->unreadNotifications->markAsRead();
		return response()->json( [ "code" => 101 ] );

	}
}
