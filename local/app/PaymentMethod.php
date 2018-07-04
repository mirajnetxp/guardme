<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model {

	public function user() {
		return $this->belongsTo( 'App\User' );
	}
}
