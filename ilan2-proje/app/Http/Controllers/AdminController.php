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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get user statistics
        $userStats = [
            'total' => User::count(),
            'online' => User::where('last_active_at', '>=', now()->subMinutes(5))->count(),
            'gemici' => User::where('user_type', 'gemici')->count(),
            'yuk_veren' => User::where('user_type', 'yuk_veren')->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
        ];

        // Get cargo statistics
        $cargoStats = [
            'total' => Yuk::count(),
            'active' => Yuk::where('status', 'active')->count(),
            'matched' => Yuk::where('status', 'matched')->count(),
            'completed' => Yuk::where('status', 'completed')->count(),
            'new_today' => Yuk::whereDate('created_at', today())->count(),
        ];

        // Get route statistics
        $routeStats = [
            'total' => GemiRoute::count(),
            'active' => GemiRoute::where('status', 'active')->count(),
            'matched' => GemiRoute::where('status', 'matched')->count(),
            'completed' => GemiRoute::where('status', 'completed')->count(),
            'new_today' => GemiRoute::whereDate('created_at', today())->count(),
        ];

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get popular routes
        $popularRoutes = $this->getPopularRoutes();

        // Get matching statistics
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

    /**
     * Show all users.
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Show all cargo packages.
     *
     * @return \Illuminate\View\View
     */
    public function cargo()
    {
        $cargo = Yuk::with('user')->latest()->paginate(20);
        return view('admin.cargo', compact('cargo'));
    }

    /**
     * Show all ship routes.
     *
     * @return \Illuminate\View\View
     */
    public function routes()
    {
        $routes = GemiRoute::with('user')->latest()->paginate(20);
        return view('admin.routes', compact('routes'));
    }

    /**
     * Get recent activities across the platform.
     *
     * @return array
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Get recent cargo
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

        // Get recent routes
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

        // Merge and sort by created_at
        $activities = $recentCargo->concat($recentRoutes)
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->all();

        return $activities;
    }

    /**
     * Get popular routes based on matches.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPopularRoutes()
    {
        return GemiRoute::select('gemi_routes.*', DB::raw('COUNT(yuks.id) as match_count'))
            ->leftJoin('yuks', 'gemi_routes.id', '=', 'yuks.matched_gemi_route_id')
            ->groupBy('gemi_routes.id')
            ->orderBy('match_count', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get matching statistics.
     *
     * @return array
     */
    private function getMatchingStats()
    {
        // Get matching rate (percentage of cargo that gets matched)
        $totalCargo = Yuk::count();
        $matchedCargo = Yuk::where('status', 'matched')->count();
        $matchingRate = $totalCargo > 0 ? ($matchedCargo / $totalCargo) * 100 : 0;

        // Get average time to match
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