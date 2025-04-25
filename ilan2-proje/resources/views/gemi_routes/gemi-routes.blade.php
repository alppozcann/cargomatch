@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Rotalarım</h1>
            <a href="{{ route('gemi_routes.create') }}" class="btn btn-primary">Yeni Rota Ekle</a>
        </div>
    </div>

    @if($gemiRoutes->count() > 0)
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="routesTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Aktif Rotalar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="matched-tab" data-bs-toggle="tab" data-bs-target="#matched" type="button" role="tab" aria-controls="matched" aria-selected="false">Eşleşmiş Rotalar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Tamamlanmış Rotalar</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="routesTabsContent">
                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Başlık</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Kapasite</th>
                                            <th>Fiyat</th>
                                            <th>Hareket Tarihi</th>
                                            <th>Varış Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gemiRoutes->where('status', 'active') as $route)
                                            <tr>
                                                <td>{{ $route->title }}</td>
                                                <td>{{ $route->start_location }}</td>
                                                <td>{{ $route->end_location }}</td>
                                                <td>{{ number_format($route->available_capacity, 2) }} kg</td>
                                                <td>{{ number_format($route->price, 2) }} TL</td>
                                                <td>{{ $route->departure_date->format('d.m.Y') }}</td>
                                                <td>{{ $route->arrival_date->format('d.m.Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('gemi_routes.show', $route) }}" class="btn btn-sm btn-info">Görüntüle</a>
                                                        <a href="{{ route('gemi_routes.edit', $route) }}" class="btn btn-sm btn-primary">Düzenle</a>
                                                        <form action="{{ route('gemi_routes.destroy', $route) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu rotayı silmek istediğinize emin misiniz?')">Sil</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="matched" role="tabpanel" aria-labelledby="matched-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Başlık</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Kapasite</th>
                                            <th>Fiyat</th>
                                            <th>Hareket Tarihi</th>
                                            <th>Varış Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gemiRoutes->where('status', 'matched') as $route)
                                            <tr>
                                                <td>{{ $route->title }}</td>
                                                <td>{{ $route->start_location }}</td>
                                                <td>{{ $route->end_location }}</td>
                                                <td>{{ number_format($route->available_capacity, 2) }} kg</td>
                                                <td>{{ number_format($route->price, 2) }} TL</td>
                                                <td>{{ $route->departure_date->format('d.m.Y') }}</td>
                                                <td>{{ $route->arrival_date->format('d.m.Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('gemi_routes.show', $route) }}" class="btn btn-sm btn-info">Görüntüle</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Başlık</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Kapasite</th>
                                            <th>Fiyat</th>
                                            <th>Hareket Tarihi</th>
                                            <th>Varış Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gemiRoutes->where('status', 'completed') as $route)
                                            <tr>
                                                <td>{{ $route->title }}</td>
                                                <td>{{ $route->start_location }}</td>
                                                <td>{{ $route->end_location }}</td>
                                                <td>{{ number_format($route->available_capacity, 2) }} kg</td>
                                                <td>{{ number_format($route->price, 2) }} TL</td>
                                                <td>{{ $route->departure_date->format('d.m.Y') }}</td>
                                                <td>{{ $route->arrival_date->format('d.m.Y') }}</td>
                                                <td>
                                                    <a href="{{ route('gemi_routes.show', $route) }}" class="btn btn-sm btn-info">Görüntüle</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-12">
            <div class="alert alert-info">
                Henüz hiç rota ilanınız bulunmamaktadır. Yeni bir rota ilanı eklemek için "Yeni Rota Ekle" butonuna tıklayabilirsiniz.
            </div>
        </div>
    @endif
</div>
@endsection
