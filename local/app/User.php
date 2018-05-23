<?php

namespace Responsive;

use DB;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use Responsive\Exceptions\NoReferralFound;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * Class User
 * Eloquent\Model for table users
 *
 * @package Responsive
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const WORK_CATEGORY_NONE = 0;
    const WORK_CATEGORY_DOOR_SUPERVISOR = 1;
    const WORK_CATEGORY_SECURITY_GUARD = 2;
    const WORK_CATEGORY_CLOSE_PROTECTION = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gender',
        'admin', 'phone', 'photo', 'verified',
        'spent', 'added'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the verification record associated with the user.
     */
    public function verification()
    {
        return $this->hasOne(VerifyUser::class);
    }

    /**
     * Find user by verification token
     *
     * @param $token
     * @return object
     */
    public static function findByVerificationToken($token)
    {
        return static::leftJoin('verify_users', 'users.id', '=', 'verify_users.user_id')
                     ->select('users.*', 'verify_users.token as verification_token', 'verify_users.new_email')
                     ->where('verify_users.token', $token)
                     ->firstOrFail();
    }

    /**
     * Update and save the model instance with the verification token.
     * @return bool|object
     * @throws \Exception
     */
    public function generateToken()
    {
        // if current user has no email, throw an error
        if (empty($this->email)) {
            throw new \Exception("The given user instance has an empty or null email field");
        }

        // Update current verified to false first
        $this->update([
            'verified' => false
        ]);

        // delete token first if exists
        $this->verification()->delete();

        // Generate token and save it to database
        $this->verification()->create([
            'token' => $token = $this->token()
        ]);

        return $token;
    }

    /**
     * Verify the user
     *
     * @return void
     */
    public function processVerify()
    {
        // update verified status
        $this->update([
            'verified' => true
        ]);

        // verify new mail if user change their email
        $this->verifyNewEmail();

        // delete stored token
        $this->verification()->delete();
    }

    /**
     * set current user email to unverified
     *
     * @return void
     */
    public function setAsUnverified()
    {
        $this->update([
            'verified' => false
        ]);
    }

    /**
     * Handle change email
     *
     * @param $new_email
     * @return void
     * @internal param $change_email
     */
    public function changeEmail($new_email)
    {
        $this->verification()->update([
            'new_email' => $new_email
        ]);
    }

    /**
     * Update new email address in users and shop tables
     *
     * @return void
     */
    private function verifyNewEmail()
    {
        if ($new_email = $this->verification->new_email) {
            $this->update(['email' => $new_email]);

            \DB::update('update shop set seller_email="'.$new_email.'" where user_id = ?', [$this->id]);
        }
    }

    /**
     * Generate the verification token.
     *
     * @return string
     */
    private function token()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }



    /**
     * Get One Time Password (valid for 5 minutes).
     *
     * @return string
     */
    public function getOTP()
    {
        $now      = Carbon::now();
        $lifetime = 300; // 5 minutes
        $periods  = intdiv($now->timestamp - $now->copy()->startOfDay()->timestamp, $lifetime);
        $unique   = $now->toDateString() . $lifetime * $periods . $this->phone;
        $password = hash_hmac('sha512', $unique, config('app.key'));

        return substr(strtoupper($password), 0, 5);
    }

    /**
     * Get array of users that registered by your link
     *
     * @return array
     */
    public function getReferrals() {
        if ($this->id) {
            $referrals = Referral::where('to', $this->id);
            $uReferrals = [];
            foreach ($referrals->get() as $referral) {
                $uReferrals [] = User::where('id', $referral->who)->first();
            }

            return $uReferrals;
        }
        throw new NoReferralFound('No referral found');
    }

    /**
     * Get referral points balance
     *
     * @return int
     */
    public function getBalance() {
        $balance = 10 * count($this->getReferrals());
        $balance -= $this->spent;
        $balance += $this->added;

        return $balance;
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'user_id', 'id');
    }

    public function person_address()
    {
        return $this->hasOne(Address::class);
    }

     public function jobs()
    {
        return $this->hasMany(Job::class,'created_by');
    }


    public function company()
    {
        return $this->hasOne(Shop::class);
    }

    public function sec_work_category()
    {
        return $this->belongsTo(SecurityCategory::class,'work_category');
    }
    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nation_id');
    }
    
    /*
     *  find the n closest locations
     *  @param Model $query eloquent model
     *  @param float $max_distance distance in miles or km
     *  @param string $units miles or kilometers
     *  @param Array $fiels to return
     *  @return array
     */
    public static function getUsersNearByJob($latitude=0, $longitude=0, $min_distance = 60, $max_distance = 20, $units = 'kilometers')
    {        
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
                                           " * cos( radians( UD.latitude ) ) " .
                                           " * cos( radians( UD.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( UD.latitude ) ) " .
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
                                           " * cos( radians( UD.latitude ) ) " .
                                           " * cos( radians( UD.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( UD.latitude ) ) " .
                                       " ) " . 
                                   "), 2 ) " . 
                                   "AS distance
                                   ",
                                   $gr_circle_radius,               
                                   $latitude,
                                   $longitude,
                                   $latitude
                                      );
        
        $users_result = DB::table((new User)->getTable().' as U')
                     ->select('U.*', DB::raw( $distance_select ))
                     ->leftJoin((new Address)->getTable().' as UD', 'U.id', '=', 'UD.user_id')
                     ->where('U.verified', '=', 1) 
                     ->where('U.admin', '=', 2) 
                     ->whereRaw("$distance_select_sub >= $min_distance")
                     ->whereRaw("$distance_select_sub <= $max_distance")
                     ->get();
        
        $users_list = $users_result;
        return $users_list;
    }
    
    /*
     *  find the n closest locations
     *  @param Model $query eloquent model
     *  @param float $max_distance distance in miles or km
     *  @param string $units miles or kilometers
     *  @param Array $fiels to return
     *  @return array
     */
    public static function getPersonnelSearchNearBy($data_arr, $latitude=0, $longitude=0, $max_distance = 600, $units = 'kilometers', $page=1)
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
        
        // todo: filter by category
        $search_category = isset($data_arr['cat_val']) ? trim($data_arr['cat_val']) : null;
        // todo: filter by gender
        $search_gender = isset($data_arr['gender']) ? trim($data_arr['gender']) : null;
        // todo: search filter, location
        $location_search_filter = isset($data_arr['location_filter']) ? trim($data_arr['location_filter']) : null;
        // todo: filter user
        $personnel_query = isset($data_arr['sec_personnel']) ? $data_arr['sec_personnel'] : null;
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
                                           " * cos( radians( UA.latitude ) ) " .
                                           " * cos( radians( UA.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( UA.latitude ) ) " .
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
                                           " * cos( radians( UA.latitude ) ) " .
                                           " * cos( radians( UA.longitude ) - radians(%s) ) " .
                                           " + sin( radians(%s) ) * sin( radians( UA.latitude ) ) " .
                                       " ) " . 
                                   "), 2 ) " . 
                                   "AS distance
                                   ",
                                   $gr_circle_radius,               
                                   $latitude,
                                   $longitude,
                                   $latitude
                                      );
        $users_total = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'UA.citytown', DB::raw( $distance_select ))
                     ->leftJoin((new Address)->getTable().' as UA', 'U.id', '=', 'UA.user_id')
                     ->leftJoin((new SecurityCategory)->getTable().' as US', 'U.work_category', '=', 'US.id')
                     ->where('U.admin', '=', 2)
                     //->where('U.verified', '=', 1)
                     ->where(function ($query) use ($search_category) {
                        if ($search_category && $search_category != 'all') {
                            $query->where('US.name', $search_category);
                        }
                      })
                     ->where(function ($query) use($search_gender){
                        if ($search_gender && $search_gender != 'all')
                            $query = $query->where('U.gender', $search_gender);
                      }) 
                     ->where(function ($query) use($location_search_filter){
                        if ($location_search_filter) {
                            $location_search_query_array = explode(' ', trim($location_search_filter));

                            if (count($location_search_query_array)) {
                                foreach ($location_search_query_array as $search_location) {
                                    $query->where('UA.citytown', $search_location);
                                }
                            }
                        }
                      }) 
                     ->where(function ($query) use($personnel_query){
                        if ($personnel_query) {
                            $search_query_array = explode(' ', trim($personnel_query));

                            if (count($search_query_array)) {
                                foreach ($search_query_array as $search_key) {
                                    $query->where('U.name', 'LIKE', "%$search_key%")
                                          ->orWhere('U.email', 'LIKE', "%$search_key%")
                                          ->orWhere('U.firstname', 'LIKE', "%$search_key%")
                                          ->orWhere('U.lastname', 'LIKE', "%$search_key%");
                                }
                            }
                        }
                      })
                     ->whereRaw("$distance_select_sub >= $min_distance")
                     ->whereRaw("$distance_select_sub <= $max_distance")
                     ->get();
                     //->toSql();
        
        $totalusers = count($users_total);
        
        $users_result = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'UA.citytown', DB::raw( $distance_select ))
                     ->leftJoin((new Address)->getTable().' as UA', 'U.id', '=', 'UA.user_id')
                     ->leftJoin((new SecurityCategory)->getTable().' as US', 'U.work_category', '=', 'US.id')
                     ->where('U.admin', '=', 2)
                     //->where('U.verified', '=', 1)
                     ->where(function ($query) use ($search_category) {
                        if ($search_category && $search_category != 'all') {
                            $query->where('US.name', $search_category);
                        }
                      })
                     ->where(function ($query) use($search_gender){
                        if ($search_gender && $search_gender != 'all')
                            $query = $query->where('U.gender', $search_gender);
                      }) 
                     ->where(function ($query) use($location_search_filter){
                        if ($location_search_filter) {
                            $location_search_query_array = explode(' ', trim($location_search_filter));

                            if (count($location_search_query_array)) {
                                foreach ($location_search_query_array as $search_location) {
                                    $query->where('UA.citytown', $search_location);
                                }
                            }
                        }
                      }) 
                     ->where(function ($query) use($personnel_query){
                        if ($personnel_query) {
                            $search_query_array = explode(' ', trim($personnel_query));

                            if (count($search_query_array)) {
                                foreach ($search_query_array as $search_key) {
                                    $query->where('U.name', 'LIKE', "%$search_key%")
                                          ->orWhere('U.email', 'LIKE', "%$search_key%")
                                          ->orWhere('U.firstname', 'LIKE', "%$search_key%")
                                          ->orWhere('U.lastname', 'LIKE', "%$search_key%");
                                }
                            }
                        }
                      })
                     ->whereRaw("$distance_select_sub >= $min_distance")
                     ->whereRaw("$distance_select_sub <= $max_distance")
                     ->orderBy('distance', 'ASC')
                     ->take($numPerPage)
                     ->offset(($page-1) * $numPerPage)
                     ->get();
        $users_list = new Paginator($users_result, $totalusers, $numPerPage, array($page), array("path" => '/search'));
       
        return $users_list;
    }
    
    /*
     *  find the n closest locations
     *  @param Model $query eloquent model
     *  @param float $max_distance distance in miles or km
     *  @param string $units miles or kilometers
     *  @param Array $fiels to return
     *  @return array
     */
    public static function getPersonnelNearBy($data_arr, $page=1)
    {        
        $numPerPage = 10;
        
        // todo: filter by category
        $search_category = isset($data_arr['cat_val']) ? trim($data_arr['cat_val']) : null;
        // todo: filter by gender
        $search_gender = isset($data_arr['gender']) ? trim($data_arr['gender']) : null;
        // todo: search filter, location
        $location_search_filter = isset($data_arr['location_filter']) ? trim($data_arr['location_filter']) : null;
        // todo: filter user
        $personnel_query = isset($data_arr['sec_personnel']) ? $data_arr['sec_personnel'] : null;
        
        $users_total = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'UA.citytown')
                     ->leftJoin((new Address)->getTable().' as UA', 'U.id', '=', 'UA.user_id')
                     ->leftJoin((new SecurityCategory)->getTable().' as US', 'U.work_category', '=', 'US.id')
                     ->where('U.admin', '=', 2)
                     //->where('U.verified', '=', 1)
                     ->where(function ($query) use ($search_category) {
                        if ($search_category && $search_category != 'all') {
                            $query->where('US.name', $search_category);
                        }
                      })
                     ->where(function ($query) use($search_gender){
                        if ($search_gender && $search_gender != 'all')
                            $query = $query->where('U.gender', $search_gender);
                      }) 
                     ->where(function ($query) use($location_search_filter){
                        if ($location_search_filter) {
                            $location_search_query_array = explode(' ', trim($location_search_filter));

                            if (count($location_search_query_array)) {
                                foreach ($location_search_query_array as $search_location) {
                                    $query->where('UA.citytown', $search_location);
                                }
                            }
                        }
                      }) 
                     ->where(function ($query) use($personnel_query){
                        if ($personnel_query) {
                            $search_query_array = explode(' ', trim($personnel_query));

                            if (count($search_query_array)) {
                                foreach ($search_query_array as $search_key) {
                                    $query->where('U.name', 'LIKE', "%$search_key%")
                                          ->orWhere('U.email', 'LIKE', "%$search_key%")
                                          ->orWhere('U.firstname', 'LIKE', "%$search_key%")
                                          ->orWhere('U.lastname', 'LIKE', "%$search_key%");
                                }
                            }
                        }
                      })
                     ->get();
                     //->toSql();
        
        $totalusers = count($users_total);
        
        $users_result = DB::table((new User)->getTable().' as U')
                     ->select('U.*', 'UA.citytown')
                     ->leftJoin((new Address)->getTable().' as UA', 'U.id', '=', 'UA.user_id')
                     ->leftJoin((new SecurityCategory)->getTable().' as US', 'U.work_category', '=', 'US.id')
                     ->where('U.admin', '=', 2)
                     //->where('U.verified', '=', 1)
                     ->where(function ($query) use ($search_category) {
                        if ($search_category && $search_category != 'all') {
                            $query->where('US.name', $search_category);
                        }
                      })
                     ->where(function ($query) use($search_gender){
                        if ($search_gender && $search_gender != 'all')
                            $query = $query->where('U.gender', $search_gender);
                      }) 
                     ->where(function ($query) use($location_search_filter){
                        if ($location_search_filter) {
                            $location_search_query_array = explode(' ', trim($location_search_filter));

                            if (count($location_search_query_array)) {
                                foreach ($location_search_query_array as $search_location) {
                                    $query->where('UA.citytown', $search_location);
                                }
                            }
                        }
                      }) 
                     ->where(function ($query) use($personnel_query){
                        if ($personnel_query) {
                            $search_query_array = explode(' ', trim($personnel_query));

                            if (count($search_query_array)) {
                                foreach ($search_query_array as $search_key) {
                                    $query->where('U.name', 'LIKE', "%$search_key%")
                                          ->orWhere('U.email', 'LIKE', "%$search_key%")
                                          ->orWhere('U.firstname', 'LIKE', "%$search_key%")
                                          ->orWhere('U.lastname', 'LIKE', "%$search_key%");
                                }
                            }
                        }
                      })
                     ->take($numPerPage)
                     ->offset(($page-1) * $numPerPage)
                     ->get();
        $users_list = new Paginator($users_result, $totalusers, $numPerPage, array($page), array("path" => '/search'));
       
        return $users_list;
    }
    
    public static function getWorkCategories() {
        return [
            User::WORK_CATEGORY_NONE => null,
            User::WORK_CATEGORY_DOOR_SUPERVISOR => 'Door Supervisor',
            User::WORK_CATEGORY_SECURITY_GUARD => 'Securoty Guard',
            User::WORK_CATEGORY_CLOSE_PROTECTION => 'Close Protection'
        ];
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class,'applied_by');
    }
}
