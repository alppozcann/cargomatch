@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Eşleşen Yükler</h2>

    @if($matchedYukler->isEmpty())
        <p>Henüz eşleşen yük bulunmamaktadır.</p>
    @else
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($matchedYukler as $yuk)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-2 fw-semibold">{{ $yuk->description ?? 'Yük Tanımı Yok' }}</h5>
                            <p class="card-text mb-1">
                                <strong>Ağırlık:</strong> {{ $yuk->weight }} {{ $yuk->weight_unit }}
                            </p>
                            <p class="card-text mb-1">
                                <strong>Rota:</strong> {{ $yuk->from_location }} &rarr; {{ $yuk->to_location }}
                            </p>
                            <p class="card-text text-muted small mb-0">
                                <strong>Durum:</strong> {{ ucfirst($yuk->status) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection