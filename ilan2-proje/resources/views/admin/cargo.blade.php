@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Yük Yönetimi</h1>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Tüm Yükler</h5>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Yük ara...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <select class="form-select" id="statusFilter" style="width: auto;">
                        <option value="">Tüm Durumlar</option>
                        <option value="active">Aktif</option>
                        <option value="matched">Eşleşmiş</option>
                        <option value="completed">Tamamlanmış</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="cargoTable">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Başlık</th>
                            <th>Yük Veren</th>
                            <th>Rota</th>
                            <th>Ağırlık</th>
                            <th>Fiyat</th>
                            <th>Teslimat Tarihi</th>
                            <th>Durum</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cargo as $item)
                            <tr data-status="{{ $item->status }}">
                                <td>{{ $item->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $item->title }}</h6>
                                            <small class="text-muted">{{ $item->yuk_type }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $item->user->name }}</h6>
                                        @if($item->user->company_name)
                                            <small class="text-muted">{{ $item->user->company_name }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="d-block">{{ $item->from_location }}</small>
                                        <i class="bi bi-arrow-down text-muted"></i>
                                        <small class="d-block">{{ $item->to_location }}</small>
                                    </div>
                                </td>
                                <td>{{ number_format($item->weight, 2) }} kg</td>
                                <td>{{ number_format($item->proposed_price, 2) }} TL</td>
                                <td>{{ $item->desired_delivery_date->format('d.m.Y') }}</td>
                                <td>
                                    @switch($item->status)
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
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            İşlemler
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('yukler.show', $item) }}">
                                                    <i class="bi bi-eye me-2"></i>Detayları Görüntüle
                                                </a>
                                            </li>
                                            @if($item->status === 'active')
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-link-45deg me-2"></i>Eşleşmeleri Göster
                                                    </a>
                                                </li>
                                            @endif
                                            @if($item->status === 'matched')
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="bi bi-truck me-2"></i>Eşleşen Rotayı Gör
                                                    </a>
                                                </li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('yukler.destroy', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bu yükü silmek istediğinize emin misiniz?')">
                                                        <i class="bi bi-trash me-2"></i>Sil
                                                    </button>
                                                </form>
                                            </li>
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
                    <small class="text-muted">Toplam {{ $cargo->total() }} yük</small>
                </div>
                {{ $cargo->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('cargoTable');
    const rows = table.getElementsByTagName('tr');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        Array.from(rows).forEach((row, index) => {
            if (index === 0) return; // Skip header row
            
            const text = row.textContent.toLowerCase();
            const status = row.dataset.status;
            
            const matchesSearch = text.includes(searchText);
            const matchesStatus = !statusValue || status === statusValue;
            
            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endpush
@endsection 