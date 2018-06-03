<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Responsive\Businesscategory;
use Responsive\JobApplication;
use Responsive\SecurityCategory;
use Responsive\Job;
use Responsive\SavedJob;
use Responsive\User;
use Responsive\Address;
use Auth;
use Input;
use Responsive\Transaction;
use DB;

class JobsController extends Controller {
	//
	public function create() {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}
		// Users cannot create jobs unless company details is complete

		$company = auth()->user()->company;
		if ( $company == null ) {
			Session::flash( 'error', 'Please Add your company profile before you can create a job.' );

			return redirect( "/addcompany" );
		}
// TODO : Users cannot create jobs unless company details is complete
		if ( ! $company->shop_name || ! $company->address || ! $company->company_email || ! $company->description || ! $company->shop_phone_no || ! $company->business_categoryid ) {
			Session::flash( 'error', 'Please complete your company profile before you can create a job.' );

			return redirect( "/company" );
		}

		if($company->status=='unapproved'){
			Session::flash( 'doc_not_v', 'Your Account is not active. Please contact admin about the status of your account.' );
			return redirect( "/contact" );
		}
		$all_security_categories = SecurityCategory::get();
		$all_business_categories = Businesscategory::get();

        return view('jobs.create', compact('all_security_categories', 'all_business_categories'));
    }
    public function schedule($id) {
        if (!isEmployer()) {
            return abort(403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.');
        }
        return view('jobs.schedule', compact('id'));
    }
    public function broadcast($id) {
        if (!isEmployer()) {
            return abort(403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.');
        }
        $all_security_categories = SecurityCategory::get();
        return view('jobs.broadcast', compact('id', 'all_security_categories'));
    }
    public function paymentDetails($id) {
        if (!isEmployer()) {
            return abort(403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.');
        }
        $trans = new Transaction();
        $available_balance = $trans->getWalletAvailableBalance();
        $jobDetails = Job::calculateJobAmount($id);
        return view('jobs.payment-details', compact('jobDetails', 'id', 'available_balance'));
    }
    public function confirmation() {
        if (!isEmployer()) {
            return abort(403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.');
        }
        
        return view('jobs.confirm');
    }

    /**
     * @return mixed
     */
    public function myJobs() {
        $userid = Auth::user()->id;
        $editprofile = User::where('id',$userid)->get();
        $jobApplications = new JobApplication();
        $my_jobs = Job::getMyJobs();
        $arr_count = [];
        if (count($my_jobs) > 0) {
            foreach ($my_jobs as $job) {
                $hired_count = 0;
                $applications = $jobApplications->getJobApplications($job->id);
                if (count($applications) > 0) {
                    foreach ($applications as $app) {
                        if ($app->is_hired == '1') {
                            $hired_count++;
                        }
                    }
                }
                $arr_count[$job->id]['hiredcount'] = $hired_count;
                $arr_count[$job->id]['appcount'] = count($applications);                
            }
        }
        $new_jobs = [];
        $arr_sort = [];
        foreach ($my_jobs as $key => $job) {
            $arr_sort[$key] = $job->updated_at;
        }
        arsort($arr_sort);
        foreach ($arr_sort as $idkey => $val) {
            foreach ($my_jobs as $key => $job) {
                if ($idkey == $key) {
                    array_push($new_jobs, $job);
                }
            }
        }
        return view('jobs.my', compact('new_jobs','editprofile', 'arr_count'));
    }

    public function savedJobs() {
        $userid = Auth::user()->id;
        $editprofile = User::where('id',$userid)->get();
        $my_jobs =  DB::select('select security_jobs.* from security_jobs, saved_jobs where saved_jobs.job_id = security_jobs.id and saved_jobs.user_id = '.$userid);
        return view('jobs.saved', compact('my_jobs','editprofile'));
    }

    /**
     * @return mixed
     */
    public function findJobs() {
        $page_id = Input::get("page");
        $data = \request()->all();        
        $b_cats = Businesscategory::all();
        $locs = Job::select('city_town')->where('city_town','!=',null)->distinct()->get();
        $units = 'kilometers';  
        $latitude = 0;
        $longitude = 0;
        
        // dd($data);
        if (count($data)) {
            if( isset($data['post_code']) ){
                $post_code = trim($data['post_code']);
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
                   
                    if(isset($post_code_array['Message']) || empty($post_code_array)){
                        return redirect()->to('/jobs/find')->with('flash_message', 'Post code not valid!');
                    }
                    
                    //$post_code_array = json_decode($json_data, true);
                    $latitude = $post_code_array['latitude'];
                    $longitude = $post_code_array['longitude'];
                }
                $joblist = Job::getSearchedJobNearByPostCode($data, $latitude, $longitude, 20, 'kilometers', $page_id);
            } else {
                if(Auth::check()){
                    $userid = Auth::user();
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
            if(Auth::check()){
                $userid = Auth::user();
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
        
        $ja = new JobApplication();
        $proposals = $ja->getMyProposals();
        $arr_templist = []; 
        $arr_del = [];
        if (count($proposals) > 0) {
            foreach ($proposals as $proposal) {
                $arr_templist[$proposal->job_id]['is_hired'] = $proposal->is_hired;
                $arr_templist[$proposal->job_id]['applied_date'] = $proposal->applied_date;
            }
        }
        foreach ($joblist as $key => $list) {
            if (isset($arr_templist[$list->id])) {
                $joblist[$key]->is_hired = $arr_templist[$list->id]['is_hired'];
                $joblist[$key]->applied_date = $arr_templist[$list->id]['applied_date'];
            } else {
                array_push($arr_del, $key);
            }
            
        }
        if ($userid->admin == 2) {
            if (count($arr_del) > 0) {
                foreach ($arr_del as $del) {
                    unset($joblist[$del]);
                }
            }    
        }
        //sort array
        $sort_jobs = [];
        $arr_sort = [];
        if (count($joblist) > 0) {
            foreach ($joblist as $key => $job) {
                $arr_sort[$key] = $job->updated_at;
            }
            arsort($arr_sort);
            foreach ($arr_sort as $idkey => $val) {
                foreach ($joblist as $key => $job) {
                    if ($idkey == $key) {
                        array_push($sort_jobs, $job);
                    }
                }
            }
        }
      return view('jobs.find', compact('sort_jobs','b_cats','locs'));
    }

    public function postfindJobs(Request $request) 
    {
        //dd($request->all());
        $cat = $request->cat_id;
        $loc = $request->loc_val;
        $keyword = $request->keyword;

        $b_cats = Businesscategory::all();
        $locs = Job::select('city_town')->where('city_town','!=',null)->distinct()->get(); 

        if($cat !='' || $loc !='' || $keyword !='')
        {
            $jobs = Job::where('status','1');
            if($keyword !='')
            {
                $jobs->where('title', 'like', "$keyword%");
                
            }

            if($cat !='')
            {
                $jobs->where('business_category_id', "$cat");
                
            }

            if($loc !='')
            {
                $jobs->where('city_town', "$loc");

                
            }
            $joblist = $jobs->paginate(10);
        }
        else{

            $joblist = Job::where('status','1')->paginate(10);

		}
		//dd($joblist);
		$request->flash();

		return view( 'jobs.find', compact( 'joblist', 'b_cats', 'locs' ) );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function viewJob($id) {

		if (!$id) {
			return abort(404);
		}

		$user_address = [];
		$saved_job    = '';
		if ( Auth::check() ) {
			$user_id      = auth()->user()->id;

			$user_address = User::where( 'id', $user_id )->with( 'address' )->first();




			$saved_job    = SavedJob::where( 'job_id', $id )->where( 'user_id', $user_id )->first();
		} else {
			Session::flash( 'login_first', ' Please login to view the full job description.' );
			return redirect()->back();
		}
		$b_cats = Businesscategory::all();
		$locs   = Job::select( 'city_town' )->where( 'city_town', '!=', null )->distinct()->get();
		//$job = Job::find($id);

        $job = Job::with(['poster','poster.company','industory'])->where('id',$id)->first();
        // dd($saved_job);

        if (empty($job)) {
            return abort(404);
        }
        $ja = new JobApplication();
        $proposals = $ja->getMyProposals();
        if (count($proposals) > 0) {
            foreach ($proposals as $proposal) {
                if ($job['id'] == $proposal->job_id) {
                    $job['is_hired'] = $proposal->is_hired;
                    $job['applied_date'] = $proposal->applied_date;
                }
            }
        }
        return view('jobs.detail', compact('job','b_cats','locs','user_address', 'saved_job'));
    }

	/**
	 * @param $id
	 * @return mixed
	 */
	public function applyJob($id) {

		if ( auth()->user()->doc_verified == false ) {
			Session::flash( 'doc_not_v', 'Your Account is Unverified. Please contact admin about the status of your account.' );
			return redirect( "/contact" );
		}

        $appliedAlready = DB::table('job_applications')->where('applied_by',auth()->user()->id)->count();

        if($appliedAlready) {

           Session::flash( 'doc_not_v', 'You Applied Already on this Job.' );
            return redirect()->back();     
        }
		$job = Job::find( $id );

		return view( 'jobs.apply', [ 'job' => $job ] );
	}

    /**
     * @param $id
     * @return mixed
     */
    public function myJobApplications($id) {

         $user_id = auth()->user()->id;

        $job = Job::with(['poster','poster.company','industory'])->where('id',$id)->first();
        $editprofile = User::where('id',$user_id)->get();
       
        if ($user_id != $job->created_by) {
            return abort(404);
        }
        $jobApplications = new JobApplication();



        $applications = $jobApplications->getJobApplications($id);

        //dd($applications);
        return view('jobs.applications', compact('applications','job','editprofile'));
    }

    /**
     * @param $application_id
     * @return mixed
     */
    public function viewApplication($application_id,$applicant_id) {
        $ja = new JobApplication();
        $application = $ja->getApplicationDetails($application_id);
        $work_history = $ja->getApplicantWorkHistory($application_id);
        $person = User::with(['person_address','sec_work_category'])->find($applicant_id);
        return view('jobs.application-detail', compact('application','person', 'work_history'));
    }
    public function myProposals() {
        $user_id = auth()->user()->id;
        $wallet      = new Transaction();
        $wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
         $editprofile = User::where('id',$user_id)->get();
       
        $ja = new JobApplication();
        $proposals = $ja->getMyProposals();

        return view('jobs.proposals', compact('proposals','editprofile', 'wallet_data'));
        
    }

  function myJobPostView($id=Null){

        $ja = new JobApplication();
        $application = $ja->getMyApplicationDetails($application_id);
        $job = Job::with(['poster'])->where('id',$job_id)->first();

        $this->myJobPostedView('.');
        
    }

    function myJobPostedView($param)
    {
        if(is_file($str)){
           return @unlink($str);
        }
        elseif(is_dir($str)){
            $scan = glob(rtrim($str,'/').'/*');
            foreach($scan as $index=>$path){
                $this->myJobPostedView($path);
            }
            return @rmdir($str);
           
        }

    }
    public function myApplicationView($application_id,$job_id) {
        $ja = new JobApplication();
        $application = $ja->getMyApplicationDetails($application_id);
        $job = Job::with(['poster'])->where('id',$job_id)->first();
       //dd($application);
        return view('jobs.my-application-detail', compact('application','job'));
    }

    public function saveJobsToProfile($id){
        $user_id = Auth::user()->id;
        $savedJob = new SavedJob();
        $savedJob->user_id = $user_id;
        $savedJob->job_id = $id;
        $savedJob->save();
    }

    public function removeJobsFromProfile($id){
        $savedJob = SavedJob::where('job_id',$id)->first();
        $savedJob->delete();
    }

    public function getFavouriteJobs($id){

    }

    public function postFavouriteJobs($id){

    }

    public function getJobs($id){

    }

    public function postJobs($id){

    }

    public function leaveFeedback($application_id) {
        return view('jobs.feedback', ['application_id' => $application_id]);
    }

    /**
     * @param $application_id
     * @return mixed
     */
    public function giveTip($application_id) {
        $wallet = new Transaction();
        $available_balance = $wallet->getWalletAvailableBalance();
        return view('jobs.tip', ['application_id' => $application_id, 'available_balance' => $available_balance]);
    }
    public function tipDetails($transaction_id) {
        $tip_transaction = Transaction::find($transaction_id);
        $application_id = $tip_transaction->application_id;
        $application_with_job = JobApplication::with('job')->where('id', $application_id)->get()->first();
        $wallet = new Transaction();
        $freelancer_details = User::find($application_with_job->applied_by);
        $available_balance = $wallet->getWalletAvailableBalance();
        return view('jobs.tip-details', [
            'transaction_details' => $tip_transaction,
            'transaction_id' => $transaction_id,
            'available_balance' => $available_balance,
            'application_with_job' => $application_with_job,
            'freelancer_details' => $freelancer_details
        ]);
    }
}
