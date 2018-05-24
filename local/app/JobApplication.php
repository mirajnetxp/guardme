<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobApplication extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_applications';
	
	public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }

    /**
     * @param $job_id
     * @return mixed
     */
    public function is_applied($job_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table)
            ->where('applied_by', $user_id)
            ->where('job_id', $job_id)->get();
        return count($res);
    }

    /**
     * @param $job_id
     * @return mixed
     */
    public function is_hired($job_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table)
            ->where('applied_by', $user_id)
            ->where('is_hired', 1)
            ->where('job_id', $job_id)->get();
        return count($res);
    }

    public function getJobApplications($id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table. ' as ja')
            ->select('u.name as user_name',
                'ja.application_description as description',
                'ja.id',
                'ja.is_hired',
                'u.photo as photo',
                'u.id as u_id',
                'ja.created_at as applied_date'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('users as u', 'u.id', '=', 'ja.applied_by')
            ->where('ja.job_id', $id)
            ->where('sj.created_by', $user_id)->get();
        return $res;
    }

    public function getApplicationDetails($application_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table. ' as ja')
            ->select('u.name as user_name',
                'ja.application_description as description',
                'ja.id',
                'sj.title as job_title',
                'sj.description as job_description',
                'ja.is_hired',
                'ja.created_at as applied_date'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('users as u', 'u.id', '=', 'ja.applied_by')
            ->where('ja.id', $application_id)
            ->where('sj.created_by', $user_id)->get()->first();
        return $res;
    }

    /**
     * @param $application_id
     * @return array
     */
    public function getApplicantWorkHistory($application_id) {
        $work_history = [];
        $job_application = JobApplication::find($application_id);
        $res = DB::table($this->table. ' as ja')
            ->select(
                'sj.title',
                'sj.id as job_id',
                'fb.message',
                'fb.appearance',
                'fb.punctuality',
                'fb.customer_focused',
                'fb.security_conscious'
            )
            ->join('users as u', 'u.id', '=','ja.applied_by')
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('feedback as fb', 'fb.application_id', '=', 'ja.id')
            ->where('ja.applied_by', $job_application->applied_by)->get();
        $sec_res = DB::table('security_jobs_schedule as sjs')
            ->select('start', 'end', 'sjs.job_id')
            ->join($this->table . ' as ja', 'ja.job_id', '=', 'sjs.job_id')
            ->join('security_jobs as sj', 'sj.id', '=', 'sjs.job_id')
            ->where('ja.applied_by', $job_application->applied_by)
            ->where('ja.is_hired', 1)->get();
        $job_schedule = [];
        if (!empty($sec_res)) {
            foreach($sec_res as $key => $sec_item)  {
                $job_schedule[$sec_item->job_id][] = ['start' => $sec_item->start, 'end' => $sec_item->end];
            }
        }
        foreach($res as $k => $item) {
            $job_date_range = '';
            $appearance = $item->appearance;
            $punctuality = $item->punctuality;
            $customer_focused = $item->customer_focused;
            $security_conscious = $item->security_conscious;
            $rating_aggregate = ($appearance + $punctuality + $customer_focused + $security_conscious)/4;
            if (!empty($job_schedule[$item->job_id])) {
                $current_job_schedule_array = $job_schedule[$item->job_id];
                $current_job_schedule_start_date = $current_job_schedule_array[0]['start'];
                $current_job_schedule_end_date = $current_job_schedule_array[count($current_job_schedule_array) - 1]['end'];
                $job_date_range = date('d, M Y', strtotime($current_job_schedule_start_date)) . ' to ' . date('d, M Y', strtotime($current_job_schedule_end_date));
            }
            $work_history[] = [
                'job_id' => $item->job_id,
                'star_rating' => $rating_aggregate,
                'feedback_message' => $item->message,
                'job_title' => $item->title,
                'date_range' => $job_date_range
            ];
        }
        return $work_history;
    }
    public function getMyApplicationDetails($application_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table. ' as ja')
            ->select('u.name as user_name',
                'ja.application_description as description',
                'ja.id',
                'sj.title as job_title',
                'sj.description as job_description',
                'ja.is_hired',
                'ja.created_at as applied_date'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('users as u', 'u.id', '=', 'ja.applied_by')
            ->where('ja.id', $application_id)
            ->where('ja.applied_by', $user_id)->get()->first();
        return $res;
    }

    public function isEligibleToMarkHired($application_id) {
        $user_id = auth()->user()->id;
        $results = DB::table($this->table . ' as ja')
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->where('sj.created_by', $user_id)
            ->where('ja.id', $application_id)
            ->where('sj.status', 1)->get();
        return count($results);
    }

    public function isHired($application_id) {
        $results = DB::table($this->table)
            ->where('id', $application_id)
            ->where('is_hired', 1)->get();
        return count($results);
    }
    public function getMyProposals() {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table .' as ja')
            ->select(
                'sj.title',
                'sj.created_by',
                'ja.application_description as description',
                'ja.is_hired',
                'ja.id',
                'u.photo as photo',
                'sj.title as job_title',
                'sj.description as job_description',
                'sj.id as job_id',
                'ja.created_at as applied_date',
                'u.id as u_id',
                'u.name as user_name',
                'shp.shop_name',
                'shp.profile_photo'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
             ->join('users as u', 'u.id', '=', 'ja.applied_by')
             ->join('shop as shp', 'sj.created_by', '=', 'shp.user_id')
            ->where('ja.applied_by', $user_id)
        ->get();
        return $res;
    }
}
