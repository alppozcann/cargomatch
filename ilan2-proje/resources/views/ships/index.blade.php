@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Gemilerim</h2>
        <a href="{{ route('ships.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Yeni Gemi Ekle
        </a>
    </div>

    @if($ships->count() > 0)
        <div class="row">
            @foreach($ships as $ship)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">{{ $ship->ship_name }}</h5>
                            <div class="mb-2">
                                <span class="badge bg-light text-dark me-2">Plaka: {{ $ship->plate_code }}</span>
                                <span class="badge bg-info text-dark">Kapasite: {{ $ship->carrying_capacity }} ton</span>
                            </div>
                            <p class="mb-2">
                                <strong>Taşıyabildiği Yük Türleri:</strong> 
                                <span class="text-primary">
                                @if(is_array($ship->load_types))
                                    {{ implode(', ', $ship->load_types) }}
                                @else
                                    {{ $ship->load_types }}
                                @endif
                                </span>
                            </p>
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('ships.edit', $ship->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Düzenle
                                </a>
                                <form action="{{ route('ships.destroy', $ship->id) }}" method="POST" onsubmit="return confirm('Bu gemiyi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Sil
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            Henüz gemi kaydınız bulunmamaktadır. 
            <a href="{{ route('ships.create') }}">Buradan yeni bir gemi ekleyebilirsiniz.</a>
        </div>
    @endif
</div>
@endsection
