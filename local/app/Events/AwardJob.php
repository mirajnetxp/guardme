<?php

namespace Responsive\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Responsive\JobApplication;

class AwardJob
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $job_application;

    /**
     * AwardJob constructor.
     * @param JobApplication $jobApplication
     */
    public function __construct(JobApplication $jobApplication)
    {
        //
        $this->job_application = $jobApplication;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
