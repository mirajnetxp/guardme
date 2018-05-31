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

class SearchController extends Controller {

//	public function getpersonnelsearch( Request $request ) {
//		$this->validate( $request, [
//			'page_id' => 'required'
//		] );
//		$sec_personnels  = [];
//		$posted_data     = $request->all();
//		$page_id         = ! empty( $posted_data['page_id'] ) ? $posted_data['page_id'] : '';
//		$user_id         = ! empty( $posted_data['user_id'] ) ? $posted_data['user_id'] : '';
//		$post_code       = ! empty( $posted_data['post_code'] ) ? $posted_data['post_code'] : '';
//		$cat_val         = ! empty( $posted_data['cat_val'] ) ? $posted_data['cat_val'] : '';
//		$gender          = ! empty( $posted_data['gender'] ) ? $posted_data['gender'] : '';
//		$location_filter = ! empty( $posted_data['location_filter'] ) ? $posted_data['location_filter'] : '';
//		$sec_personnel   = ! empty( $posted_data['sec_personnel'] ) ? $posted_data['sec_personnel'] : '';
//		$distance        = ! empty( $posted_data['distance'] ) ? $posted_data['distance'] : '';
//
//		if ( $post_code != '' || $cat_val != '' || $gender != '' || $location_filter != '' || $sec_personnel != '' || $distance != '' ) {
//			if ( $post_code != '' ) {
//				$post_code = trim( $post_code );
//				if ( ! empty( $post_code ) ) {
//					$postcode_url = "https://api.getaddress.io/find/" . $post_code . "?api-key=ZTIFqMuvyUy017Bek8SvsA12209&sort=true";
//					$postcode_url = str_replace( ' ', '%20', $postcode_url );
//					$ch           = curl_init();
//					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
//					curl_setopt( $ch, CURLOPT_HEADER, false );
//					curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
//					curl_setopt( $ch, CURLOPT_URL, $postcode_url );
//					curl_setopt( $ch, CURLOPT_REFERER, $postcode_url );
//					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
//					$getBas = curl_exec( $ch );
//					curl_close( $ch );
//					$post_code_array = json_decode( $getBas, true );
//
//					if ( isset( $post_code_array['Message'] ) || empty( $post_code_array ) ) {
//						$return_data   = [ 'Post code not valid!' ];
//						$return_status = 403;
//
//
//						return response()
//							->json( $return_data, $return_status );
//					}
//					$latitude  = $post_code_array['latitude'];
//					$longitude = $post_code_array['longitude'];
//				}
//				$sec_personnels = User::getPersonnelSearchNearBy( $posted_data, $latitude, $longitude, 20, 'kilometers', $page_id );
//			} else {
//				$sec_personnels = User::getPersonnelNearBy( $posted_data, $page_id );
//			}
//		} else {
//			$sec_personnels = User::getPersonnelNearBy( $data, $page_id );
//		}
//
//
//		return response()->json( [
//			'personnel_list' => $sec_personnels
//		] );
//	}


	function getpersonnelsearch( $user_id = null ) {
		$data = \request()->all();


		//		Users cannot show up on freelancer list unless profile is complete.
		$query = User::where( 'admin', '=', '2' )
		             ->where( 'doc_verified', '=', true )//		             ->join( 'job_applications', 'users.id', '=', 'job_applications.applied_by' )//		             ->select('users.name')
		;


		if ( count( $data ) ) {

			// todo: filter by category
			$search_category = isset( $data['cat_val'] ) ? trim( $data['cat_val'] ) : null;
			if ( $search_category && $search_category != 'all' ) {
				$query = $query->whereHas( 'sec_work_category', function ( $q ) use ( $search_category ) {
					$q->where( 'name', $search_category );
				} );
			}

			// todo: filter by gender
			$search_gender = isset( $data['gender'] ) ? trim( $data['gender'] ) : null;
			if ( $search_gender && $search_gender != 'all' ) {
				$query = $query->where( 'gender', $search_gender );
			}

			// todo: search filter, location
			$location_search_filter = isset( $data['location_filter'] ) ? trim( $data['location_filter'] ) : null;

			if ( $location_search_filter ) {
				$location_search_query_array = explode( ' ', trim( $location_search_filter ) );

				if ( count( $location_search_query_array ) ) {
					foreach ( $location_search_query_array as $search_location ) {
						$query = $query
							->whereHas( 'address', function ( $q ) use ( $search_location ) {
								$q->where( 'citytown', $search_location );
							} );
					}
				}
			}

			// todo: filter location
			/*$search_location = trim($data['loc_val']);

			if($search_location){
				$query = $query
					->whereHas('address', function ($q) use ($search_location){
						$q->where('citytown', $search_location);
					});
			}*/

			// todo: filter user
			$personnel_query = isset( $data['sec_personnel'] ) ? $data['sec_personnel'] : null;

			if ( $personnel_query ) {
				$search_query_array = explode( ' ', trim( $personnel_query ) );

				if ( count( $search_query_array ) ) {
					foreach ( $search_query_array as $search_key ) {
						$query = $query
							->where( 'name', 'LIKE', "%$search_key%" )
							->orWhere( 'email', 'LIKE', "%$search_key%" )
							->orWhere( 'firstname', 'LIKE', "%$search_key%" )
							->orWhere( 'lastname', 'LIKE', "%$search_key%" );
					}
				}
			}
		}
		$cats = DB::table( 'security_categories' )->orderBy( 'name', 'asc' )->get();

		$locs = DB::table( 'address' )->distinct()->get();

		$sec_personnels = $query
			->with( 'person_address' )
			->paginate( 10 );
//
//		foreach ( $sec_personnels as $key => $value ) {
//			$sec_personnels[$key]['rating']=null;
//
//			$rat=
//
//		}


		return response()->json( $sec_personnels );

	}

	public function personnelprofile( $id ) {

		$person = User::with( [ 'person_address', 'sec_work_category' ] )->find( $id );

		$feedbackHis = DB::table( 'job_applications' )
		                 ->where( 'job_applications.applied_by', $id )
		                 ->Join( 'feedback', 'job_applications.id', '=', 'feedback.application_id' )
		                 ->Join( 'security_jobs', 'job_applications.job_id', '=', 'security_jobs.id' )
		                 ->select( 'security_jobs.title as job_title', 'feedback.appearance', 'feedback.punctuality', 'feedback.customer_focused', 'feedback.security_conscious', 'feedback.message' )
		                 ->get()
		                 ->map( function ( $item, $key ) {
			                 $item->average_feedback = ( $item->appearance + $item->punctuality + $item->customer_focused + $item->security_conscious ) / 4;

			                 return $item;
		                 } );

		$collection = collect( $person );

		$merged = $collection->merge( [ 'feedback_history' => $feedbackHis ] );

		$merged->all();


		return response()->json( $merged );
	}


}