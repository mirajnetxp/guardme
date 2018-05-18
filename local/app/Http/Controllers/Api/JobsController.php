<?php
namespace Responsive\Http\Controllers\Api;
use Responsive\Http\Traits\JobsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Transaction;
use Responsive\User;
use Responsive\Businesscategory;
use Responsive\SecurityCategory;

class JobsController extends Controller
{
    use JobsTrait;
    
    public function create(Request $request) {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        $job = new Job();
        $postedData = $request->all();
        $job->title = !empty($postedData['title']) ? ($postedData['title']) : null;
        $job->description = !empty($postedData['description']) ? ($postedData['description']) : null;
        $job->country = !empty($postedData['country']) ? ($postedData['country']) : null;
        $job->city_town = !empty($postedData['town']) ? ($postedData['town']) : null;
        $job->address_line1 = !empty($postedData['line1']) ? ($postedData['line1']) : null;
        $job->address_line2 = !empty($postedData['line2']) ? ($postedData['line2']) : null;
        $job->address_line3 = !empty($postedData['line3']) ? ($postedData['line3']) : null;
        $job->post_code = !empty($postedData['postcode']) ? ($postedData['postcode']) : null;
        $job->latitude = !empty($postedData['addresslat']) ? ($postedData['addresslat']) : null;
        $job->longitude = !empty($postedData['addresslong']) ? ($postedData['addresslong']) : null;
        $job->business_category_id = !empty($postedData['business_category']) ? ($postedData['business_category']) : null;
        $job->security_category_id = !empty($postedData['security_category']) ? ($postedData['security_category']) : null;
        $job->created_by = !empty(auth()->user()->id) ? (auth()->user()->id) : 0;
        $isSaved = $job->save();
        if ($isSaved) {
            $return = ['message' => 'Data Saved Successfully', 'id' => $job->id];
            $status_code = 200;
        } else {
            $return = ['message' => 'Failed to save data'];
            $status_code = 500;
        }
        return response()
            ->json($return, $status_code);
    }
    public function schedule(Request $request, $id) {
        $this->validate($request, [
            'working_hours' => 'required|integer',
            'working_days' => 'required|integer',
            'pay_per_hour' => 'required|integer',
            'number_of_freelancers' => 'required|integer',
            'start_date_time' => 'required',
            'end_date_time' => 'required',
        ]);
        $posted_data = $request->all();
        $working_days = !empty($posted_data['working_days']) ? $posted_data['working_days'] : 0;
        $working_hours = !empty($posted_data['working_hours']) ? $posted_data['working_hours'] : 0;
        $pay_per_hour = !empty($posted_data['pay_per_hour']) ? $posted_data['pay_per_hour'] : 0;
        $number_of_freelancers = !empty($posted_data['number_of_freelancers']) ? $posted_data['number_of_freelancers'] : 0;
        $start_date_time = !empty($posted_data['start_date_time']) ? $posted_data['start_date_time'] : [];
        $end_date_time = !empty($posted_data['end_date_time']) ? $posted_data['end_date_time'] : [];
        $schedules = [];
        foreach($start_date_time as $k => $sch) {
            $schedule_item['start'] = date('Y-m-d h:i', strtotime($sch));
            $schedule_item['end'] = date('Y-m-d h:i', strtotime($end_date_time[$k]));
            $schedules[] = $schedule_item;
        }
        $job = Job::find($id);
        $logged_in_id = !empty(auth()->user()->id) ? (auth()->user()->id) : 0;
        $return_data = ['Not allowed to perform this action'];
        $return_status = 500;
        if (!empty($job) && !empty($job->created_by) && $job->created_by == $logged_in_id) {
            // save job schedules

            $job->schedules()->createMany($schedules);

            $job->daily_working_hours = $working_hours;
            $job->monthly_working_days = $working_days;
            $job->per_hour_rate = $pay_per_hour;
            $job->number_of_freelancers = $number_of_freelancers;
            if ($job->save()) {
                $return_data = ['message' => 'Data saved successfully'];
                $return_status = 200;
            }
        }
        return response()
            ->json($return_data, $return_status);
    }
    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function broadcast(Request $request, $id) {
        $this->validate($request, [
            'visible_to_all_security_personal' => 'required_without_all:visible_to_favourite,specific_area,specific_category_id',
            'visible_to_favourite' => 'required_without_all:visible_to_all_security_personal,specific_area,specific_category_id',
            'specific_area' => 'required_without_all:visible_to_all_security_personal,visible_to_favourite,specific_category_id',
            'specific_category_id' => 'required_without_all:visible_to_all_security_personal,visible_to_favourite,specific_area',
            'min_area' => 'required_with:specific_area',
            'max_area' => 'required_with:specific_area',
            'terms_conditions' => 'required',
        ]);
        $posted_data = $request->all();
        $visible_to_all_security_personal = !empty($posted_data['visible_to_all_security_personal']) ? $posted_data['visible_to_all_security_personal'] : 0;
        $visible_to_favourite = !empty($posted_data['visible_to_favourite']) ? $posted_data['visible_to_favourite'] : 0;
        $specific_area = !empty($posted_data['specific_area']) ? $posted_data['specific_area'] : 0;
        $min_area = !empty($posted_data['min_area']) ? $posted_data['min_area'] : 0;
        $max_area = !empty($posted_data['max_area']) ? $posted_data['max_area'] : 0;
        $specific_category_id = !empty($posted_data['specific_category_id']) ? $posted_data['specific_category_id'] : 0;
        $job = Job::find($id);
        $logged_in_id = !empty(auth()->user()->id) ? (auth()->user()->id) : 0;
        $return_data = ['Not allowed to perform this action'];
        $return_status = 500;
        if (!empty($job) && !empty($job->created_by) && $job->created_by == $logged_in_id) {
            $job->visible_to_all_security_personal = $visible_to_all_security_personal;
            $job->visible_to_favourite = $visible_to_favourite;
            $job->specific_category_id = $specific_category_id;
            if (!empty($specific_area)) {
                $job->specific_area_min = $min_area;
                $job->specific_area_max = $max_area;
            }
            if ($job->save()) {
                $return_data = ['message' => 'Data saved successfully'];
                $return_status = 200;
            }
        }
        return response()
            ->json($return_data, $return_status);
    }

    public function getJobAmount($id) {
        $jobDetails = Job::calculateJobAmount($id);
        return response()
            ->json($jobDetails);
    }
    public function activateJob($job_id) {
        $returnData = "Un-know error occured";
        $returnStatus = 500;
        $user_id = auth()->user()->id;
        if ($user_id) {
            // find job
            $job = Job::find($job_id);
            if ($job->created_by == $user_id) {
                $job_details = Job::calculateJobAmount($job_id);
                $trans = new Transaction();
                $available_balance = $trans->getWalletAvailableBalance();
                if ($job_details['grand_total'] > $available_balance) {
                    $returnStatus = 500;
                    $returnData = "Your available balance is less than the balance required for this job";
                } else {
                    // add 3 credit entries to activate job
                    $parms['job_id'] = $job_id;
                    $parms['amount'] = $job_details['basic_total'];
                    $parms['status'] = 1;
                    $trans->fundJobFee($parms);
                    // add vat fee
                    $parms['amount'] = $job_details['vat_fee'];
                    $trans->fundVatFee($parms);
                    // add admin fee
                    $parms['amount'] = $job_details['admin_fee'];
                    $trans->fundAdminFee($parms);
                    $job->status = 1;
                    $job->save();
                    $returnStatus = 200;
                    $returnData = 'Job Activated successfully';
                }
            }
        }
        return response()
            ->json($returnData, $returnStatus);
    }

    /**
     * @return mixed
     */
    public function myJobs() {
        $my_jobs = Job::getMyJobs();
        return response()
            ->json($my_jobs);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function applyJob($id, Request $request) {
        $this->validate($request, [
            'application_description' => 'required'
        ]);
        $return_status = 500;
        $return_data = ['Failed to save data'];
        $posted_data = $request->all();
        // apply checks
        $user_id = auth()->user()->id;
        $job_application = new JobApplication();
        $is_applied = $job_application->is_applied($id);
        $is_hired = $job_application->is_hired($id);
        $job = Job::find($id);

        if (!isFreelancer()) {
            $return_status = 500;
            $return_data = ['Only freelancers can apply on jobs'];
        } else if ($is_applied) {
            $return_status = 500;
            $return_data = ['You have already applied on this job'];
        } else if ($is_hired) {
            $return_status = 500;
            $return_data = ['You have already been hired on this job'];
        } else if ($job->created_by == $user_id) {
            $return_status = 500;
            $return_data = ['You can not apply on your own job'];
        } else {
            $job_application = new JobApplication();
            $job_application->application_description = $posted_data['application_description'];
            $job_application->job_id = $id;
            $job_application->applied_by = $user_id;
            $is_saved = $job_application->save();

            if ($is_saved) {
                $return_status = 200;
                $return_data = ['Application has been submitted successfully'];
            }
        }

        return response()
            ->json($return_data, $return_status);
    }

    public function markHired($application_id) {

        // check if user is authorized to mark this application as hired.
        $job_application = new JobApplication();
        $is_eligible_to_hire = $job_application->isEligibleToMarkHired($application_id);
        if ($is_eligible_to_hire) {
            $ja = JobApplication::find($application_id);
            $ja->is_hired = 1;
            if($ja->save()) {
                $return_data = ['Hired Successfully'];
                $return_status = 200;
            } else {
                $return_data = ['Un know error occureds'];
                $return_status = 500;
            }
        } else {
            $return_data = ['You are not authorized to hire on this application'];
            $return_status = 500;
        }

        return response()
            ->json($return_data, $return_status);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addMoney(Request $request) {
        $return_data = ['Unknown Error'];
        $return_status = 500;
        $posted_data = $request->all();
        $user_id = auth()->user()->id;
        $this->validate($request, [
            'paypal_id' => 'required',
            'amount' => 'required',
            'paypal_payment_status' => 'required',
            'status' => 'required',
        ]);

        $add_money_params = [
            'paypal_id' => $posted_data['paypal_id'],
            'amount' => $posted_data['amount'],
            'user_id' => $user_id,
            'paypal_payment_status' => $posted_data['paypal_payment_status'],
            'status' => $posted_data['status']
        ];
        
        /* Email to users for selected radius */
        $job_id = $posted_data['job_id'];
        $job = Job::find($job_id);
        if($job){
            if( !empty($job->latitude) && !empty($job->longitude) && !empty($job->specific_area_min) && !empty($job->specific_area_max) ){
                $latitude = $job->latitude;
                $longitude = $job->longitude;
                $specific_area_min = $job->specific_area_min;
                $specific_area_max = $job->specific_area_max;
                $usersRes = User::getUsersNearByJob($latitude, $longitude, $specific_area_min, $specific_area_max, 'kilometers');
                if( count($usersRes) > 0 ){
                    foreach($usersRes as $usersResVal){
                        $data = array('name' => $usersResVal->name, 'specific_area_min' => $specific_area_min, 'specific_area_max' => $specific_area_max);
                        // Send mail
                        $this->jobStore($data, $usersResVal->id);
                    }
                }
            }
        }

        // add money
        $walletTransaction = new Transaction();
        $re = $walletTransaction->addMoney($add_money_params);

        if ($re) {
            $return_data = ['Data Saved Successfully'];
            $return_status = 200;
        }
        return response()
            ->json($return_data, $return_status);
    }

    public function fundJobFee(Request $request) {
        $return_data = ['Unknown Error'];
        $return_status = 500;
        $posted_data = $request->all();
        $user_id = auth()->user()->id;
        $this->validate($request, [
            'paypal_id' => 'required',
            'amount' => 'required',
            'paypal_payment_status' => 'required',
            'status' => 'required',
        ]);

        $add_money_params = [
            'paypal_id' => $posted_data['paypal_id'],
            'amount' => $posted_data['amount'],
            'user_id' => $user_id,
            'paypal_payment_status' => $posted_data['paypal_payment_status'],
            'status' => $posted_data['status']
        ];

        // add money
        $walletTransaction = new Transaction();
        $re = $walletTransaction->addMoney($add_money_params);

        if ($re) {
            $return_data = ['Data Saved Successfully'];
            $return_status = 200;
        }
        return response()
            ->json($return_data, $return_status);
    }

    public function totalUserAwardedJobs()
    {
        /** @var User $user */
        $user = auth()->user();

        // todo: get jobs awarded to user
        $awarded_jobs_query = $user->applications()
            ->where('is_hired', true)
            ->whereDate('end_date_time','>=',date('Y-m-d'))
            ;

        return response()->json([
           'total_awarded_jobs' => $awarded_jobs_query->count(),
           'data' => $awarded_jobs_query->get()
        ]);
    }

    public function totalAppliedJobsForUser()
    {
        /** @var User $user */
        $user = auth()->user();

        // todo: get jobs applied by user
        $applied_jobs = $user->applications();

        return response()->json([
            'total_awarded_jobs' => $applied_jobs->count(),
            'data' => $applied_jobs->get()
        ]);
    }

    public function totalCreatedJobsForEmployer()
    {
        /** @var User $user */
        $user = auth()->user();

        // todo: get jobs applied by user
        $created_jobs = $user->jobs();

        return response()->json([
            'total_created_jobs' => $created_jobs->count(),
            'data' => $created_jobs->get()
        ]);
    }
	
	public function getPaidJobs()
    {
        $paid_jobs = Job::whereHas('transactions', function($query){
            $query->where('credit_payment_status', 'paid');
        })->get();

        return response()->json([
            'data' => $paid_jobs
        ]);
    }
	
    /**
     * @return mixed
     */
    public function myProposals() {
        $ja = new JobApplication();
        $proposals = $ja->getMyProposals();
        return response()
            ->json($proposals, 200);

    }
    
    /**
     * Job details with routing line
     * @param Request $request
     * @return mixed
     */
    public function jobDetailsLocation(Request $request) {        
        $this->validate($request, [
            'job_id' => 'required',
            'user_id' => 'required'
        ]);
        $posted_data = $request->all();

        $user_address = User::where('id', $posted_data['user_id'])->with('address')->first();
        $job_details = Job::with(['poster','poster.company','industory'])->where('id',$posted_data['job_id'])->first();
        
        return response()->json([
            'user_address' => $user_address,
            'job_details' => $job_details
        ]);
    }
    
    public function findJobs(Request $request)
    {        
        $this->validate($request, [
            'page_id' => 'required'
        ]);
        
        $joblist = [];
        $posted_data = $request->all();        
        $page_id = !empty($posted_data['page_id']) ? $posted_data['page_id'] : '';
        $user_id = !empty($posted_data['user_id']) ? $posted_data['user_id'] : '';
        $post_code = !empty($posted_data['post_code']) ? $posted_data['post_code'] : '';
        $cat_id = !empty($posted_data['cat_id']) ? $posted_data['cat_id'] : '';
        $loc_val = !empty($posted_data['loc_val']) ? $posted_data['loc_val'] : '';
        $keyword = !empty($posted_data['keyword']) ? $posted_data['keyword'] : '';
        $distance = !empty($posted_data['distance']) ? $posted_data['distance'] : '';
        
        if ( $post_code != '' || $cat_id != '' || $loc_val != '' || $keyword != '' || $distance != '' ) {
            if( $post_code != '' ){
                $post_code = trim($post_code);
                if (!empty($post_code)) {
                    $postcode_url = "https://api.getaddress.io/find/".$post_code."?api-key=ZTIFqMuvyUy017Bek8SvsA12209&sort=true";
                    $postcode_url = str_replace(' ', '%20', $postcode_url);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_URL, $postcode_url);
                    curl_setopt($ch, CURLOPT_REFERER, $postcode_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $getBas = curl_exec($ch);
                    curl_close($ch);
                    $post_code_array = json_decode($getBas, true);
                   
                    if(isset($post_code_array['Message']) || empty($post_code_array) ){
                        $return_data = ['Post code not valid!'];
                        $return_status = 403;
                        return response()
                            ->json($return_data, $return_status);
                    }
                    $latitude = $post_code_array['latitude'];
                    $longitude = $post_code_array['longitude'];
                }
                $joblist = Job::getSearchedJobNearByPostCode($posted_data, $latitude, $longitude, 20, 'kilometers', $page_id);
            } else {
                if( $user_id != '' ){
                    $userid = User::find($user_id);
                    if( $userid->admin == 2 ){
                        if($userid->person_address){
                            $userAddressObj = $userid->person_address;
                            if(!empty($userAddressObj->latitude))
                                $latitude = $userAddressObj->latitude;
                            if(!empty($userAddressObj->latitude))
                                $longitude = $userAddressObj->longitude;

                            if( $latitude > 0 && $latitude > 0 )
                                $joblist = Job::getJobNearByUser($latitude, $longitude, 20, 'kilometers', $page_id);
                            else
                                $joblist = Job::where('status','1')->paginate(10);
                        } else {
                            $joblist = Job::where('status','1')->paginate(10);
                        }                
                    } else {
                        $joblist = Job::where('status','1')->paginate(10);
                    }
                } else {
                    $joblist = Job::where('status','1')->paginate(10);
                }
            }
        } else {
            if( $user_id != '' ){
                $userid = User::find($user_id);
                if( $userid->admin == 2 ){
                    if($userid->person_address){
                        $userAddressObj = $userid->person_address;
                        if(!empty($userAddressObj->latitude))
                            $latitude = $userAddressObj->latitude;
                        if(!empty($userAddressObj->latitude))
                            $longitude = $userAddressObj->longitude;

                        if( $latitude > 0 && $latitude > 0 )
                            $joblist = Job::getJobNearByUser($latitude, $longitude, 20, 'kilometers', $page_id);
                        else
                            $joblist = Job::where('status','1')->paginate(10);
                    } else {
                        $joblist = Job::where('status','1')->paginate(10);
                    }                
                } else {
                    $joblist = Job::where('status','1')->paginate(10);
                }
            } else {
                $joblist = Job::where('status','1')->paginate(10);
            }
        }
        
        return response()->json([
            'job_list' => $joblist
        ]);
    }
    
    /**
     * @return mixed
     */
    public function getSecurityCategories() {
        $ja = new JobApplication();
        $securityCategories = SecurityCategory::all();
        return response()
            ->json($securityCategories, 200);

    }
    
    /**
     * @return mixed
     */
    public function getBusinessCategories() {
        $ja = new JobApplication();
        $businessCategories = Businesscategory::all();
        return response()
            ->json($businessCategories, 200);

    }
    
    
}