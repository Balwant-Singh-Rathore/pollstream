<?php

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->poll = Poll::factory()->create([
        'created_by' => $this->user->id,
        'total_votes' => 0
    ]);
    $this->option = PollOption::factory()->create([
        'poll_id' => $this->poll->id,
        'votes_count' => 0
    ]);
});

test('user can vote successfully', function () {

    $response = $this->post("/poll/{$this->poll->slug}/vote", [
        'poll_id' => $this->poll->id,
        'option_id' => $this->option->id
    ]);

    $response->assertStatus(302);

    expect(Vote::count())->toBe(1);

    expect(PollOption::first()->votes_count)->toBe(1);

    expect(Poll::first()->total_votes)->toBe(1);

});


test('user cannot vote more than once using same ip', function () {
    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->post("/poll/{$this->poll->slug}/vote", [
            'poll_id' => $this->poll->id,
            'option_id' => $this->option->id
        ]);

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
        ->post("/poll/{$this->poll->slug}/vote", [
            'poll_id' => $this->poll->id,
            'option_id' => $this->option->id
        ]);

    expect(Vote::count())->toBe(1);
});


test('vote increments option vote count', function () {
    $this->post("/poll/{$this->poll->slug}/vote", [
        'poll_id' => $this->poll->id,
        'option_id' => $this->option->id
    ]);

    $this->option->refresh();

    expect($this->option->votes_count)->toBe(1);
});

