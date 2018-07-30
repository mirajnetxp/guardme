<?php namespace Responsive\Http\Controllers\Admin;

use Responsive\Http\Controllers\Admin\AdminController;
/*use App\Article;
use App\ArticleCategory;
use App\User;
use App\Photo;
use App\PhotoAlbum;*/
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Responsive\Transaction;

class DashboardController extends AdminController {

    public function __construct()
    {
        parent::__construct();
        view()->share('type', '');
    }

	public function index()
	{
        $title = "Dashboard";
		
		$total_user = DB::table('users')
			           ->count();

		$seller_id=2;
        $total_seller = DB::table('users')
			           ->where('admin','=', $seller_id)
					   ->count();
		
		$customer_id=0;
        $total_customer = DB::table('users')
			           ->where('admin','=', $customer_id)
					   ->count();
					   
					   
		$total_booking = DB::table('booking')
			             ->count();


		$curdate = date("Y-m-d");			   
		$today_booking = DB::table('booking')
			           ->where('curr_date','=', $curdate)
					   ->count();

         $total_shop = DB::table('shop')
		              ->count();
					  
					  
					  
					  
			          $curr_date=date("Y-m-d");

					$last_date1=date('Y-m-d',strtotime("-1 days"));
					$last_date2=date('Y-m-d',strtotime("-2 days"));
					$last_date3=date("Y-m-d", strtotime("-3 days"));
					$last_date4=date("Y-m-d", strtotime("-4 days"));
					$last_date5=date("Y-m-d", strtotime("-5 days"));
					$last_date6=date("Y-m-d", strtotime("-6 days"));



                      $date1 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $curr_date . "%")
					   ->count();
					   $date2 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $last_date1 . "%")
					   ->count();
					   $date3 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $last_date2 . "%")
					   ->count();
					   $date4 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $last_date3 . "%")
					   ->count();
					   $date5 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $last_date4 . "%")
					   ->count();
					   $date6 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $last_date5 . "%")
					   ->count();
					   $date7 = DB::table('security_jobs')
			           ->where('created_at','LIKE',"%" . $last_date6 . "%")
					   ->count();



				$javas="{ label: '$last_date6', y: $date7 },";
				$javas.="{ label: '$last_date5', y: $date6 },";
				$javas.="{ label: '$last_date4', y: $date5 },";
				$javas.="{ label: '$last_date3', y: $date4 },";
				$javas.="{ label: '$last_date2', y: $date3 },";
				$javas.="{ label: '$last_date1', y: $date2 },";
				$javas.="{ label: '$curr_date', y: $date1 },";


		  
					  
					  
			$booking	= DB::table('booking')
			            ->leftJoin('users', 'users.email', '=', 'booking.user_email')
			           ->orderBy('booking.curr_date','desc')
					   ->limit(5)->offset(0)
					   ->get();	  
					  
				$set_id=1;
		$setting = DB::table('settings')->where('id', $set_id)->get();	   
		
		$users = DB::table('users')
		         ->orderBy('id','desc')
				 ->limit(4)->offset(0)
				 ->get();
				 
			$testimonials = DB::table('testimonials')
		         ->orderBy('id','desc')
				 ->limit(3)->offset(0)
				 ->get();





			$trans = new Transaction();

			$partner_balance = $trans->getPartnerBalance();
			$admin_fee = $trans->getAdminFee();
			$vat_fee = $trans->getVatFee();
			$partner_balance = !empty($partner_balance->total) ? ($partner_balance->total) : 0;
			$vat_fee = !empty($vat_fee->total) ? ($vat_fee->total) : 0;
			$admin_fee = !empty($admin_fee->total) ? ($admin_fee->total) : 0;

		$data = array('total_seller' => $total_seller, 'total_user' => $total_user, 'total_customer' => $total_customer, 'total_booking' => $total_booking,
		'today_booking' => $today_booking, 'total_shop' =>  $total_shop, 'javas' => $javas, 'booking' => $booking, 'setting' => $setting, 'users' => $users,
		'testimonials' => $testimonials, 'partner_balance' => $partner_balance, 'admin_fee' => $admin_fee, 'vat_fee' => $vat_fee);
		
		return view('admin.index')->with($data);
		
		
		
		
	}
}