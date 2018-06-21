<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserItem
 * Eloquent\Model of table user_items
 *
 * @package Responsive
 */
class UserItem extends Model
{
    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'item_id'
    ];

}
