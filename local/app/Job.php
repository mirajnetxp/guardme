<?php

namespace Responsive;

use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Responsive\SecurityJobsSchedule;

class Job extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'security_jobs';
    public function schedules() {
        return $this->hasMany(SecurityJobsSchedule::class);
    }
    public static function calculateJobAmount($id) {
        $job = Job::find($id);
        $number_of_freelancers = $job->number_of_freelancers;
        $working_hours = ($job->daily_working_hours) * $number_of_freelancers;
        $working_days = $job->monthly_working_days;
        $pay_per_hour = $job->per_hour_rate;
        $total_working_hours = $working_hours * $working_days;
        $basic_total = $total_working_hours * $pay_per_hour;
        $vat_fee = number_format(($basic_total * 20) / 100, 2, '.', '');
        $admin_fee = number_format(($basic_total * 14.99) / 100, 2, '.', '');
        $grand_total = $basic_total + $vat_fee + $admin_fee;
        $grand_total = number_format($grand_total, 2, '.', '');
        $return_data = [
            'daily_working_hours' => $working_hours,
            'monthly_working_days' => $working_days,
            'per_hour_rate' => $pay_per_hour,
            'total_working_hours_per_month' => $total_working_hours,
            'basic_total' => floatval($basic_total),
            'vat_fee' => floatval($vat_fee),
            'admin_fee' => floatval($admin_fee),
            'grand_total' => floatval($grand_total)
        ];
        return $return_data;
    }

    /**
     * @return \Illuminate\Support\Collection
     * 
     */
    public static function getMyJobs() {
        $user_id = auth()->user()->id;
        $data = Job::with(['poster','poster.company','industory'])->where('created_by', $user_id)->get();
        return $data;
    }

    public static function findJobs() {
        $jobs = Job::where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();
        return $jobs;
    }
    
    /*
     *  find the n closest locations
     *  @param Model $query eloquent model
     *  @param float $max_distance distance in miles or km
     *  @param string $units miles or kilometers
     *  @param Array $fiels to return
     *  @return array
     */
    public static function getSearchedJobNearByPostCode($data_arr, $latitude=0, $longitude=0, $max_distance = 600, $units = 'kilometers', $page=1)
    {        
        $numPerPage = 10;
        
        /*
        *  Allow for changing of units of measurement
        */
        switch ( $units ) {
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 5;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
            break;
        }  
        
        //
        $cat_id = trim($data_arr['cat_id']);
        // todo: filter location
        $loc_val = trim($data_arr['loc_val']);
        // todo: filter keyword
        $keyword = $data_arr['keyword']; 
        // todo: filter distance
        $distance = $data_arr['distance'];
        if( $distance == 1 ){
            $min_distance = 0;
            $max_distance = 10;
        } else if( $distance == 2 ){
            $min_distance = 10;
            $max_distance = 20;
        } else if( $distance == 3 ){
            $min_distance = 20;
            $max_distance = 50;
        } else if( $distance == 4 ){
            $min_distance = 50;
            $max_distance = 600;
        } else {
            $min_distance = 0;
            $max_distance = 600;
        }
        
        /*
        *  Generate the select field for disctance
        */
        $distance_select_sub = sprintf(
                                   "           
                                   ROUND(( %d * acos( cos( radians(%s) ) " .
                                           " * cos( radians( J.latitude ) ) " .
                                           " * cos( radians( J.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( J.latitude ) ) " .
                                       " ) " . 
                                   "), 2 ) " . 
                                   "",
                                   $gr_circle_radius,               
                                   $latitude,
                                   $longitude,
                                   $latitude
                                      );
        
        $distance_select = sprintf(
                                   "           
                                   ROUND(( %d * acos( cos( radians(%s) ) " .
                                           " * cos( radians( J.latitude ) ) " .
                                           " * cos( radians( J.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( J.latitude ) ) " .
                                       " ) " . 
                                   "), 2 ) " . 
                                   "AS distance
                                   ",
                                   $gr_circle_radius,               
                                   $latitude,
                                   $longitude,
                                   $latitude
                                      );
        $jobs_total = DB::table((new Job)->getTable().' as J')
                     ->select('J.*', DB::raw( $distance_select ))
                     ->where('J.status', '=', 1)
                     ->where(function ($query) use($cat_id){
                         if(!empty($cat_id))
                            $query->where('J.business_category_id', $cat_id);
                      }) 
                     ->where(function ($query) use($loc_val){
                         if(!empty($loc_val))
                            $query->where('J.city_town', $loc_val);
                      }) 
                     ->where(function ($query) use($keyword){
                         if(!empty($keyword))
                            $query->where('J.title', 'like', "$keyword%");
                      })
                     ->whereRaw("$distance_select_sub >= $min_distance")
                     ->whereRaw("$distance_select_sub <= $max_distance")
                     ->get();
        
        $totaljobs = count($jobs_total);
        
        $jobs_result = DB::table((new Job)->getTable().' as J')
                     ->select('J.*', DB::raw( $distance_select ))
                     ->where('J.status', '=', 1)
                     ->where(function ($query) use($cat_id){
                         if(!empty($cat_id))
                            $query->where('J.business_category_id', $cat_id);
                      }) 
                     ->where(function ($query) use($loc_val){
                         if(!empty($loc_val))
                            $query->where('J.city_town', $loc_val);
                      }) 
                     ->where(function ($query) use($keyword){
                         if(!empty($keyword))
                            $query->where('J.title', 'like', "$keyword%");
                      })
                     ->whereRaw("$distance_select_sub >= $min_distance")
                     ->whereRaw("$distance_select_sub <= $max_distance")
                     ->orderBy('distance', 'ASC')
                     ->take($numPerPage)
                     ->offset(($page-1) * $numPerPage)
                     ->get();
        $jobs_list = new Paginator($jobs_result, $totaljobs, $numPerPage, array($page), array("path" => '/jobs/find'));
       
        return $jobs_list;
    }
    
    
    /*
     *  find the n closest locations
     *  @param Model $query eloquent model
     *  @param float $max_distance distance in miles or km
     *  @param string $units miles or kilometers
     *  @param Array $fiels to return
     *  @return array
     */
    public static function getJobNearByUser($latitude=0, $longitude=0, $max_distance = 600, $units = 'kilometers', $page=1)
    {        
        $numPerPage = 10;
        
        /*
        *  Allow for changing of units of measurement
        */
        switch ( $units ) {
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 5;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
            break;
        }  
        
        /*
        *  Generate the select field for disctance
        */
        $distance_select_sub = sprintf(
                                   "           
                                   ROUND(( %d * acos( cos( radians(%s) ) " .
                                           " * cos( radians( J.latitude ) ) " .
                                           " * cos( radians( J.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( J.latitude ) ) " .
                                       " ) " . 
                                   "), 2 ) " . 
                                   "",
                                   $gr_circle_radius,               
                                   $latitude,
                                   $longitude,
                                   $latitude
                                      );
        
        $distance_select = sprintf(
                                   "           
                                   ROUND(( %d * acos( cos( radians(%s) ) " .
                                           " * cos( radians( J.latitude ) ) " .
                                           " * cos( radians( J.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( J.latitude ) ) " .
                                       " ) " . 
                                   "), 2 ) " . 
                                   "AS distance
                                   ",
                                   $gr_circle_radius,               
                                   $latitude,
                                   $longitude,
                                   $latitude
                                      );
        $jobs_total = DB::table((new Job)->getTable().' as J')
                     ->select('J.*', DB::raw( $distance_select ))
                     ->where('J.status', '=', 1)                     
                     ->whereRaw("( $distance_select_sub <= J.specific_area_max OR J.specific_area_max IS NULL )")
                     ->get();
                     //->toSql();
        $totaljobs = count($jobs_total);
        
        $jobs_result = DB::table((new Job)->getTable().' as J')
                     ->select('J.*', DB::raw( $distance_select ))
                     ->where('J.status', '=', 1)
                     ->whereRaw("( $distance_select_sub <= J.specific_area_max OR J.specific_area_max IS NULL )")
                     ->orderBy('distance', 'ASC')
                     ->take($numPerPage)
                     ->offset(($page-1) * $numPerPage)
                     ->get();
        
        $jobs_list = new Paginator($jobs_result, $totaljobs, $numPerPage, array($page), array("path" => '/jobs/find'));
        return $jobs_list;
    }    

    function industory(){
        return $this->belongsTo(Businesscategory::class,'business_category_id');
    }

    function poster(){
        return $this->belongsTo(User::class,'created_by' ,'id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class,'job_id');
    }

    public function getJobTransactions(){
        return $this->hasMany(Transaction::class) ;
    }
}
