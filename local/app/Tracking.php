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
     * @param null $user_id
     * @return array|\Illuminate\Support\Collection
     */
    public function getTracingDataByJobAndUser($job_id, $user_id = null) {
        $tracking_data = [];
        if (!empty($user_id) && !empty($job_id)) {
            $query = Tracking::where('user_id', $user_id);
            if (!empty($user_id)) {
                $query->where('job_id', $job_id);
            }
            $tracking_data = $query->get();

        }
        return $tracking_data;
    }
}
