<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    //
    protected $table = 'tracking';
    public $timestamps = false;

    /**
     * @param $job_id
     * @param $user_id
     * @return array|\Illuminate\Support\Collection
     */
    public function getTracingDataByJobAndUser($job_id, $user_id) {
        $tracking_data = [];
        if (!empty($user_id) && !empty($job_id)) {
            $tracking_data = Tracking::where('user_id', $user_id)->where('job_id', $job_id)->get();
        }
        return $tracking_data;
    }
}
