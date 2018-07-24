<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Newsletter;

class NewsletterController extends Controller
{
	public function index(  ) {

		$allNewsletter=Newsletter::all();

		return view('admin.newsletter',compact('allNewsletter'));
    }
}
