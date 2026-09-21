{{-- resources/views/partials/topbar.blade.php --}}
<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">

            {{-- Left: menu toggle + greeting --}}
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu"></i>
                    </button>
                </li>

                @php
                    $user = Auth::user();
                    $unreadCount = $user ? $user->unreadNotifications->count() : 0;
                    $hour = \Carbon\Carbon::now('Asia/Kolkata')->hour;

                    if ($hour < 12) {
                        $greeting = 'Good Morning';
                    } elseif ($hour < 17) {
                        $greeting = 'Good Afternoon';
                    } elseif ($hour < 21) {
                        $greeting = 'Good Evening';
                    } else {
                        $greeting = 'Good Night';
                    }
                @endphp

                <li class="d-none d-lg-block ms-3">
                    <h5 class="mb-0 d-flex align-items-center" id="greeting-section">
                        <span id="greeting-icon" class="me-2 fs-5">🌞</span>
                        <span id="topbar-greeting">{{ $greeting }},</span>
                        <span id="topbar-username" class="ms-1">{{ $user->name ?? 'Guest' }}</span>
                    </h5>
                </li>
            </ul>

            {{-- Right: notifications + user profile --}}
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                {{-- Notifications Dropdown --}}
                @if ($user && $user->role === 'admin')
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle" href="#" id="topbar-notification-link" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="bell" class="noti-icon"></i>
                            @if ($unreadCount > 0)
                                <span id="topbar-noti-badge" class="badge bg-danger rounded-circle noti-icon-badge">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="topbar-notification-link"
                            style="width: 420px;">
                            <li class="dropdown-item px-3 py-2 border-bottom d-flex justify-content-between">
                                <strong>Notifications</strong>
                            </li>

                            <li>
                                <div id="topbar-noti-list" class="noti-scroll p-2"
                                    style="max-height: 350px; overflow-y: auto;">
                                    @forelse($user ? $user->unreadNotifications->take(10) : collect() as $n)
                                        @php
                                            $notifUrl = $n->data['url'] ?? '#';
                                        @endphp
                                        <a href="{{ $notifUrl ?: '#' }}" class="dropdown-item notify-item"
                                            data-id="{{ $n->id }}">
                                            <div class="d-flex align-items-start gap-2">
                                                <div>
                                                    @if (isset($n->data['avatar']))
                                                        <img src="{{ $n->data['avatar'] }}" class="rounded-circle"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <i class="mdi mdi-bell-outline fs-4 text-primary"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <p class="mb-0 fw-semibold">
                                                            {{ $n->data['title'] ?? 'Notification' }}</p>
                                                        <small
                                                            class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <small class="text-truncate d-block" style="max-width: 300px;">
                                                        {{ \Illuminate\Support\Str::limit($n->data['message'] ?? ($n->data['body'] ?? ''), 120) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center text-muted p-3">No new notifications</div>
                                    @endforelse
                                </div>
                            </li>

                            @php
                                // Check if notifications index route exists
                                $hasNotificationsRoute = Route::has('notifications.index');
                            @endphp

                            @if ($hasNotificationsRoute)
                                <li class="dropdown-footer text-center border-top">
                                    <a href="{{ route('notifications.index') }}" class="text-primary d-block py-2">
                                        View All <i class="fe-arrow-right ms-1"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                {{-- User profile dropdown --}}
                <li class="nav-item dropdown ms-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-1 pe-2 bg-light-hover rounded-pill"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="userDropdown"
                        aria-label="User menu">
                        <div class="position-relative d-flex align-items-center">
                            <img src="{{ $user->avatar ?? asset('assets/images/users/user-5.jpg') }}"
                                class="rounded-circle border border-2 border-white shadow-sm flex-shrink-0"
                                style="width: 38px; height: 38px; object-fit: cover;"
                                alt="{{ $user->name ?? 'Guest' }} profile picture">

                            <div class="d-none d-md-block ms-2 text-start flex-grow-1 overflow-hidden">
                                <div class="d-flex align-items-center" style="min-width: 0;">
                                    <span class="fw-semibold text-dark text-truncate"
                                        style="max-width: 120px;">{{ $user->name ?? 'Guest' }}</span>
                                    @if ($user->role ?? false)
                                        <small class="text-muted ms-1 flex-shrink-0"
                                            style="font-size: 0.75rem;">({{ ucfirst($user->role) }})</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <i class="mdi mdi-chevron-down ms-1 text-muted flex-shrink-0"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" aria-labelledby="userDropdown"
                        style="min-width: 220px;">
                        <li class="dropdown-header bg-light py-2 px-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar ?? asset('assets/images/users/user-5.jpg') }}"
                                    class="rounded-circle me-2" style="width: 48px; height: 48px; object-fit: cover;"
                                    alt="{{ $user->name ?? 'Guest' }}">
                                <div>
                                    <h6 class="m-0 fw-semibold">{{ $user->name ?? 'Guest' }}</h6>
                                    <small class="text-muted">{{ $user->email ?? '' }}</small>
                                </div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2"
                                href="{{ route('admin.profile.show') }}">
                                <i class="fas fa-user-circle me-3 text-primary"></i>
                                <div>
                                    <span>My Profile</span>
                                    <small class="d-block text-muted">View & edit profile</small>
                                </div>
                            </a>
                        </li>

                        @if (($user->role ?? '') === 'admin')
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2"
                                    href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-3 text-secondary"></i>
                                    <div>
                                        <span>Admin Dashboard</span>
                                        <small class="d-block text-muted">System overview</small>
                                    </div>
                                </a>
                            </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2 text-danger"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout me-3"></i>
                                <div>
                                    <span>Logout</span>
                                    <small class="d-block text-muted">Sign out of your account</small>
                                </div>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>

@push('styles')
    <style>
        #greeting-section {
            display: flex;
            align-items: center;
            font-size: 1rem;
            font-weight: 600;
        }

        #greeting-icon {
            font-size: 1.4rem;
            transition: transform 0.3s ease;
        }

        #greeting-section:hover #greeting-icon {
            transform: rotate(15deg);
        }

        #topbar-greeting {
            color: #0a2540;
            font-weight: 600;
        }

        #topbar-username {
            color: #6c757d;
            font-weight: 500;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof feather !== 'undefined') feather.replace();

            const greetingIcon = document.getElementById('greeting-icon');
            const greetingText = document.getElementById('topbar-greeting');

            function updateGreeting() {
                const hour = new Date().toLocaleString('en-US', {
                    hour: 'numeric',
                    hour12: false,
                    timeZone: 'Asia/Kolkata'
                });
                const h = parseInt(hour);
                let text = '',
                    icon = '';

                if (h < 12) {
                    text = 'Good Morning';
                    icon = '🌞';
                } else if (h < 17) {
                    text = 'Good Afternoon';
                    icon = '🌤️';
                } else if (h < 21) {
                    text = 'Good Evening';
                    icon = '🌇';
                } else {
                    text = 'Good Night';
                    icon = '🌙';
                }

                greetingText.textContent = text + ',';
                greetingIcon.textContent = icon;
            }

            updateGreeting();
            setInterval(updateGreeting, 60000);

            // ===== Topbar notifications: mark as read on click =====
            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                '{{ csrf_token() }}';

            const badgeEl = document.getElementById('topbar-noti-badge');

            document.querySelectorAll('.notify-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    const id = this.dataset.id;
                    const targetUrl = this.getAttribute('href') || '#';

                    if (!id) {
                        // no id => just follow the link
                        return;
                    }

                    // prevent default so we can fire the AJAX first
                    e.preventDefault();

                    // fire-and-forget AJAX to mark as read
                    try {
                        fetch("{{ route('notifications.markRead') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                id
                            }),
                        }).then(() => {
                            // optimistically update badge count in UI
                            if (badgeEl) {
                                const current = parseInt((badgeEl.textContent || '0')
                                .trim(), 10) || 0;
                                const next = current > 0 ? current - 1 : 0;
                                badgeEl.textContent = next;
                                if (next === 0) {
                                    badgeEl.style.display = 'none';
                                }
                            }
                        }).catch(err => {
                            console.error('Failed to mark notification as read', err);
                        });
                    } catch (err) {
                        console.error('Mark-read error', err);
                    }

                    // navigate to target page
                    if (targetUrl && targetUrl !== '#') {
                        window.location.href = targetUrl;
                    } else {
                        // if no URL, just close dropdown by triggering a click outside or reload
                        window.location.reload();
                    }
                });
            });
        });
    </script>
@endpush
