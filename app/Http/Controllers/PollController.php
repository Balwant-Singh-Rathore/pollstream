<?php

namespace App\Http\Controllers;

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

            $alreadyVoted = Vote::where('poll_id', $request->poll_id)
                ->where('ip_address', $ip)
                ->exists();

            if ($alreadyVoted) {
                return back()->with('error', 'You have already voted on this poll.');
            }

            DB::transaction(function () use ($request, $ip) {
                Vote::create([
                    'poll_id' => $request->poll_id,
                    'poll_option_id' => $request->option_id,
                    'ip_address' => $ip
                ]);

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
