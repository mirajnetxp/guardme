<?php
namespace Responsive;
use Illuminate\Database\Eloquent\Model;

class FreelancerSetting extends Model
{
    public $table = 'freelancer_settings';

	public function user()
	{
		return $this->hasOne(User::class,'id');
	}
}
