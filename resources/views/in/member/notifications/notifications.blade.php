@extends('in.member.layouts.app')
@section('title', 'Notifications')

@section('content')

    <h1 class="fs-5 fw-semibold text-dark mb-1">Notifications</h1>
    <p class="text-muted small mb-4">Updates about your account and membership</p>

    @if($notifications->isEmpty())

        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-bell-slash text-muted" style="font-size:2rem;"></i>
                <p class="text-muted small mt-3 mb-0">You have no notifications yet.</p>
            </div>
        </div>

    @else

        <div class="card">
            <ul class="list-unstyled mb-0">
                @foreach($notifications as $notification)
                    <li class="d-flex align-items-start gap-3 px-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }} {{ is_null($notification->read_at) ? 'bg-light' : '' }}">

                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1"
                             style="width:38px;height:38px; background: {{ is_null($notification->read_at) ? 'rgba(13,42,74,.1)' : '#f1f1f1' }};">
                            <i class="bi bi-bell {{ is_null($notification->read_at) ? 'text-ifl-navy' : 'text-muted' }}"></i>
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <p class="fw-semibold text-dark mb-0 small">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                    @if(is_null($notification->read_at))
                                        <span class="d-inline-block rounded-circle bg-ifl-gold ms-1" style="width:6px;height:6px;"></span>
                                    @endif
                                </p>
                                <span class="text-muted flex-shrink-0" style="font-size:.7rem;">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-muted mb-0 small mt-1">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            @if(is_null($notification->read_at))
                                <form method="POST" action="{{ route('member.notifications.read', $notification->id) }}" class="mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size:.7rem;">
                                        <i class="bi bi-check2"></i> Mark as read
                                    </button>
                                </form>
                            @endif
                        </div>

                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>

    @endif

@endsection