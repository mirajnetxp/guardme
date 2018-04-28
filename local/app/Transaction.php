<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Transaction extends Model
{
    //
    protected $table = 'transactions';

    // Fields documentation

    /*
     * user_id -> id of the user the amount relates to
     * job_id -> relates to the job (for which payment is funded or paid to), only necessary if debit_credit_type = credit
     * debit_credit_type: possible values = ['debit' or 'credit']
     * amount: float value of amount
     * type: possible values 'admin_fee', 'vat_fee' or job_fee only needed for credit
     * title: any title string for example 'created job' or could be job title etc.
     * status: integer could be 0 or 1 active or inactive transaction
     * credit_payment_status: shows that either amount is paid or funded (only for debit_credit_type = credit)
     * paypal_id -> id from paypal on successful transaction only necessary for debit
     * paypal_payment_status -> approved (only for debit)
     * extra_details: could be any extra detail
     * created_at: timestamp transaction is created at
     * updated_at: timestamp transaction is updated at
     *
     */
    /**
     * @param $params
     * @return bool
     */
    public function addMoney($params) {
        $defaults = [
            'debit_credit_type' => 'debit',
            'type' => 'add_money'
        ];
        $defaults['title'] = !empty($params['title']) ? ($params['title']) : 'Adding balance';
        $defaults['amount'] = !empty($params['amount']) ? ($params['amount']) : 0;
        $defaults['paypal_id'] = !empty($params['paypal_id']) ? ($params['paypal_id']) : 0;
        $defaults['user_id'] = !empty($params['user_id']) ? ($params['user_id']) : 0;
        $defaults['status'] = !empty($params['status']) ? ($params['status']) : 0;
        $defaults['paypal_payment_status'] = !empty($params['paypal_payment_status']) ? ($params['paypal_payment_status']) : null;
        return $this->insertTransaction($defaults);
    }

    /**
     * @param $params
     * @return bool
     */
    public function fundJobFee($params) {
        $defaults = [
            'debit_credit_type' => 'credit',
            'type' => 'job_fee',
            'credit_payment_status' => 'funded'
        ];
        $defaults['title'] = !empty($params['title']) ? ($params['title']) : 'Job Fee';
        $defaults['job_id'] = !empty($params['job_id']) ? ($params['job_id']) : 0;
        $defaults['amount'] = !empty($params['amount']) ? ($params['amount']) : 0;
        $defaults['status'] = !empty($params['status']) ? ($params['status']) : 0;
        return $this->insertTransaction($defaults);
    }

    /**
     * @param $params
     * @return bool
     */
    public function fundAdminFee($params) {
        $defaults = [
            'debit_credit_type' => 'credit',
            'type' => 'admin_fee',
            'credit_payment_status' => 'paid'
        ];
        $defaults['title'] = !empty($params['title']) ? ($params['title']) : 'Admin Fee';
        $defaults['job_id'] = !empty($params['job_id']) ? ($params['job_id']) : 0;
        $defaults['amount'] = !empty($params['amount']) ? ($params['amount']) : 0;
        $defaults['status'] = !empty($params['status']) ? ($params['status']) : 0;
        return $this->insertTransaction($defaults);
    }

    /**
     * @param $params
     * @return bool
     */
    public function fundVatFee($params) {
        $defaults = [
            'debit_credit_type' => 'credit',
            'type' => 'vat_fee',
            'credit_payment_status' => 'paid'
        ];
        $defaults['title'] = !empty($params['title']) ? ($params['title']) : 'VAT Fee';
        $defaults['job_id'] = !empty($params['job_id']) ? ($params['job_id']) : 0;
        $defaults['amount'] = !empty($params['amount']) ? ($params['amount']) : 0;
        $defaults['status'] = !empty($params['status']) ? ($params['status']) : 0;
        return $this->insertTransaction($defaults);
    }

    /**
     * @param $params
     * @return bool
     */
    protected function insertTransaction($params) {
        if (empty($params['user_id'])) {
            if (!empty(auth()->user()) && !empty(auth()->user()->id)) {
                $params['user_id'] = auth()->user()->id;
            }
        }
        $isEligible = false;
        if($this->isEligibleToAddCredit($params)) {
            $isEligible = true;
            DB::table($this->table)->insert($params);
        }
        // TODO add some message for user
        return $isEligible;
    }

    protected function isEligibleToAddCredit($params) {
        // TODO have to add some validation if user is eligible to add specific amount as credit or debit. for credit have to check wheather they have enough balance to add a credit entry and for debit have to check wheather there is a valid paypal transaction for that amount to be added as debit
        return true;
    }

    public function getWalletAvailableBalance() {
        $user_id = auth()->user()->id;
        if(!empty($user_id)) {
            // get sum of all active debits for user
            $debit = DB::table($this->table)
                ->select(DB::raw('SUM(amount) as total'))
                ->groupBy('user_id')
                ->where('user_id', $user_id)
                ->where('status', 1)
                ->where('debit_credit_type', 'debit')
                ->get()->first();
            $total_debit = !empty($debit->total) ? ($debit->total) : 0;
            // get sum of all active credits for user
            $credit = DB::table($this->table)
                ->select(DB::raw('SUM(amount) as total'))
                ->groupBy('user_id')
                ->where('user_id', $user_id)
                ->where('status', 1)
                ->where('debit_credit_type', 'credit')
                ->get()->first();
            $total_credit = !empty($credit->total) ? ($credit->total) : 0;
            $balance = $total_debit - $total_credit;
            return $balance;
        }
    }

    public function getWalletEscrowBalance() {
        $user_id = auth()->user()->id;
        if(!empty($user_id)) {
            // get sum of all active debits for user
            $debit = DB::table($this->table)
                ->select(DB::raw('SUM(amount) as total'))
                ->groupBy('user_id')
                ->where('user_id', $user_id)
                ->where('status', 1)
                ->where('debit_credit_type', 'debit')
                ->get()->first();
            $total_debit = !empty($debit->total) ? ($debit->total) : 0;
            // get sum of all active credits for user
            $credit = DB::table($this->table)
                ->select(DB::raw('SUM(amount) as total'))
                ->groupBy('user_id')
                ->where('user_id', $user_id)
                ->where('status', 1)
                ->where(function($query){
                    $query->orWhere('credit_payment_status', 'paid')
                        ->orWhere('type', 'vat_fee')
                        ->orWhere('type', 'admin_fee');
                })
                ->where('debit_credit_type', 'credit')
                ->get()->first();
            $total_credit = !empty($credit->total) ? ($credit->total) : 0;
            $balance = $total_debit - $total_credit;
            return $balance;
        }
    }
}