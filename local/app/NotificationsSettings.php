<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * Eloquent\Model for table items
 *
 * @package Responsive
 */
class NotificationsSettings extends Model
{
    /**
     * @var array
    */
	 
    protected $fillable = ['user_id', 'job_created', 'job_awarded'];
	protected $table = 'notifications_settings';

    /**
     * @var bool
     */
    public $timestamps = false;
	
	
}
