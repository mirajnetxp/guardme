<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Responsive\Job;


class Transaction extends Model {
	//
	protected $table = 'transactions';

	// Fields documentation

	/*
	 * user_id -> id of the user the amount relates to
	 * job_id -> relates to the job (for which payment is funded or paid to)
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
	 * application_id: id of job_applications table. by which we can identify that which hired user is associated with this specific transaction. will be null for the combined credit transaction (done while creating the job)
	 *
	 */
	/**
	 * @param $params
	 *
	 * @return bool
	 */
	public function addMoney( $params ) {
		$type = $defaults['type'] = ! empty( $params['type'] ) ? ( $params['type'] ) : 'add_money';
		$defaults                          = [
			'debit_credit_type' => 'debit',
			'type'              => $type
		];
		$defaults['title']                 = ! empty( $params['title'] ) ? ( $params['title'] ) : 'Adding balance';
		$defaults['amount']                = ! empty( $params['amount'] ) ? ( $params['amount'] ) : 0;
		$defaults['paypal_id']             = ! empty( $params['paypal_id'] ) ? ( $params['paypal_id'] ) : 0;
		$defaults['user_id']               = ! empty( $params['user_id'] ) ? ( $params['user_id'] ) : 0;
		$defaults['job_id']               = ! empty( $params['job_id'] ) ? ( $params['job_id'] ) : 0;
		$defaults['status']                = ! empty( $params['status'] ) ? ( $params['status'] ) : 0;
		$defaults['paypal_payment_status'] = ! empty( $params['paypal_payment_status'] ) ? ( $params['paypal_payment_status'] ) : null;

		return $this->insertTransaction( $defaults );
	}

	/**
	 * @param $params
	 *
	 * @return bool
	 */
	public function fundJobFee( $params ) {
		$defaults           = [
			'debit_credit_type'     => 'credit',
			'type'                  => 'job_fee',
			'credit_payment_status' => 'funded'
		];
		$defaults['title']  = ! empty( $params['title'] ) ? ( $params['title'] ) : 'Job Fee';
		$defaults['job_id'] = ! empty( $params['job_id'] ) ? ( $params['job_id'] ) : 0;
		$defaults['amount'] = ! empty( $params['amount'] ) ? ( $params['amount'] ) : 0;
		$defaults['status'] = ! empty( $params['status'] ) ? ( $params['status'] ) : 0;
		$defaults['paypal_id'] = ! empty( $params['paypal_id'] ) ? ( $params['paypal_id'] ) : null;

		return $this->insertTransaction( $defaults );
	}

	/**
	 * @param $params
	 *
	 * @return bool
	 */
	public function fundAdminFee( $params ) {
		$defaults           = [
			'debit_credit_type'     => 'credit',
			'type'                  => 'admin_fee',
			'credit_payment_status' => 'paid'
		];
		$defaults['title']  = ! empty( $params['title'] ) ? ( $params['title'] ) : 'Admin Fee';
		$defaults['job_id'] = ! empty( $params['job_id'] ) ? ( $params['job_id'] ) : 0;
		$defaults['amount'] = ! empty( $params['amount'] ) ? ( $params['amount'] ) : 0;
		$defaults['status'] = ! empty( $params['status'] ) ? ( $params['status'] ) : 0;
		$defaults['paypal_id'] = ! empty( $params['paypal_id'] ) ? ( $params['paypal_id'] ) : null;

		return $this->insertTransaction( $defaults );
	}

	/**
	 * @param $params
	 *
	 * @return bool
	 */
	public function fundVatFee( $params ) {
		$defaults           = [
			'debit_credit_type'     => 'credit',
			'type'                  => 'vat_fee',
			'credit_payment_status' => 'paid'
		];
		$defaults['title']  = ! empty( $params['title'] ) ? ( $params['title'] ) : 'VAT Fee';
		$defaults['job_id'] = ! empty( $params['job_id'] ) ? ( $params['job_id'] ) : 0;
		$defaults['amount'] = ! empty( $params['amount'] ) ? ( $params['amount'] ) : 0;
		$defaults['status'] = ! empty( $params['status'] ) ? ( $params['status'] ) : 0;
		$defaults['paypal_id'] = ! empty( $params['paypal_id'] ) ? ( $params['paypal_id'] ) : null;

		return $this->insertTransaction( $defaults );
	}

	/**
	 * @param $params
	 *
	 * @return bool
	 */
	protected function insertTransaction( $params ) {
		if ( empty( $params['user_id'] ) ) {
			if ( ! empty( auth()->user() ) && ! empty( auth()->user()->id ) ) {
				$params['user_id'] = auth()->user()->id;
			}
		}
		$isEligible = false;
		if ( $this->isEligibleToAddCredit( $params ) ) {
			$isEligible = true;
			DB::table( $this->table )->insert( $params );
		}

		// TODO add some message for user
		return $isEligible;
	}

	protected function isEligibleToAddCredit( $params ) {
		// TODO have to add some validation if user is eligible to add specific amount as credit or debit. for credit have to check wheather they have enough balance to add a credit entry and for debit have to check wheather there is a valid paypal transaction for that amount to be added as debit
		return true;
	}

	/**
	 * @return int
	 */
	public function getWalletAvailableBalance() {
		$balance = 0;
		$user_id = auth()->user()->id;
		if ( ! empty( $user_id ) ) {
			if ( isEmployer() ) {
				// get sum of all active debits for user
				$debit       = DB::table( $this->table )
				                 ->select( DB::raw( 'SUM(amount) as total' ) )
				                 ->groupBy( 'user_id' )
				                 ->where( 'user_id', $user_id )
				                 ->where( 'status', 1 )
				                 ->where( 'debit_credit_type', 'debit' )
				                 ->get()->first();
				$total_debit = ! empty( $debit->total ) ? ( $debit->total ) : 0;
				// get sum of all active credits for user
				$credit       = DB::table( $this->table )
				                  ->select( DB::raw( 'SUM(amount) as total' ) )
				                  ->groupBy( 'user_id' )
				                  ->where( 'user_id', $user_id )
				                  ->where( 'status', 1 )
				                  ->where( 'debit_credit_type', 'credit' )
				                  ->get()->first();
				$total_credit = ! empty( $credit->total ) ? ( $credit->total ) : 0;
				$balance      = $total_debit - $total_credit;
			}
			if ( isFreelancer() ) {
				// note: type credit for freelancer is not actually credited by freelancer these are actually credit by employer and by using flipping it we will get freelancer transactions

				// get sum of all funded transactions for a freelancer where credit payment status is funded
				$credit        = DB::table( $this->table . ' as tr' )
				                   ->select( DB::raw( 'SUM(amount) as total' ) )
				                   ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
				                   ->where( 'ja.applied_by', $user_id )
				                   ->whereNotNull( 'tr.application_id' )
				                   ->where( 'status', 1 )
				                   ->where( 'debit_credit_type', 'credit' )
				                   ->where( 'credit_payment_status', 'paid' )
				                   ->get()->first();
				$total_balance = ! empty( $credit->total ) ? ( $credit->total ) : 0;
				$balance       = $total_balance;
			}

		}
		$balance = round( $balance, 2 );

		return $balance;
	}

	public function getWalletEscrowBalance() {
		$user_id = auth()->user()->id;
		$balance = 0;
		if ( ! empty( $user_id ) ) {
			if ( isEmployer() ) {
				// get sum of all active debits for user
				$debit       = DB::table( $this->table )
				                 ->select( DB::raw( 'SUM(amount) as total' ) )
				                 ->groupBy( 'user_id' )
				                 ->where( 'user_id', $user_id )
				                 ->where( 'status', 1 )
				                 ->where( 'debit_credit_type', 'debit' )
				                 ->get()->first();
				$total_debit = ! empty( $debit->total ) ? ( $debit->total ) : 0;
				// get sum of all active credits for user
				$credit       = DB::table( $this->table )
				                  ->select( DB::raw( 'SUM(amount) as total' ) )
				                  ->groupBy( 'user_id' )
				                  ->where( 'user_id', $user_id )
				                  ->where( 'status', 1 )
				                  ->where( function ( $query ) {
					                  $query->orWhere( 'credit_payment_status', 'paid' )
					                        ->orWhere( 'type', 'vat_fee' )
					                        ->orWhere( 'type', 'admin_fee' );
				                  } )
				                  ->where( 'debit_credit_type', 'credit' )
				                  ->get()->first();
				$total_credit = ! empty( $credit->total ) ? ( $credit->total ) : 0;
				$balance      = $total_debit - $total_credit;
			}
			if ( isFreelancer() ) {
				// note: type credit for freelancer is not actually credited by freelancer these are actually credit by employer and by using flipping it we will get freelancer transactions

				// get sum of all funded transactions for a freelancer where credit payment status is funded
				$credit        = DB::table( $this->table . ' as tr' )
				                   ->select( DB::raw( 'SUM(amount) as total' ) )
				                   ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
				                   ->groupBy( 'tr.application_id' )
				                   ->where( 'ja.applied_by', $user_id )
				                   ->whereNotNull( 'tr.application_id' )
				                   ->where( 'status', 1 )
				                   ->where( 'debit_credit_type', 'credit' )
				                   ->where( 'credit_payment_status', 'paid' )
				                   ->get()->first();
				$total_balance = ! empty( $credit->total ) ? ( $credit->total ) : 0;
				$balance       = $total_balance;
			}
		}

		return $balance;
	}

	/**
	 * @return array
	 */
	public function getAllTransactionsAndEscrowBalance() {
		$return_data = [
			'escrow_balance'    => 0,
			'available_balance' => 0,
			'all_transactions'  => []
		];
		$user_id     = auth()->user()->id;
		// no need for escrow balance for freelancer, avaiable balance is escrow balance for freelancer.
		$available_balance = $this->getWalletAvailableBalance();
		if ( ! isFreelancer() ) {
			$escrow_balance = $this->getWalletEscrowBalance();
		} else {
			$escrow_balance = $available_balance;
		}
		$all_transactions = Transaction::where( 'status', 1 )
		                               ->where( 'user_id', $user_id )
		                               ->get();
		if ( ! empty( $all_transactions ) ) {
			$return_data = [
				'escrow_balance'    => $escrow_balance,
				'available_balance' => $available_balance,
				'all_transactions'  => $all_transactions
			];
		}

		return $return_data;
	}

	public function getAllTransactions() {
		$user_id          = auth()->user()->id;
		$all_transactions = [];
		// employer
		if ( isEmployer() ) {
			$query_rows = DB::table( $this->table . ' as tr' )
			                ->select(
				                'sj.id as job_id',
				                'sj.title',
				                'tr.amount as transaction_amount',
				                'tr.status as transaction_status',
				                'tr.debit_credit_type',
				                'tr.credit_payment_status',
				                'tr.type',
				                'tr.id as transaction_id',
				                'tr.created_at as payment_date',
				                'tr.paypal_id'
			                )
			                ->join( 'security_jobs as sj', 'sj.id', '=', 'tr.job_id' )
			                ->where( 'tr.status', 1 )
			                ->where( 'sj.created_by', $user_id )
			                ->orderBy( 'tr.type', 'asc' )
			                ->get();
			// re arrange
			$all_transactions = $this->reArrangeJobTransactions( $query_rows );
		}
		if ( isFreelancer() ) {
			$query_rows = DB::table( $this->table . ' as tr' )
			                ->select(
				                'sj.id as job_id',
				                'sj.title',
				                'tr.amount as transaction_amount',
				                'tr.status as transaction_status',
				                'tr.debit_credit_type',
				                'tr.credit_payment_status',
				                'tr.type',
				                'tr.id as transaction_id',
				                'tr.created_at as payment_date',
				                'tr.paypal_id'
			                )
			                ->join( 'security_jobs as sj', 'sj.id', '=', 'tr.job_id' )
			                ->join( 'job_applications as ja', 'tr.application_id', '=', 'ja.id' )
			                ->where( 'tr.status', 1 )
			                ->where( 'ja.applied_by', $user_id )
			                ->orderBy( 'tr.type', 'asc' )
			                ->get();
			// re arrange
			$all_transactions = $this->reArrangeJobTransactions( $query_rows );

		}

		return $all_transactions;
	}


	public function getTransactionJob() {
		return $this->belongsTo( Job::class, 'job_id', 'id' );
	}

	/**
	 * @return array
	 */
	public function getJobsTransactionData() {
		$available_balance    = $this->getWalletAvailableBalance();
		$escrow               = $this->getWalletEscrowBalance();
		$all_job_transactions = $this->getAllTransactions();

		return [
			'all_job_transactions' => $all_job_transactions,
			'escrow_balance'       => $escrow,
			'available_balance'    => $available_balance
		];
	}

	/**
	 * @param $query_rows
	 *
	 * @return mixed
	 */
	private function reArrangeJobTransactions( $query_rows ) {
		$all_transactions = [];
		if ( ! empty( $query_rows ) ) {
			foreach ( $query_rows as $key => $row ) {
				$job_transactions[ $row->job_id ][ $row->transaction_id ] = [
					'transaction_id'        => $row->transaction_id,
					'amount'                => $row->transaction_amount,
					'status'                => $row->transaction_status,
					'type'                  => $row->type,
					'debit_credit_type'     => $row->debit_credit_type,
					'credit_payment_status' => $row->credit_payment_status,
					'payment_date'          => $row->payment_date,
					'paypal_id'             => $row->paypal_id
				];
				$all_transactions[ $row->job_id ]                         = [
					'job_id' => $row->job_id,
					'title'  => $row->title
				];
			}
			foreach ( $all_transactions as $job_id => $job ) {
				$all_transactions[ $job_id ]['transactions'] = array_values( $job_transactions[ $job_id ] );
			}
		}

		return array_values( $all_transactions );
	}

	/**
	 * @param $job_id
	 * @return mixed
	 */
	public function getDebitTransactionForJob($job_id) {
		$transaction = Transaction::where('job_id', $job_id)->where('debit_credit_type', 'debit')->orderBy('id', 'desc')->get()->first();
		return $transaction;
	}

	/**
	 * @param \Responsive\Job $job
	 * @return array
	 */
	public function giveRefund(Job $job) {
		$job_id = $job->id;
		$schedules = $job->schedules()->get();
		$first_date = $schedules[0];
		$start_date_time = $first_date->start;
		$current_date_time = new \DateTime();
		$schedule_date_time = new \DateTime($start_date_time);
		// get list of hired applications on the job
		$hired_applications = JobApplication::where('job_id', $job_id)->where('is_hired', 1)->get();
		$completed_applications = [];
		if (!empty($hired_applications)) {
			foreach ($hired_applications as $key => $app) {
				$completed_applications[$app->id] = $app->id;
			}
		}
		if ($current_date_time >= $schedule_date_time && count($hired_applications) > 0) {
			$return_data   = [ "You can not cancel this job" ];
			$return_status = 500;
		} else {
			$interval = $current_date_time->diff($schedule_date_time);
			$days_left = $interval->format('%d');
			$months_left = $interval->format('%m');
			$years_left = $interval->format('%y');
			if ($days_left > 0 || $months_left > 0 || $years_left > 0) {
				// make a full refund
				$this->processRefund($job);
			} else if (count($hired_applications) == 0) {
				// make a full refund
				$this->processRefund($job);
			} else {
				/* have to do partial refund */
				$this->processRefund($job, $completed_applications, 'partial');

			}
			$return_data   = [ "Job canceled successfully" ];
			$return_status = 200;
		}
		return ['return_data' => $return_data, 'return_status' => $return_status];
	}

	/**
	 * @param $job
	 * @param string $type
	 */
	private function processRefund($job, $completed_applications = [], $type = 'full') {
		$job_id = $job->id;
		if ($type == 'full') {
			DB::transaction(function () use ($job_id) {
				$full_debit_transaction = $this->getDebitTransactionForJob($job_id);
				// disable all old credit transactions for the job
				DB::table($this->table)
					->where('job_id', $job_id)
					->where('debit_credit_type',  'credit')
					->update(['status' => 0, 'title' => 'canceled']);

				// create new credit entry for refund to equalize the debit funds
				$transaction_params = [
					'debit_credit_type' => 'credit',
					'type' => 'refund',
					'title' => 'full refund for the job',
					'job_id' => $job_id,
					'user_id' => auth()->user()->id,
					'paypal_id' => $full_debit_transaction->paypal_id,
					'status' => 1,
					'amount' => $full_debit_transaction->amount,
					'credit_payment_status' => 'paid',
					'extra_details' => 'full refund of the amount ' . $full_debit_transaction->amount . ' is made for canceling the job with job id '. $job_id
				];
				DB::table( $this->table )->insert( $transaction_params );
				DB::table('security_jobs')->where('id', $job_id)->update(['status' => 0]);
			});
		} else {
			/*criteria ---> deduct 10% from the vat fee,
									10% from admin fee, and
									10% from job fee for each hired freelancer
			rest of the amount will be refunded to the employer by creating an equalent credit entry in transactions*/
			// calculate total amount to be refunded
			$total_refund_amounts = [];

			// get all credit entries
			$credit_entries = Transaction::where('job_id', $job_id)->where('debit_credit_type', 'credit')->get();

			if (!empty($credit_entries)) {
				$refund_paypal_id = $credit_entries[0]->paypal_id;
				foreach ($credit_entries as $key => $credit_row) {
					if (!empty($completed_applications) && !empty($credit_row->application_id)) {
						if (!empty($completed_applications[$credit_row->application_id])) {
							continue;
						}
					}
					if ($credit_row->type == 'job_fee' && empty($credit_row->application_id)) {
						 if ($credit_row->amount > 0) {
							 $total_refund_amounts['full amount for un awarded vacancies'] = $credit_row->amount;
						 }
					} else {
						if ($credit_row->amount > 0) {
							$penalty_value = ($credit_row->amount * 10)/100;
							$credit_value = $credit_row->amount - $penalty_value;
							if ($credit_row->type == 'job_fee') {
								$total_refund_amounts['amount after 10% penalty for job fee for application_id ' . $credit_row->application_id] = $credit_value;
							} else {
								$total_refund_amounts['amount after 10% penalty for ' . $credit_row->type . ' for job_id ' . $credit_row->job_id] = $credit_value;
							}
							$penalty_rows[] = [
								'user_id' => $credit_row->user_id,
								'job_id' => $credit_row->job_id,
								'debit_credit_type' => $credit_row->debit_credit_type,
								'amount' => $penalty_value,
								'type' => $credit_row->type,
								'title' => '10% penalty',
								'status' => 1,
								'credit_payment_status' => 'paid',
								'paypal_id' => $credit_row->paypal_id,
								'application_id' => $credit_row->application_id
							];
						}
					}

					if (!empty($total_refund_amounts)) {
						// add refund row
						$refund_row = [
							'user_id' => auth()->user()->id,
							'job_id' => $job_id,
							'debit_credit_type' => 'credit',
							'amount' => array_sum($total_refund_amounts),
							'type' => 'refund',
							'title' => 'amont after deducting 10% penalty',
							'status' => 1,
							'credit_payment_status' => 'paid',
							'extra_details' => json_encode($total_refund_amounts),
							'paypal_id' => $refund_paypal_id
						];
					}
				}
			}


			DB::transaction(function () use ($job_id, $penalty_rows, $refund_row) {
				// disable credit rows for the job
				DB::table($this->table)->where('job_id', $job_id)->where('debit_credit_type', 'credit')->update(['status' => 0, 'title' => 'canceled']);
				// add penalty rows //TODO add both of them to the same query
				DB::table($this->table)->insert($penalty_rows);
				// add refund row
				DB::table($this->table)->insert($refund_row);
				// make job inactive
				DB::table('security_jobs')->where('id', $job_id)->update(['status' => 0]);
			});
		}

	}

	public function getEmployerPaymentRecords($transaction_id = null) {
		$query = Transaction::where('debit_credit_type', 'credit')
			->where(function ($query){
				$query->where('credit_payment_status', 'paid');
				$query->orWhere('credit_payment_status', 'complete');
			})
			->where('type', 'refund')
			->where('transactions.status', 1)
			->where('transactions.credit_payment_status', 'paid');
			if (!empty($transaction_id)) {
				$query->where('transactions.id', $transaction_id);
			}
		$query->join('users', 'users.id', 'transactions.user_id')
			->join('security_jobs', 'security_jobs.id', 'transactions.job_id')
			->select(
				'users.id',
				'users.name',
				'users.email',
				'transactions.id as transaction_id',
				'transactions.amount',
				'transactions.type',
				'transactions.status',
				'transactions.paypal_id',
				'transactions.extra_details',
				'transactions.credit_payment_status',
				'security_jobs.title as job_title'
			);
			$transactions = $query->get();
		return $transactions;
	}

}