<?php

namespace App\Http\Controllers;

use App\Events\VoteCast;
use App\Http\Requests\MakeVote;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PollController extends Controller
{
    public function index($slug)
    {
        try {
            $poll = Poll::with('options')->where('slug', $slug)->firstOrFail();

            $ip = request()->ip();

            $alreadyVoted = Vote::where('poll_id', $poll->id)
                ->where('ip_address', $ip)
                ->exists();

            return view('polls.public', compact('poll', 'alreadyVoted'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return redirect('/')->withErrors('Poll not found.');
        }
    }

    public function vote(MakeVote $request, $slug)
    {
        try {
            $ip = $request->ip();

            # here we are using atomic insert so it will help to prevent duplicates votes from database side itself
            # we already have indexed ip_address and poll_id in votes table so it will be fast to check for duplicates and uniqness of votes
            # we can use redis/kafka consumer as well for higher performance but for 10k-20K concurrent users this approach should be sufficient and simpler to implement
            $inserted = DB::table('votes')->insertOrIgnore([
                'poll_id' => $request->poll_id,
                'poll_option_id' => $request->option_id,
                'ip_address' => $ip,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if(!$inserted) {
                return back()->withErrors('You have already voted in this poll.');
            }

            broadcast(new VoteCast(
                $request->poll_id,
                $request->option_id,
                Poll::where('id', $request->poll_id)->value('total_votes')
            ));

            defer(function () use ($request) {
                Poll::where('id', $request->poll_id)
                    ->increment('total_votes');

                PollOption::where('id', $request->option_id)
                    ->increment('votes_count');
            });

            return back()->with('voted', $request->option_id);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return redirect('/')->withErrors('Unable to record your vote. Please try again.');
        }
    }
}
