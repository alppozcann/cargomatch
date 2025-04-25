@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="section-title">Admin Dashboard</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- User Stats -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i>Kullanıcı İstatistikleri</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Toplam Kullanıcı</span>
                        <span class="badge bg-primary">{{ $userStats['total'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Çevrimiçi</span>
                        <span class="badge bg-success">{{ $userStats['online'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Gemici</span>
                        <span class="badge bg-info">{{ $userStats['gemici'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Yük Veren</span>
                        <span class="badge bg-warning">{{ $userStats['yuk_veren'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Bugün Yeni</span>
                        <span class="badge bg-secondary">{{ $userStats['new_today'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cargo Stats -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Yük İstatistikleri</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Toplam Yük</span>
                        <span class="badge bg-primary">{{ $cargoStats['total'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Aktif</span>
                        <span class="badge bg-success">{{ $cargoStats['active'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Eşleşmiş</span>
                        <span class="badge bg-info">{{ $cargoStats['matched'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Tamamlanmış</span>
                        <span class="badge bg-secondary">{{ $cargoStats['completed'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Bugün Yeni</span>
                        <span class="badge bg-warning">{{ $cargoStats['new_today'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Stats -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-compass me-2"></i>Rota İstatistikleri</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Toplam Rota</span>
                        <span class="badge bg-primary">{{ $routeStats['total'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Aktif</span>
                        <span class="badge bg-success">{{ $routeStats['active'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Eşleşmiş</span>
                        <span class="badge bg-info">{{ $routeStats['matched'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Tamamlanmış</span>
                        <span class="badge bg-secondary">{{ $routeStats['completed'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Bugün Yeni</span>
                        <span class="badge bg-warning">{{ $routeStats['new_today'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Activities -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Son Aktiviteler</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tür</th>
                                    <th>Başlık</th>
                                    <th>Kullanıcı</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>
                                            @if($activity['type'] === 'cargo')
                                                <i class="bi bi-box-seam text-primary"></i>
                                            @else
                                                <i class="bi bi-compass text-success"></i>
                                            @endif
                                        </td>
                                        <td>{{ $activity['title'] }}</td>
                                        <td>{{ $activity['user'] }}</td>
                                        <td>
                                            @switch($activity['status'])
                                                @case('active')
                                                    <span class="badge bg-success">Aktif</span>
                                                    @break
                                                @case('matched')
                                                    <span class="badge bg-info">Eşleşti</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-secondary">Tamamlandı</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $activity['status'] }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $activity['created_at']->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ $activity['url'] }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matching Stats and Popular Routes -->
        <div class="col-md-4">
            <!-- Matching Stats -->
            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Eşleşme İstatistikleri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Eşleşme Oranı</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $matchingStats['matching_rate'] }}%"
                                 aria-valuenow="{{ $matchingStats['matching_rate'] }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                        <div class="text-end mt-1">
                            <small class="text-muted">{{ number_format($matchingStats['matching_rate'], 1) }}%</small>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Ortalama Eşleşme Süresi</h6>
                        <p class="h3 mb-0">{{ number_format($matchingStats['avg_time_to_match'], 1) }} saat</p>
                    </div>
                </div>
            </div>

            <!-- Popular Routes -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="bi bi-star me-2"></i>Popüler Rotalar</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($popularRoutes as $route)
                            <a href="{{ route('gemi_routes.show', $route) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $route->title }}</h6>
                                    <small class="text-muted">{{ $route->start_location }} → {{ $route->end_location }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $route->match_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
