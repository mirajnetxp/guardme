<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Newsletter;

class NewsletterController extends Controller {
	public function index() {

		$allNewsletter = Newsletter::where( 'status', 1 )->get();
		return view( 'admin.newsletter', compact( 'allNewsletter' ) );
	}
}
