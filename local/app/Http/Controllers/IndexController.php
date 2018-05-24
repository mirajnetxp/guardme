<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Responsive\Url;
use Responsive\Businesscategory;
use Responsive\Job;

class IndexController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function sangvish_index() {
		$services = DB::table( 'services' )->limit( 7 )->get();
		$one      = DB::table( 'services' )->orderBy( 'name', 'asc' )->limit( 1 )->offset( 0 )->get();
		$first    = DB::select( 'select * from subservices where service = ?', [ $one[0]->id ] );

		$two    = DB::table( 'services' )->orderBy( 'name', 'asc' )->limit( 1 )->offset( 1 )->get();
		$second = DB::select( 'select * from subservices where service = ?', [ $two[0]->id ] );

		$three = DB::table( 'services' )->orderBy( 'name', 'asc' )->limit( 1 )->offset( 2 )->get();
		$third = DB::select( 'select * from subservices where service = ?', [ $three[0]->id ] );

		$four   = DB::table( 'services' )->orderBy( 'name', 'asc' )->limit( 1 )->offset( 3 )->get();
		$fourth = DB::select( 'select * from subservices where service = ?', [ $four[0]->id ] );


		$b_cats = Businesscategory::all();
		$locs   = Job::select( 'city_town' )->where( 'city_town', '!=', null )->distinct()->get();


		$testimonials = DB::table( 'testimonials' )->orderBy( 'id', 'desc' )->get();
		/** Fetch Wordpress Blog Posts Via API REQUEST**/

		$url      = 'http://blog.guarddme.com/wp-json/wp/v2/posts?per_page=3';
		$response = $this->curl( $url );

		$posts = json_decode( $response, true );


		$new_posts = array();
		$count2    = 0;

			foreach ( $posts as $post ) {
				$new_post[ $count2 ]['title']   = $post['title']['rendered'];
				$new_post[ $count2 ]['content'] = $this->custom_echo( $post['content']['rendered'], 300 );
				$new_post[ $count2 ]['date']    = $post['date'];
				$new_post[ $count2 ]['link']    = $post['guid']['rendered'];


				$url2     = $post['_links']['wp:featuredmedia']['0']['href'];
				$respons2 = $this->curl( $url2 );
				$image    = json_decode( $respons2, true );

				$category_id   = $post['categories'];
				$url3          = 'http://blog.guarddme.com/wp-json/wp/v2/categories/' . $category_id[0];
				$respons3      = $this->curl( $url3 );
				$category_data = json_decode( $respons3, true );


				$new_post[ $count2 ]['image']    = $image['guid']['rendered'];
				$new_post[ $count2 ]['category'] = $category_data['name'];
				$count2 ++;
			}

		/** Fetch Wordpress Blog Posts Via API REQUEST**/

		$data = array(
			'posts'        => $new_post,
			'services'     => $services,
			'one'          => $one,
			'first'        => $first,
			'two'          => $two,
			'second'       => $second,
			'three'        => $three,
			'third'        => $third,
			'four'         => $four,
			'fourth'       => $fourth,
			'testimonials' => $testimonials,
			'b_cats'       => $b_cats,
			'locs'         => $locs
		);

		return view( 'index' )->with( $data );
	}


	public function sangvish_autoComplete( Request $request ) {
		$query = $request->get( 'term', '' );

		$viewsubservice = DB::table( 'subservices' )->where( 'subname', 'LIKE', '%' . $query . '%' )->orderBy( 'subname', 'asc' )->get();

		$data = array();
		foreach ( $viewsubservice as $viewsub ) {
			$data[] = array( 'value' => $viewsub->subname, 'id' => $viewsub->subid );
		}
		if ( count( $data ) ) {
			return $data;
		} else {
			return [ 'value' => 'No Result Found', 'id' => '' ];
		}
	}


	private function curl( $url ) {

		$ch2 = curl_init();
		curl_setopt( $ch2, CURLOPT_URL, $url );
		curl_setopt( $ch2, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch2, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3" );
		curl_setopt( $ch2, CURLOPT_FOLLOWLOCATION, true );
		$respons2 = curl_exec( $ch2 );
		curl_close( $ch2 );

		return $respons2;

	}

	private function custom_echo( $x, $length ) {
		if ( strlen( $x ) <= $length ) {
			return $x;
		} else {
			$y = substr( $x, 0, $length ) . '...';

			return $y;
		}
	}


}
