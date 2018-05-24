<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * Eloquent\Model for table items
 *
 * @package Responsive
 */
class Notifications extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['notification_type' , 'notification_message' , 'user_id' , 'job_id', 'notification_by_user_id' , 'is_read'];
    protected $table = 'notifications';
    /**
     * @var bool
     */
    public $timestamps = false;
	
	
}
