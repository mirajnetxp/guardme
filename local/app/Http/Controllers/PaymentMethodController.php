<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Responsive\PaymentMethod;


class PaymentMethodController extends Controller {

	public function add( Request $request ) {
		$user = auth()->user();
		$ckM  = PaymentMethod::where( 'user_id', $user->id )->first();

		if ( $request->method_type == 'payple' ) {
			$this->validate( $request, [
				'payple_email' => 'required|email',
			] );

			if ( ! $ckM ) {
				$ckM = new PaymentMethod();
			}
			$ckM->user_id        = $user->id;
			$ckM->method_type    = 'payple';
			$ckM->method_details = $request->payple_email;


		} elseif ( $request->method_type == 'bank' ) {
			$this->validate( $request, [
				'bank_name' => 'required',
				'ac_name'   => 'required',
				'sort_code' => 'required',
				'ac_number' => 'required'
			] );
			if ( ! $ckM ) {
				$ckM = new PaymentMethod();
			}
			$details             = json_encode([
				"bank_name" => $request->bank_name,
				"ac_name"   => $request->ac_name,
				"sort_code" => $request->sort_code,
				"ac_number" => $request->ac_number,
			]);

			$ckM->user_id        = $user->id;
			$ckM->method_type    = 'bank';
			$ckM->method_details = $details;

		}
		$ckM->save();

		return back();
	}
}
