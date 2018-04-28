<?php namespace Responsive\Http\Controllers\Partners;

use Responsive\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {

    public function __construct()
    {
        $this->middleware('partners');
    }

	public function index(){
		return view('partners.dashboard.index');
	}
}