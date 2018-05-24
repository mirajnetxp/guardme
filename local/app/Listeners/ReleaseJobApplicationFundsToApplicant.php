<?php

namespace Responsive\Listeners;

use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReleaseJobApplicationFundsToApplicant
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  JobHiredApplicationMarkedAsComplete  $event
     * @return void
     */
    public function handle(JobHiredApplicationMarkedAsComplete $event)
    {
        //
        $application = $event->job_application;
        //@TODO mark the job as complete.
        //@TODO Release funds to the applicant.
    }
}
