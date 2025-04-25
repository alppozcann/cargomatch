@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Yeni Gemi Rotası Ekle</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('gemi_routes.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_location" class="form-label">Başlangıç Noktası</label>
                            <input type="text" class="form-control @error('start_location') is-invalid @enderror" id="start_location" name="start_location" value="{{ old('start_location') }}" required>
                            @error('start_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="end_location" class="form-label">Bitiş Noktası</label>
                            <input type="text" class="form-control @error('end_location') is-invalid @enderror" id="end_location" name="end_location" value="{{ old('end_location') }}" required>
                            @error('end_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ara Duraklar</label>
                        <div id="waypoints-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="way_points[]" placeholder="Ara durak">
                                <button type="button" class="btn btn-outline-secondary remove-waypoint" disabled>Kaldır</button>
                            </div>
                        </div>
                        <button type="button" id="add-waypoint" class="btn btn-sm btn-outline-secondary">+ Ara Durak Ekle</button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="available_capacity" class="form-label">Boş Kapasite (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('available_capacity') is-invalid @enderror" id="available_capacity" name="available_capacity" value="{{ old('available_capacity') }}" required>
                            @error('available_capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Fiyat (TL)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departure_date" class="form-label">Hareket Tarihi</label>
                            <input type="datetime-local" class="form-control @error('departure_date') is-invalid @enderror" id="departure_date" name="departure_date" value="{{ old('departure_date') }}" required>
                            @error('departure_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="arrival_date" class="form-label">Varış Tarihi</label>
                            <input type="datetime-local" class="form-control @error('arrival_date') is-invalid @enderror" id="arrival_date" name="arrival_date" value="{{ old('arrival_date') }}" required>
                            @error('arrival_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('gemi_routes.index') }}" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Rotayı Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const waypointsContainer = document.getElementById('waypoints-container');
        const addWaypointBtn = document.getElementById('add-waypoint');
        
        // Ara durak ekle
        addWaypointBtn.addEventListener('click', function() {
            const newWaypoint = document.createElement('div');
            newWaypoint.className = 'input-group mb-2';
            newWaypoint.innerHTML = `
                <input type="text" class="form-control" name="way_points[]" placeholder="Ara durak">
                <button type="button" class="btn btn-outline-secondary remove-waypoint">Kaldır</button>
            `;
            waypointsContainer.appendChild(newWaypoint);
            
            // İlk ara durağın kaldır butonu aktif olsun
            if (waypointsContainer.children.length > 1) {
                waypointsContainer.querySelector('.remove-waypoint').removeAttribute('disabled');
            }
        });
        
        // Ara durak kaldır
        waypointsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-waypoint') && !e.target.disabled) {
                e.target.closest('.input-group').remove();
                
                // Eğer sadece bir ara durak kaldıysa, kaldır butonu pasif olsun
                if (waypointsContainer.children.length === 1) {
                    waypointsContainer.querySelector('.remove-waypoint').setAttribute('disabled', true);
                }
            }
        });
    });
</script>
@endsection
@endsection
