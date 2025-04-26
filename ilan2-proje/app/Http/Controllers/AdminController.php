<?php

namespace App\Http\Controllers;

use App\Models\GemiRoute;
use App\Models\User;
use App\Models\Yuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function dashboard()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $userStats = [
            'total' => User::count(),
            'online' => User::where('last_active_at', '>=', now()->subMinutes(5))->count(),
            'gemici' => User::where('user_type', 'gemici')->count(),
            'yuk_veren' => User::where('user_type', 'yuk_veren')->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
        ];

        $cargoStats = [
            'total' => Yuk::count(),
            'active' => Yuk::where('status', 'active')->count(),
            'matched' => Yuk::where('status', 'matched')->count(),
            'completed' => Yuk::where('status', 'completed')->count(),
            'new_today' => Yuk::whereDate('created_at', today())->count(),
        ];

        $routeStats = [
            'total' => GemiRoute::count(),
            'active' => GemiRoute::where('status', 'active')->count(),
            'matched' => GemiRoute::where('status', 'matched')->count(),
            'completed' => GemiRoute::where('status', 'completed')->count(),
            'new_today' => GemiRoute::whereDate('created_at', today())->count(),
        ];

        $recentActivities = $this->getRecentActivities();
        $popularRoutes = $this->getPopularRoutes();
        $matchingStats = $this->getMatchingStats();

        return view('admin.dashboard', compact(
            'userStats',
            'cargoStats',
            'routeStats',
            'recentActivities',
            'popularRoutes',
            'matchingStats'
        ));
    }

    public function users()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function cargo()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $cargo = Yuk::with('user')->latest()->paginate(20);
        return view('admin.cargo', compact('cargo'));
    }

    public function routes()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $routes = GemiRoute::with('user')->latest()->paginate(20);
        return view('admin.routes', compact('routes'));
    }

    private function getRecentActivities()
    {
        $recentCargo = Yuk::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($cargo) {
                return [
                    'type' => 'cargo',
                    'title' => $cargo->title,
                    'user' => $cargo->user->name,
                    'status' => $cargo->status,
                    'created_at' => $cargo->created_at,
                    'url' => route('yukler.show', $cargo),
                ];
            });

        $recentRoutes = GemiRoute::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($route) {
                return [
                    'type' => 'route',
                    'title' => $route->title,
                    'user' => $route->user->name,
                    'status' => $route->status,
                    'created_at' => $route->created_at,
                    'url' => route('gemi_routes.show', $route),
                ];
            });

        return $recentCargo->concat($recentRoutes)
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->all();
    }

    private function getPopularRoutes()
    {
        return GemiRoute::select('gemi_routes.*', DB::raw('COUNT(yuks.id) as match_count'))
            ->leftJoin('yuks', 'gemi_routes.id', '=', 'yuks.matched_gemi_route_id')
            ->groupBy('gemi_routes.id')
            ->orderBy('match_count', 'desc')
            ->take(5)
            ->get();
    }

    private function getMatchingStats()
    {
        $totalCargo = Yuk::count();
        $matchedCargo = Yuk::where('status', 'matched')->count();
        $matchingRate = $totalCargo > 0 ? ($matchedCargo / $totalCargo) * 100 : 0;

        $avgTimeToMatch = Yuk::whereNotNull('matched_gemi_route_id')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
            ->first()
            ->avg_hours;

        return [
            'matching_rate' => round($matchingRate, 2),
            'avg_time_to_match' => $avgTimeToMatch ? round($avgTimeToMatch, 1) : 0,
        ];
    }
}
