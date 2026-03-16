<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteCast implements ShouldBroadcast
{
   use Dispatchable, SerializesModels;

    public $pollId;
    public $optionId;

    public function __construct($pollId, $optionId)
    {
        $this->pollId = $pollId;
        $this->optionId = $optionId;
    }

    public function broadcastOn()
    {
        return new Channel('poll.' . $this->pollId);
    }

    public function broadcastAs()
    {
        return 'vote.cast';
    }
}
