<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Job;
use Responsive\Transaction;
class JobsController extends Controller
{
    //
    public function create(Request $request) {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
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
            'wallet_debit_frequency' => 'required',
        ]);
        $posted_data = $request->all();
        $working_days = !empty($posted_data['working_days']) ? $posted_data['working_days'] : 0;
        $working_hours = !empty($posted_data['working_hours']) ? $posted_data['working_hours'] : 0;
        $pay_per_hour = !empty($posted_data['pay_per_hour']) ? $posted_data['pay_per_hour'] : 0;
        $wallet_debit_frequency = !empty($posted_data['wallet_debit_frequency']) ? $posted_data['wallet_debit_frequency'] : null;

        $job = Job::find($id);
        $logged_in_id = !empty(auth()->user()->id) ? (auth()->user()->id) : 0;
        $return_data = ['Not allowed to perform this action'];
        $return_status = 500;
        if (!empty($job) && !empty($job->created_by) && $job->created_by == $logged_in_id) {
            $job->daily_working_hours = $working_hours;
            $job->monthly_working_days = $working_days;
            $job->per_hour_rate = $pay_per_hour;
            $job->wallet_debit_frequency = $wallet_debit_frequency;
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
}
