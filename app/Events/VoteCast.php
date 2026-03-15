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
    public $votes;

    public function __construct($pollId, $optionId, $votes)
    {
        $this->pollId = $pollId;
        $this->optionId = $optionId;
        $this->votes = $votes;
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
