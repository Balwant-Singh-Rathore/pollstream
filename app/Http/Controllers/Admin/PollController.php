<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePoll;
use App\Models\Poll;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PollController extends Controller
{
    public function index()
    {
        try {
            $polls = Poll::latest()
            ->paginate(10);

            return view('admin.polls.index', compact('polls'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return back()->withErrors('An error occurred while loading polls. Please try again.');
        }
    }

    public function create()
    {
        return view('admin.polls.create');
    }

    public function store(CreatePoll $request)
    {
        try {
            DB::beginTransaction();

            $poll = Poll::create([
                'question' => $request->question,
                'slug' => Str::slug($request->question) . '-' . uniqid(),
                'created_by' => auth()->id(),
            ]);

            foreach ($request->options as $option) {
                $poll->options()->create(['option_text' => $option]);
            }

            DB::commit();

            return redirect()->route('polls')->with('success', 'Poll created successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return back()->withErrors('An error occurred while creating the poll. Please try again.');
        }
    }

    public function results($id)
    {
        try {
            $poll = Poll::with('options.votes')->findOrFail($id);
            $shareLink = url('/poll/' . $poll->slug);
            return view('admin.polls.results', compact('poll', 'shareLink'));
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return back()->withErrors('An error occurred while fetching poll results. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $poll = Poll::findOrFail($id);
            $poll->delete();
            return redirect()->route('polls')->with('success', 'Poll deleted successfully!');
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return back()->withErrors('An error occurred while deleting the poll. Please try again.');
        }
    }
}
