<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use DB;
class PaymentRequest extends Model
{
    //
    protected $table = 'payment_requests';
    public $timestamps = false;

    /**
     * @param null $payment_request_id
     * @return mixed
     */
    public function getPaymentRequestsByEmployer($payment_request_id = null) {
        $user_id = auth()->user()->id;
        $query = DB::table($this->table . ' as pr')
            ->select(
                'sj.title',
                'sj.id as job_id',
                'freelancer.name as freelancer_name',
                'sj.per_hour_rate',
                'pr.number_of_hours',
                'pr.type',
                'pr.description',
                'pr.status',
                'pr.id',
                'pr.application_id',
                DB::raw('sj.per_hour_rate * pr.number_of_hours as request_amount')
            )
            ->join('job_applications as ja', 'ja.id', 'pr.application_id')
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('users as u', 'u.id', '=', 'sj.created_by')
            ->join('users as freelancer', 'freelancer.id', '=', 'ja.applied_by');
            if (!empty($payment_request_id)) {
                $query->where('pr.id', $payment_request_id);
            }
        $query->orderBy('sj.id', 'asc')
            ->orderBy('pr.id', 'asc')
            ->where('sj.created_by', $user_id);
        return $res = $query->get();
    }
}
