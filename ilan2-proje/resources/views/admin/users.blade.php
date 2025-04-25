@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Kullanıcı Yönetimi</h1>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Tüm Kullanıcılar</h5>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Kullanıcı ara...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="usersTable">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Ad Soyad</th>
                            <th>E-posta</th>
                            <th>Kullanıcı Tipi</th>
                            <th>Durum</th>
                            <th>Kayıt Tarihi</th>
                            <th>Son Aktivite</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            @if($user->company_name)
                                                <small class="text-muted">{{ $user->company_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($user->user_type === 'gemici')
                                        <span class="badge bg-info">Gemici</span>
                                    @elseif($user->user_type === 'yuk_veren')
                                        <span class="badge bg-warning">Yük Veren</span>
                                    @else
                                        <span class="badge bg-secondary">Belirlenmemiş</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->last_active_at && $user->last_active_at->gt(now()->subMinutes(5)))
                                        <span class="badge bg-success">Çevrimiçi</span>
                                    @else
                                        <span class="badge bg-secondary">Çevrimdışı</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    @if($user->last_active_at)
                                        {{ $user->last_active_at->diffForHumans() }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            İşlemler
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bi bi-person-lines-fill me-2"></i>Profili Görüntüle
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bi bi-box-seam me-2"></i>Yükleri Görüntüle
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bi bi-compass me-2"></i>Rotaları Görüntüle
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            @if(!$user->is_admin)
                                                <li>
                                                    <form action="#" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-person-x me-2"></i>Hesabı Askıya Al
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Toplam {{ $users->total() }} kullanıcı</small>
                </div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function(e) {
        const searchText = e.target.value.toLowerCase();

        Array.from(rows).forEach((row, index) => {
            if (index === 0) return; // Skip header row
            
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
});
</script>
@endpush
@endsection
