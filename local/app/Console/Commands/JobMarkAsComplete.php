<?php

namespace Responsive\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Responsive\User;

class JobMarkAsComplete extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'JobMarkAsComplete:markjobascomplete';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'To mark a job as compelete';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$user = User::find( 3 );

		$user->name = 'cron working';
		$user->save();

	}
}
