<div class="dropdown">
    @php
        $unreadCount = auth()->user()->unreadNotifications()->count();
        $allCount = auth()->user()->notifications()->count();
    @endphp

    <button class="btn btn-white border shadow-sm d-flex align-items-center gap-2"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="d-none d-md-inline">Bildirimler</span>
        @if($unreadCount > 0)
            <span class="notification-badge">{{ $unreadCount }}</span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 mt-2"
        style="width: 370px; max-height: 420px; overflow-y: auto;">
        <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light">
            <span class="fw-bold">Bildirimler</span>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button class="btn btn-link btn-sm text-decoration-none text-primary p-0">Tümünü Okundu Yap</button>
                </form>
            @endif
            @if($allCount > 0)
                <form method="POST" action="{{ route('notifications.deleteAll') }}" onsubmit="return confirm('Tüm bildirimleri silmek istediğinize emin misiniz?');" class="d-inline-block mb-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 d-inline-flex align-items-center">
                        <i class="bi bi-trash me-1"></i> Sil
                    </button>
                </form>
            @endif
        </li>

        @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
            <li class="dropdown-item px-3 py-2 {{ $notification->read_at ? '' : 'bg-warning-subtle' }} border-bottom">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-semibold mb-1 text-wrap" style="word-break: break-word; white-space: normal;">
                            {{ $notification->data['message'] ?? 'Yeni bildirim' }}
                        </div>
                        @if(isset($notification->data['yuk_id']))
                            <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary mt-1">Ayrıntılara Git</a>
                        @endif
                        <div class="text-muted small mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary ms-2">Okundu</button>
                        </form>
                    @endif
                </div>
            </li>
        @empty
            <li class="dropdown-item text-center text-muted py-4">Bildirim yok</li>
        @endforelse
    </ul>
</div>
