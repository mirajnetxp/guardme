<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Responsive\Item;
use Responsive\Url;
use Responsive\User;
use Responsive\UserItem;
use Responsive\Http\Controllers\Controller;

/**
 * Class ReferralController
 * Backend controller
 *
 * @package Responsive\Http\Controllers\Admin
 */
class ReferralController extends Controller
{
    /**
     * Items management page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.referral', [
            'items' => Item::all()
        ]);
    }

    /**
     * Create item action
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        Item::create([
            'title' => $request->get('title'),
            'price' => $request->get('price'),
            'image' => $request->get('image')
        ]);

        return redirect('/admin/referral/items');
    }

    /**
     * Change user points balance action
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function balance(Request $request)
    {
        $user = User::where('id', $request->get('user'))->first();

        if ($user && $user->id) {
            //Increate current added value with request one
            $user->added = $user->added + $request->get('balance');
            $user->save();
            return redirect('/admin/edituser/' . $user->id);
        }
        return redirect('/admin/users');
    }

}
