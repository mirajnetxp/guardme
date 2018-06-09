<?php

namespace Responsive\Http\Controllers\Api;

use Responsive\FavouriteFreelancer;
use Responsive\Feedback;
use Responsive\Http\Traits\JobsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\PaymentRequest;
use Responsive\SecurityJobsSchedule;
use Responsive\Transaction;
use Responsive\User;
use Responsive\Businesscategory;
use Responsive\SecurityCategory;
use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Responsive\Events\AwardJob;

class JobsController extends Controller {
	use JobsTrait;

	public function create( Request $request ) {
		$this->validate( $request, [
			'title'       => 'required|max:255',
			'description' => 'required',
			'security_category' => 'required',
			'business_category' => 'required',
			'town' => 'required',
			'country' => 'required',
			'postcode' => 'required',
		] );
		$job                       = new Job();
		$postedData                = $request->all();
		$job->title                = ! empty( $postedData['title'] ) ? ( $postedData['title'] ) : null;
		$job->description          = ! empty( $postedData['description'] ) ? ( $postedData['description'] ) : null;
		$job->country              = ! empty( $postedData['country'] ) ? ( $postedData['country'] ) : null;
		$job->city_town            = ! empty( $postedData['town'] ) ? ( $postedData['town'] ) : null;
		$job->address_line1        = ! empty( $postedData['line1'] ) ? ( $postedData['line1'] ) : null;
		$job->address_line2        = ! empty( $postedData['line2'] ) ? ( $postedData['line2'] ) : null;
		$job->address_line3        = ! empty( $postedData['line3'] ) ? ( $postedData['line3'] ) : null;
		$job->post_code            = ! empty( $postedData['postcode'] ) ? ( $postedData['postcode'] ) : null;
		$job->latitude             = ! empty( $postedData['addresslat'] ) ? ( $postedData['addresslat'] ) : null;
		$job->longitude            = ! empty( $postedData['addresslong'] ) ? ( $postedData['addresslong'] ) : null;
		$job->business_category_id = ! empty( $postedData['business_category'] ) ? ( $postedData['business_category'] ) : null;
		$job->security_category_id = ! empty( $postedData['security_category'] ) ? ( $postedData['security_category'] ) : null;
		$job->created_by           = ! empty( auth()->user()->id ) ? ( auth()->user()->id ) : 0;
		$isSaved                   = $job->save();
		if ( $isSaved ) {
			$return      = [ 'message' => 'Data Saved Successfully', 'id' => $job->id ];
			$status_code = 200;
		} else {
			$return      = [ 'message' => 'Failed to save data' ];
			$status_code = 500;
		}

		return response()
			->json( $return, $status_code );
	}

	public function schedule( Request $request, $id ) {
		$this->validate( $request, [
			'working_hours'         => 'required|integer',
			'working_days'          => 'required|integer',
			'pay_per_hour'          => 'required|integer',
			'number_of_freelancers' => 'required|integer',
			'start_date_time.*'     => 'required',
			'end_date_time.*'       => 'required',
		],
			[
				'end_date_time.*.required'   => 'Start date/time field is required',
				'start_date_time.*.required' => 'End date/time field is required',
			] );
		$posted_data           = $request->all();
		$working_days          = ! empty( $posted_data['working_days'] ) ? $posted_data['working_days'] : 0;
		$working_hours         = ! empty( $posted_data['working_hours'] ) ? $posted_data['working_hours'] : 0;
		$pay_per_hour          = ! empty( $posted_data['pay_per_hour'] ) ? $posted_data['pay_per_hour'] : 0;
		$number_of_freelancers = ! empty( $posted_data['number_of_freelancers'] ) ? $posted_data['number_of_freelancers'] : 0;
		$start_date_time       = ! empty( $posted_data['start_date_time'] ) ? $posted_data['start_date_time'] : [];
		$end_date_time         = ! empty( $posted_data['end_date_time'] ) ? $posted_data['end_date_time'] : [];
		$schedules             = [];
		foreach ( $start_date_time as $k => $sch ) {
			$schedule_item['start'] = $sch;
			// becase date and time format from pick is Y-m-d h:i:s therfore no need of conversion
			$schedule_item['end']   = $end_date_time[ $k ];
			$schedules[]            = $schedule_item;
		}
		$job           = Job::find( $id );
		$logged_in_id  = ! empty( auth()->user()->id ) ? ( auth()->user()->id ) : 0;
		$return_data   = [ 'Not allowed to perform this action' ];
		$return_status = 500;
		if ( ! empty( $job ) && ! empty( $job->created_by ) && $job->created_by == $logged_in_id ) {
			// save job schedules

			$job->schedules()->createMany( $schedules );

			$job->daily_working_hours   = $working_hours;
			$job->monthly_working_days  = $working_days;
			$job->per_hour_rate         = $pay_per_hour;
			$job->number_of_freelancers = $number_of_freelancers;
			if ( $job->save() ) {
				$return_data   = [ 'message' => 'Data saved successfully' ];
				$return_status = 200;
			}
		}

		return response()
			->json( $return_data, $return_status );
	}

	/**
	 * @param Request $request
	 * @param $id
	 *
	 * @return mixed
	 */
	public function broadcast( Request $request, $id ) {
		$this->validate( $request, [
			'visible_to_all_security_personal' => 'required_without_all:visible_to_favourite,specific_area,specific_category_id',
			'visible_to_favourite'             => 'required_without_all:visible_to_all_security_personal,specific_area,specific_category_id',
			'specific_area'                    => 'required_without_all:visible_to_all_security_personal,visible_to_favourite,specific_category_id',
			'specific_category_id'             => 'required_without_all:visible_to_all_security_personal,visible_to_favourite,specific_area',
			'min_area'                         => 'required_with:specific_area',
			'max_area'                         => 'required_with:specific_area',
			'terms_conditions'                 => 'required',
		] );
		$posted_data                      = $request->all();
		$visible_to_all_security_personal = ! empty( $posted_data['visible_to_all_security_personal'] ) ? $posted_data['visible_to_all_security_personal'] : 0;
		$visible_to_favourite             = ! empty( $posted_data['visible_to_favourite'] ) ? $posted_data['visible_to_favourite'] : 0;
		$specific_area                    = ! empty( $posted_data['specific_area'] ) ? $posted_data['specific_area'] : 0;
		$min_area                         = ! empty( $posted_data['min_area'] ) ? $posted_data['min_area'] : 0;
		$max_area                         = ! empty( $posted_data['max_area'] ) ? $posted_data['max_area'] : 0;
		$specific_category_id             = ! empty( $posted_data['specific_category_id'] ) ? $posted_data['specific_category_id'] : 0;
		$job                              = Job::find( $id );
		$logged_in_id                     = ! empty( auth()->user()->id ) ? ( auth()->user()->id ) : 0;
		$return_data                      = [ 'Not allowed to perform this action' ];
		$return_status                    = 500;
		if ( ! empty( $job ) && ! empty( $job->created_by ) && $job->created_by == $logged_in_id ) {
			$job->visible_to_all_security_personal = $visible_to_all_security_personal;
			$job->visible_to_favourite             = $visible_to_favourite;
			$job->specific_category_id             = $specific_category_id;
			if ( ! empty( $specific_area ) ) {
				$job->specific_area_min = $min_area;
				$job->specific_area_max = $max_area;
			}
			if ( $job->save() ) {
				$return_data   = [ 'message' => 'Data saved successfully' ];
				$return_status = 200;
			}
		}

		return response()
			->json( $return_data, $return_status );
	}

	public function getJobAmount( $id ) {
		$jobDetails = Job::calculateJobAmount( $id );

		return response()
			->json( $jobDetails );
	}

	public function activateJob( $job_id ) {
		$returnData   = "Un-know error occured";
		$returnStatus = 500;
		$user_id      = auth()->user()->id;
		if ( $user_id ) {
			// find job
			$job = Job::find( $job_id );
			if ( $job->created_by == $user_id ) {
				$job_details       = Job::calculateJobAmount( $job_id );
				$trans             = new Transaction();
				$available_balance = $trans->getWalletAvailableBalance();
				if ( $job_details['grand_total'] > $available_balance ) {
					$returnStatus = 500;
					$returnData   = "Your available balance is less than the balance required for this job";
				} else {
					// add 3 credit entries to activate job
					$parms['job_id'] = $job_id;
					$parms['amount'] = $job_details['basic_total'];
					$parms['status'] = 1;
					$trans->fundJobFee( $parms );
					// add vat fee
					$parms['amount'] = $job_details['vat_fee'];
					$trans->fundVatFee( $parms );
					// add admin fee
					$parms['amount'] = $job_details['admin_fee'];
					$trans->fundAdminFee( $parms );
					$job->status = 1;
					$job->save();
					$returnStatus = 200;
					$returnData   = 'Job Activated successfully';
				}
			}
		}

		return response()
			->json( $returnData, $returnStatus );
	}

	/**
	 * @return mixed
	 */
	public function myJobs( Request $request ) {

		$user_Id = auth()->user()->id;

		$this->validate( $request, [
			'page_id' => 'required'
		] );

		$joblist     = [];
		$posted_data = $request->all();
		$page_id     = ! empty( $posted_data['page_id'] ) ? $posted_data['page_id'] : '';
		$user_id     = ! empty( $posted_data['user_id'] ) ? $posted_data['user_id'] : '';
		$post_code   = ! empty( $posted_data['post_code'] ) ? $posted_data['post_code'] : '';
		$cat_id      = ! empty( $posted_data['cat_id'] ) ? $posted_data['cat_id'] : '';
		$loc_val     = ! empty( $posted_data['loc_val'] ) ? $posted_data['loc_val'] : '';
		$keyword     = ! empty( $posted_data['keyword'] ) ? $posted_data['keyword'] : '';
		$distance    = ! empty( $posted_data['distance'] ) ? $posted_data['distance'] : '';

		if ( $post_code != '' || $cat_id != '' || $loc_val != '' || $keyword != '' || $distance != '' ) {
			if ( $post_code != '' ) {
				$post_code = trim( $post_code );
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
						$return_data   = [ 'Post code not valid!' ];
						$return_status = 403;

						return response()
							->json( $return_data, $return_status );
					}
					$latitude  = $post_code_array['latitude'];
					$longitude = $post_code_array['longitude'];
				}
				$joblist = Job::getSearchedJobNearByPostCode( $posted_data, $latitude, $longitude, 20, 'kilometers', $page_id );
			} else {

				if ( auth()->user()->person_address ) {
					$userAddressObj = auth()->user()->person_address;
					if ( ! empty( $userAddressObj->latitude ) ) {
						$latitude = $userAddressObj->latitude;
					}
					if ( ! empty( $userAddressObj->latitude ) ) {
						$longitude = $userAddressObj->longitude;
					}
					if ( $latitude > 0 && $latitude > 0 ) {
						$joblist = Job::getJobNearByUser( $latitude, $longitude, 20, 'kilometers', $page_id );
					} else {
						$joblist = Job::with( [ 'poster', 'poster.company', 'industory', 'schedules', ] );
					}
				} else {
//					$joblist = Job::with( [ 'poster', 'poster.company', 'industory', 'schedules', ] );
					$joblist = Job::with( [ 'industory', 'schedules', ] );
				}

			}
		} else {
			$joblist = Job::with( [ 'industory', 'schedules', ] );
		}

//   status filtering .......

		if ( $request->status === 'close' ) {
			$joblist = $joblist
				->where( 'created_by', '=', $user_Id )
				->where( 'status', '=', 0 );
		} elseif ( $request->status === 'open' ) {
			$joblist = $joblist
				->where( 'created_by', '=', $user_Id )
				->where( 'status', '=', 1 );
		} else {
			$joblist = $joblist
				->where( 'created_by', '=', $user_Id );
		}


		if ( $post_code != '' || $cat_id != '' || $loc_val != '' || $keyword != '' || $distance != '' ) {
		} else {
			$joblist = $joblist->paginate( 10 );
			foreach ( $joblist as $key => $value ) {
				$app = DB::table( 'job_applications' )
				         ->where( 'job_id', $joblist[ $key ]->id )
				         ->where( 'is_hired', 1 )
				         ->get();

				$joblist[ $key ]->applications = $app;
			}
		}

		return response()->json( [ 'job_list' => $joblist ] );
	}

	/**
	 * @param $id
	 * @param Request $request
	 *
	 * @return mixed
	 */

	public function applyJob( $id, Request $request ) {
		$this->validate( $request, [
			'application_description' => 'required'
		] );
		$return_status = 500;
		$return_data   = [ 'Failed to save data' ];
		$posted_data   = $request->all();
		// apply checks
		$user_id         = auth()->user()->id;
		$job_application = new JobApplication();
		$is_applied      = $job_application->is_applied( $id );
		$is_hired        = $job_application->is_hired( $id );
		$job             = Job::find( $id );

		if ( ! isFreelancer() ) {
			$return_status = 500;
			$return_data   = [ 'Only freelancers can apply on jobs' ];
		} else if ( $is_applied ) {
			$return_status = 500;
			$return_data   = [ 'You have already applied on this job' ];
		} else if ( $is_hired ) {
			$return_status = 500;
			$return_data   = [ 'You have already been hired on this job' ];
		} else if ( $job->created_by == $user_id ) {
			$return_status = 500;
			$return_data   = [ 'You can not apply on your own job' ];
		} else {
			$job_application                          = new JobApplication();
			$job_application->application_description = $posted_data['application_description'];
			$job_application->job_id                  = $id;
			$job_application->applied_by              = $user_id;
			$is_saved                                 = $job_application->save();

			if ( $is_saved ) {
				$return_status = 200;
				$return_data   = [ 'Application has been submitted successfully' ];
			}
		}

		return response()
			->json( $return_data, $return_status );
	}

	public
	function markHired(
		$application_id
	) {

		// check if user is authorized to mark this application as hired.
		$job_application     = new JobApplication();
		$is_eligible_to_hire = $job_application->isEligibleToMarkHired( $application_id );
		if ( $is_eligible_to_hire['status_code'] == 200 ) {
			$ja = JobApplication::find( $application_id );
			event( new AwardJob( $ja ) );
			$return_data   = [ 'Hired Successfully' ];
			$return_status = 200;
		} else {
			$error_message = $is_eligible_to_hire['error_message'];
			$return_data   = [ $error_message ];
			$return_status = 500;
		}

		return response()
			->json( $return_data, $return_status );
	}

	/**
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public
	function addMoney(
		Request $request
	) {
		$return_data   = [ 'Unknown Error' ];
		$return_status = 500;
		$posted_data   = $request->all();
		$user_id       = auth()->user()->id;
		$this->validate( $request, [
			'paypal_id'             => 'required',
			'amount'                => 'required',
			'paypal_payment_status' => 'required',
			'status'                => 'required',
		] );

		$add_money_params = [
			'paypal_id'             => $posted_data['paypal_id'],
			'amount'                => $posted_data['amount'],
			'user_id'               => $user_id,
			'paypal_payment_status' => $posted_data['paypal_payment_status'],
			'status'                => $posted_data['status']
		];

		/* Email to users for selected radius */
		$job_id = $posted_data['job_id'];
		$job    = Job::find( $job_id );
		if ( $job ) {
			if ( ! empty( $job->latitude ) && ! empty( $job->longitude ) && ! empty( $job->specific_area_min ) && ! empty( $job->specific_area_max ) ) {
				$latitude          = $job->latitude;
				$longitude         = $job->longitude;
				$specific_area_min = $job->specific_area_min;
				$specific_area_max = $job->specific_area_max;
				$usersRes          = User::getUsersNearByJob( $latitude, $longitude, $specific_area_min, $specific_area_max, 'kilometers' );
				if ( count( $usersRes ) > 0 ) {
					foreach ( $usersRes as $usersResVal ) {
						$data = array(
							'name'              => $usersResVal->name,
							'specific_area_min' => $specific_area_min,
							'specific_area_max' => $specific_area_max
						);
						// Send mail
						$this->jobStore( $data, $usersResVal->id );
					}
				}
			}
		}

		// add money
		$walletTransaction = new Transaction();
		$re                = $walletTransaction->addMoney( $add_money_params );

		if ( $re ) {
			$return_data   = [ 'Data Saved Successfully' ];
			$return_status = 200;
		}

		return response()
			->json( $return_data, $return_status );
	}

	public
	function fundJobFee(
		Request $request
	) {
		$return_data   = [ 'Unknown Error' ];
		$return_status = 500;
		$posted_data   = $request->all();
		$user_id       = auth()->user()->id;
		$this->validate( $request, [
			'paypal_id'             => 'required',
			'amount'                => 'required',
			'paypal_payment_status' => 'required',
			'status'                => 'required',
		] );

		$add_money_params = [
			'paypal_id'             => $posted_data['paypal_id'],
			'amount'                => $posted_data['amount'],
			'user_id'               => $user_id,
			'paypal_payment_status' => $posted_data['paypal_payment_status'],
			'status'                => $posted_data['status']
		];

		// add money
		$walletTransaction = new Transaction();
		$re                = $walletTransaction->addMoney( $add_money_params );

		if ( $re ) {
			$return_data   = [ 'Data Saved Successfully' ];
			$return_status = 200;
		}

		return response()
			->json( $return_data, $return_status );
	}

	public
	function totalUserAwardedJobs() {
		/** @var User $user */
		$user = auth()->user();

		// todo: get jobs awarded to user
		$awarded_jobs_query = $user->applications()
		                           ->where( 'is_hired', true )
		                           ->whereDate( 'end_date_time', '>=', date( 'Y-m-d' ) );

		return response()->json( [
			'total_awarded_jobs' => $awarded_jobs_query->count(),
			'data'               => $awarded_jobs_query->get()
		] );
	}

	public
	function totalAppliedJobsForUser() {
		/** @var User $user */
		$user = auth()->user();

		// todo: get jobs applied by user
		$applied_jobs = $user->applications();

		return response()->json( [
			'total_awarded_jobs' => $applied_jobs->count(),
			'data'               => $applied_jobs->get()
		] );
	}

	public
	function totalCreatedJobsForEmployer() {
		/** @var User $user */
		$user = auth()->user();

		// todo: get jobs applied by user
		$created_jobs = $user->jobs();

		return response()->json( [
			'total_created_jobs' => $created_jobs->count(),
			'data'               => $created_jobs->get()
		] );
	}

	/**
	 * @return mixed
	 */
	public
	function myProposals() {
		$ja        = new JobApplication();
		$proposals = $ja->getMyProposals();

		return response()
			->json( $proposals, 200 );

	}

	/**
	 * Job details with routing line
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public
	function jobDetailsLocation(
		Request $request
	) {
		$this->validate( $request, [
			'job_id'  => 'required',
			'user_id' => 'required'
		] );
		$posted_data = $request->all();

		$user_address = User::where( 'id', $posted_data['user_id'] )->with( 'address' )->first();
		$job_details  = Job::with( [
			'poster',
			'poster.company',
			'industory',
			'schedules'

		] )->where( 'id', $posted_data['job_id'] )->first();

		return response()->json( [
			'user_address' => $user_address,
			'job_details'  => $job_details
		] );
	}

	public function findJobs( Request $request ) {
		$this->validate( $request, [
			'page_id' => 'required'
		] );

		$joblist     = [];
		$posted_data = $request->all();
		$page_id     = ! empty( $posted_data['page_id'] ) ? $posted_data['page_id'] : '';
		$user_id     = ! empty( $posted_data['user_id'] ) ? $posted_data['user_id'] : '';
		$post_code   = ! empty( $posted_data['post_code'] ) ? $posted_data['post_code'] : '';
		$cat_id      = ! empty( $posted_data['cat_id'] ) ? $posted_data['cat_id'] : '';
		$loc_val     = ! empty( $posted_data['loc_val'] ) ? $posted_data['loc_val'] : '';
		$keyword     = ! empty( $posted_data['keyword'] ) ? $posted_data['keyword'] : '';
		$distance    = ! empty( $posted_data['distance'] ) ? $posted_data['distance'] : '';

		if ( $post_code != '' || $cat_id != '' || $loc_val != '' || $keyword != '' || $distance != '' ) {
			if ( $post_code != '' ) {
				$post_code = trim( $post_code );
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
						$return_data   = [ 'Post code not valid!' ];
						$return_status = 403;

						return response()
							->json( $return_data, $return_status );
					}
					$latitude  = $post_code_array['latitude'];
					$longitude = $post_code_array['longitude'];
				}
				$joblist = Job::getSearchedJobNearByPostCode( $posted_data, $latitude, $longitude, 20, 'kilometers', $page_id );
			} else {
				if ( $user_id != '' ) {
					$userid = User::find( $user_id );
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
								$joblist = Job::where( 'status', '1' );
							}
						} else {
							$joblist = Job::where( 'status', '1' );
						}
					} else {
						$joblist = Job::where( 'status', '1' );
					}
				} else {
					$joblist = Job::where( 'status', '1' );
				}
			}
		} else {
			if ( $user_id != '' ) {
				$userid = User::find( $user_id );
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
							$joblist = Job::where( 'status', '1' );
						}
					} else {
						$joblist = Job::where( 'status', '1' );
					}
				} else {
					$joblist = Job::where( 'status', '1' );
				}
			} else {
				$joblist = Job::where( 'status', '1' );
			}
		}

		$joblist = $joblist->with( 'schedules' )->paginate( 10 );

		return response()->json( [
			'job_list' => $joblist
		] );
	}

	/**
	 * @return mixed
	 */
	public
	function getSecurityCategories() {
		$ja                 = new JobApplication();
		$securityCategories = SecurityCategory::all();

		return response()
			->json( $securityCategories, 200 );

	}

	/**
	 * @return mixed
	 */
	public
	function getBusinessCategories() {
		$ja                 = new JobApplication();
		$businessCategories = Businesscategory::all();

		return response()
			->json( $businessCategories, 200 );

	}

	/**
	 * @param $application_id
	 *
	 * @return mixed
	 */
	public function markApplicationAsComplete( $application_id ) {
		$application   = JobApplication::find( $application_id );
		$user_id       = auth()->user()->id;
		$return_status = 200;
		$return_data   = [ "success" ];
		$job           = Job::find( $application->job_id );
		if ( $job->created_by != $user_id ) {
			$return_status = 430;
			$return_data   = [ "You are not authorized to perform this action." ];
		}

		if ( $return_status == 200 ) {

			event( new JobHiredApplicationMarkedAsComplete( $application ) );
		}

		return response()
			->json( $return_data, $return_status );
	}

	/**
	 * @param $application_id
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function leaveFeedback( $application_id, Request $request ) {
		$posted_data = $request->all();
		$application = JobApplication::find( $application_id );
		$job         = Job::find( $application->job_id );
		$user_id     = auth()->user()->id;
		if ( $job->created_by != $user_id ) {
			$return_data   = [ 'You are not eligible to leave feedback' ];
			$return_status = 500;
		} else {
			$return_data   = [ 'Un-know error' ];
			$return_status = 500;
			$already       = Feedback::where( 'application_id', $application_id )->get();
			if ( count( $already ) ) {
				$return_status = 500;
				$return_data   = [ 'You have already left feedback' ];
			} else {
				$feedback                     = new Feedback();
				$feedback->application_id     = $application_id;
				$feedback->appearance         = ! empty( $posted_data['appearance'] ) ? ( $posted_data['appearance'] ) : 1;
				$feedback->punctuality        = ! empty( $posted_data['punctuality'] ) ? ( $posted_data['punctuality'] ) : 1;
				$feedback->customer_focused   = ! empty( $posted_data['customer_focused'] ) ? ( $posted_data['customer_focused'] ) : 1;
				$feedback->security_conscious = ! empty( $posted_data['security_conscious'] ) ? ( $posted_data['security_conscious'] ) : 1;
				$feedback->message            = ! empty( $posted_data['feedback_message'] ) ? ( $posted_data['feedback_message'] ) : null;
				$ret                          = $feedback->save();
				if ( $ret ) {
					$return_data   = [ 'Feedback Submitted successfully' ];
					$return_status = 200;
				}
			}
		}

		return response()
			->json( $return_data, $return_status );
	}


	public function get_notifications_settings( Request $request ) {
		$settings_exist = @\Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->count();

		if ( $settings_exist > 0 ) {
			$settings_data = @\Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->get();
		} else {
			$settings          = new \Responsive\NotificationsSettings;
			$settings->user_id = $request->user_id;
			$settings->save();
			$settings_data = @\Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->get();
		}

		return response()
			->json( $settings_data, 200 );
	}


	public
	function update_notifications_settings(
		Request $request
	) {
		$settings_exist = @\Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->count();

		if ( $settings_exist > 0 ) {
			Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->update( [
				'job_created' => $request->job_created,
				'job_awarded' => $request->job_awarded
			] );
			$settings_data = @\Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->get();
		} else {
			$settings              = new \Responsive\NotificationsSettings;
			$settings->user_id     = $request->user_id;
			$settings->job_created = $request->job_created;
			$settings->job_awarded = $request->job_awarded;
			$settings->save();
			$settings_data = @\Responsive\NotificationsSettings::where( 'user_id', $request->user_id )->get();
		}

		return response()
			->json( $settings_data, 200 );
	}


	public
	function get_notifications(
		Request $request
	) {
		$notifications = \Responsive\Notifications::where( 'user_id', $request->user_id )->orWhere( 'notification_type', 'all' )->orderBy( 'id', 'DESC' )->paginate();

		foreach ( $notifications as $n ) {
			$n->created_at                   = \Carbon\Carbon::parse( $n->created_at )->diffForHumans() . "";
			$n->notification_by_user_details = @\Responsive\User::where( 'id', @$n->notification_by_user_id )->get( [
				'id',
				'name',
				'email',
				'photo'
			] );

			if ( $n->job_id != '' or $n->job_id != null ) {

				$n->job_details = @\Responsive\Job::where( 'id', @$n->job_id )->get( [
					'id',
					'title',
					'per_hour_rate'
				] );
			} else {
				$n->job_details = [];
			}

		}

		return response()
			->json( $notifications, 200 );
	}


	public
	function create_notification(
		$notification_type, $applied_by, $details
	) {

		// {notification_by_user_id} hired you for the Job {job_title}

		if ( $notification_type == 'job_awarded' ) {
			$created_by      = $details[0]["created_by"];
			$created_by_name = @\Responsive\User::where( 'id', $created_by )->first( [ 'name' ] )->name;
			$message         = $created_by_name . ' hired you for the Job (' . $details[0]["title"] . ')';

			$input                            = array();
			$input['notification_type']       = $notification_type;
			$input['notification_message']    = $message;
			$input['user_id']                 = @$applied_by;
			$input['job_id']                  = @$details[0]['id'];
			$input['notification_by_user_id'] = $created_by;
			$input['is_read']                 = 0;

			$notification = @\Responsive\Notifications::create( $input );
		}

		return 1;
		//$badge_count = $for_user_notification['badge_count']+1;


	}

	/**
	 * @param $application_id
	 *
	 * @return mixed
	 */
	public
	function cancelHiredApplication(
		$application_id
	) {
		$application   = JobApplication::find( $application_id );
		$job           = Job::find( $application->job_id );
		$return_data   = [ "Un-known error" ];
		$return_status = 500;
		// if freelancer cancels the job
		if ( isFreelancer() ) {
			if ( $application->applied_by != auth()->user()->id ) {
				$return_data   = [ "You are no authorized to perform this action" ];
				$return_status = 500;
			} else if ( $application->completion_status != 0 ) {
				$return_data   = [ "Your job is either complete or already canceled" ];
				$return_status = 500;
			} else {
				$schedule_start     = $job->schedules()->get()->first();
				$start              = strtotime( $schedule_start->start );
				$current_date_time  = time();
				$difference         = $start - $current_date_time;
				$less_than_24_hours = true;
				if ( $difference > 0 ) {
					$diff_hours = $difference / ( 60 * 60 );
					if ( $diff_hours > 24 ) {
						$less_than_24_hours = false;
					}
				}
				if ( $less_than_24_hours ) {
					// leave 1 start rating
					$already = Feedback::where( 'application_id', $application_id )->get();
					if ( count( $already ) == 0 ) {
						$feedback                     = new Feedback();
						$feedback->application_id     = $application_id;
						$feedback->appearance         = 1;
						$feedback->punctuality        = 1;
						$feedback->customer_focused   = 1;
						$feedback->security_conscious = 1;
						$feedback->message            = null;
						$feedback->save();
					}
				}
				// mark application as canceled with completion_status = 2
				$application->completion_status = 2;
				$application->save();
				$return_data   = [ "Canceled Successfully" ];
				$return_status = 200;
			}

		}

		return response()
			->json( $return_data, $return_status );
	}

	/**
	 * @param $application_id
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public
	function postTip($application_id, Request $request ) {
		$this->validate( $request, [
			'tip_amount' => 'required|integer'
		] );
		$posted_data = $request->all();
		$tip_amount  = $posted_data['tip_amount'];
		$application = JobApplication::find( $application_id );
		// add inactive transaction for the tip
		$trans                    = new Transaction();
		$trans->application_id    = $application_id;
		$trans->job_id            = $application->job_id;
		$trans->debit_credit_type = 'credit';
		$trans->amount            = $tip_amount;
		$trans->title             = 'Tip';
		$trans->type              = 'tip';
		$trans->status            = 0;
		$trans->user_id           = auth()->user()->id;
		$res                      = $trans->save();
		$return_status            = 200;
		$trans->save();
		$return_data = [ 'transaction_id' => $trans->id ];

		return response()
			->json( $return_data, $return_status );

	}

	public function confirmTip( $transaction_id ) {
		$transaction = Transaction::find( $transaction_id );
		// check if user is authorized to perform this action means it should be the user who created this transaction
		if ( $transaction->user_id != auth()->user()->id ) {
			$return_status = 500;
			$return_data   = [ "You are not authorized to perform this action" ];
		} else {
			// make sure user has enough available balance to mark this tip as confirmed (activated)
			$trans            = new Transaction();
			$available_balace = $trans->getWalletAvailableBalance();
			if ( $available_balace < $transaction->amount ) {
				$return_status = 500;
				$return_data   = [ "You don't have sufficient balance to perform this action. Please load more balance." ];
			} else {
				$transaction->status                = 1;
				$transaction->credit_payment_status = 'paid';
				$transaction->save();
				$return_status = 200;
				$return_data   = [ "Your tip has been successfully added." ];
			}
		}

		return response()
			->json( $return_data, $return_status );
	}

	/**
	 * @param $freelancer_id
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function toggleFavouriteFreelancer( $freelancer_id ) {
		$return_data   = [];
		$return_status = 500;
		if ( ! empty( $freelancer_id ) ) {
			$employer_id       = auth()->user()->id;
			$already_favourite = FavouriteFreelancer::where( 'freelancer_id', $freelancer_id )
			                                        ->where( 'employer_id', $employer_id );
			if ( count( $already_favourite->get() ) > 0 ) {
				$already_favourite->delete();
				$return_data   = [ 'Freelancer removed from favourite list' ];
				$return_status = 200;
			} else {
				$fav                = new FavouriteFreelancer();
				$fav->freelancer_id = $freelancer_id;
				$fav->employer_id   = $employer_id;
				$fav->save();
				$return_data   = [ 'Freelancer added to favourite list' ];
				$return_status = 200;
			}
		}

		return response()
			->json( $return_data, $return_status );
	}

	public function allOpenJobs() {
		$uID = auth()->user()->id;
		switch ( auth()->user()->admin ) {
			case 0:
				$openJobs = Job::where( 'created_by', $uID )
				               ->where( 'status', 1 )
				               ->select( 'id as job_id', 'title as job_title' )
				               ->get();
				break;
			case 2:
				$openJobs = DB::table( 'job_applications' )
				              ->where( 'applied_by', $uID )
				              ->join( 'security_jobs', 'job_applications.job_id', '=', 'security_jobs.id' )
				              ->where( 'security_jobs.status', 1 )
				              ->select( 'security_jobs.id as job_id', 'security_jobs.title as job_title' )
				              ->get();
				break;
		}

		return response()->json( $openJobs );
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function createPaymentRequest(Request $request) {
		$this->validate($request, [
			'application_id' => 'required|integer|min:1',
			'number_of_hours' => 'required|integer|min:1'
		]);
		$return_data = ['Un-known error'];
		$return_status = 500;
		$user_id = auth()->user()->id;
		$posted_data = $request->all();
		$application = JobApplication::find($posted_data['application_id']);
		if ($application->applied_by != $user_id) {
			$return_data = ['You are not authorized to perform this action'];
			$return_status = 500;
		} else {
			// save payment request
			$job = $application->job;
			$payment_request = new PaymentRequest();
			$payment_request->application_id = $posted_data['application_id'];
			$payment_request->number_of_hours = $posted_data['number_of_hours'];
			if (!empty($posted_data['description'])) {
				$payment_request->description = $posted_data['description'];
			}
			if (!empty($posted_data['type'])) {
				$payment_request->type = $posted_data['type'];
			}
			$payment_request->save();
			$return_status = 200;
			$return_data = ['Your request has been received successfully'];
		}
		return response()
			->json($return_data, $return_status);
	}

	/**
	 * @param $payment_request_id
	 * @return mixed
	 */
	public function approvePaymentRequest($payment_request_id) {

		$return_data = ['Un-known error'];
		$return_status = 500;
		$user_id = auth()->user()->id;
		$pr = new PaymentRequest();
		$payment_request_details = $pr->getPaymentRequestsByEmployer($payment_request_id)->first();

		$wallet = new Transaction();
		$available_balance = $wallet->getWalletAvailableBalance();
		
		if ($available_balance < $payment_request_details->request_amount && $payment_request_details->type == 'extra_time') {
			$return_data = ['Your don\'t have enough balance to perform this action. Please load more balance from paypal.'];
			$return_status = 500;
		}
		else if (empty($payment_request_details)) {
			$return_data = ['You are not authorized to perform this action'];
			$return_status = 500;
		} else {
			// approve payment request
			DB::transaction(function () use ($payment_request_id, $payment_request_details, $user_id) {
				$payment_request = PaymentRequest::find($payment_request_id);
				$payment_request->status = 'approved';
				$payment_request->save();
				if ($payment_request->type == 'job_fee') { 
					// mark this application as complete
					$application = JobApplication::find($payment_request_details->application_id);
					event( new JobHiredApplicationMarkedAsComplete($application));
				} else if($payment_request->type == 'extra_time') {
					$transaction = new Transaction();
					$transaction->application_id = $payment_request->application_id;
					$transaction->status = 1;
					$transaction->job_id = $payment_request_details->job_id;
					$transaction->amount = $payment_request_details->request_amount;
					$transaction->debit_credit_type = 'credit';
					$transaction->credit_payment_status = 'paid';
					$transaction->user_id = $user_id;
					$transaction->type = 'extra_work';
					$transaction->title = 'Extra work';
					$transaction->save();
				}
			});
			$return_status = 200;
			$return_data = ['Request has been approved successfully'];

		}
		return response()
			->json($return_data, $return_status);
	}


	public function giveTip( $application_id ) {
		$wallet            = new Transaction();
		$available_balance = $wallet->getWalletAvailableBalance();

		return response()->json( [ 'application_id' => $application_id, 'available_balance' => $available_balance ] );

	}

	public function tipDetails( $transaction_id ) {
		$tip_transaction      = Transaction::find( $transaction_id );
		$application_id       = $tip_transaction->application_id;
		$application_with_job = JobApplication::with( 'job' )->where( 'id', $application_id )->get()->first();
		$wallet               = new Transaction();
		$freelancer_details   = User::find( $application_with_job->applied_by );
		$available_balance    = $wallet->getWalletAvailableBalance();

		return response()->json( [
			'transaction_details'  => $tip_transaction,
			'transaction_id'       => $transaction_id,
			'available_balance'    => $available_balance,
			'application_with_job' => $application_with_job,
			'freelancer_details'   => $freelancer_details
		] );
	}

	/**
	 * @param $job_id
	 * @return mixed
	 */
	public function pauseJob($job_id) {
		// check if created by this user
		$user_id = auth()->user()->id;
		$job = Job::find($job_id);
		//@TODO move to some comman place
		$job_hired_applications = JobApplication::where('job_id', $job_id)->where('is_hired', 1)->get();
		if ($job->created_by != $user_id) {
			$return_data = ["Sorry, You are not authorized to perform this action"];
			$return_status = 500;
		} else if (count($job_hired_applications) > 0) {
			$return_data = ["Sorry, Please withdraw all hired applications before you can pause this job"];
			$return_status = 500;
		} else {
			// set status of the job to 0 to mark it as inactive or pause
			$job->status = 0;
			$job->save();
			$return_data = ["Job successfully paused"];
			$return_status = 200;
		}
		return response()
			->json($return_data, $return_status);
	}

	/**
	 * @param $job_id
	 * @return mixed
	 */
	public function restartJob($job_id) {
		// check if created by this user
		$user_id = auth()->user()->id;
		$job = Job::find($job_id)->with('schedules')->first();
		//@TODO revisit the expiration part later on when having more info in the next mile stones
		$schedules = $job->schedules;
		$diff = 0;
		if (!empty($schedules)) {
			$first_day = $schedules[count($schedules) - 1];
			$end_time = $first_day->end;
			$current_date_time = date('Y-m-d h:i:s');
			$diff = strtotime($end_time) - strtotime($current_date_time);
		}

		if ($job->created_by != $user_id) {
			$return_data = ["Sorry, You are not authorized to perform this action"];
			$return_status = 500;
		} else if($diff < 0) {
			$return_data = ["Sorry, Job has already been expired"];
			$return_status = 500;
		} else {
			// set status of the job to 1 to mark it as active or start
			$job_start = Job::find($job_id);
			$job_start->status = 1;
			$job_start->save();
			$return_data = ["Job successfully restarted"];
			$return_status = 200;
		}
		return response()
			->json($return_data, $return_status);
	}
}
