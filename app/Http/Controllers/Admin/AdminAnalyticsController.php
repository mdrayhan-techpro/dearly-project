<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use App\Models\MatchResult;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WaxSeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        // 1. User Growth (Past 14 Days)
        $dailyRegistrations = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = User::whereDate('created_at', $date->toDateString())->count();
            $dailyRegistrations[] = [
                'date' => $date->format('M d'),
                'count' => $count,
            ];
        }

        // 2. Letters Activity (Past 14 Days)
        $dailyLetters = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dispatched = Letter::whereDate('created_at', $date->toDateString())->count();
            $read = Letter::whereDate('read_at', $date->toDateString())->count();
            $dailyLetters[] = [
                'date' => $date->format('M d'),
                'dispatched' => $dispatched,
                'read' => $read,
            ];
        }

        // 3. Postal Velocity Metrics
        $totalActiveUsers = max(1, User::where('is_suspended', false)->count());
        $totalLetters = Letter::count();
        $lettersPerUser = round($totalLetters / $totalActiveUsers, 1);

        $readLetters = Letter::whereNotNull('read_at')
            ->whereNotNull('delivered_at')
            ->get(['delivered_at', 'read_at']);

        $totalReadHours = 0;
        foreach ($readLetters as $rl) {
            $diffHours = $rl->delivered_at->diffInHours($rl->read_at);
            $totalReadHours += $diffHours;
        }
        $avgReadHours = $readLetters->count() > 0 ? round($totalReadHours / $readLetters->count(), 1) : 0;

        // 4. Language Demographics
        $languageCounts = UserProfile::select('preferred_language', DB::raw('count(*) as total'))
            ->groupBy('preferred_language')
            ->orderBy('total', 'desc')
            ->get();

        $languagesMap = [
            'en' => 'English',
            'bn' => 'বাংলা (Bengali)',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'ja' => '日本語',
            'ar' => 'العربية',
        ];

        // 5. Popular Stationery, Stamps, Wax Seals
        $popularThemes = StationeryTheme::withCount('letters')
            ->orderBy('letters_count', 'desc')
            ->take(5)
            ->get();

        $popularStamps = PostageStamp::withCount('letters')
            ->orderBy('letters_count', 'desc')
            ->take(5)
            ->get();

        $popularSeals = WaxSeal::withCount('letters')
            ->orderBy('letters_count', 'desc')
            ->take(5)
            ->get();

        // 6. Resonance summary
        $avgCompatibility = round((float) MatchResult::avg('compatibility_score'), 1);
        $totalKindredPairs = MatchResult::where('compatibility_score', '>=', 90)->count();

        return view('admin.analytics.index', compact(
            'dailyRegistrations',
            'dailyLetters',
            'lettersPerUser',
            'avgReadHours',
            'languageCounts',
            'languagesMap',
            'popularThemes',
            'popularStamps',
            'popularSeals',
            'avgCompatibility',
            'totalKindredPairs',
            'totalActiveUsers',
            'totalLetters'
        ));
    }
}
