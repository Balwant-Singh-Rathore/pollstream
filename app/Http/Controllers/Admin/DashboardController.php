<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $stats = Poll::selectRaw('
                COUNT(*) as total_polls,
                SUM(total_votes) as total_votes,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_polls
            ')->first();

            return view('admin.dashboard', [
                'totalPolls' => $stats->total_polls ?? 0,
                'totalVotes' => $stats->total_votes ?? 0,
                'totalActivePolls' => $stats->active_polls ?? 0
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return back()->withErrors('An error occurred while loading the dashboard. Please try again.');
        }
    }
}
