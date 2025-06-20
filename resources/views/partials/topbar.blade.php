<div class="topbar">
    <div class="topbar-left">
        <button id="hamburger-btn" class="hamburger-btn">
            <i class="fas fa-bars"></i>
        </button>
        <div class="greeting">
            Hello, <strong>{{ Auth::user()->first_name }}</strong>
        </div>
    </div>

    <div class="topbar-right">
        <!-- Notification Icon Button -->

        <div id="notification-wrapper" style="position: relative; display: inline-block;">
            <button id="notification-btn" style="background:none; border:none; cursor:pointer; font-size: 20px; position: relative;">
                <i class="fas fa-bell"></i>
                <span id="notification-count" style="
                position: absolute;
                top: -5px;
                right: -5px;
                background: red;
                color: white;
                border-radius: 50%;
                padding: 2px 6px;
                font-size: 12px;
                display: none;
                ">0</span>

            </button>

            <!-- Dropdown container -->
            <div id="notification-dropdown" class="notif-dropdown">
                <ul id="notification-list" style="list-style: none; margin:0; padding: 10px;"></ul>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div id="profile-wrapper" style="position: relative; display: inline-block;">
            <button id="profile-btn" style="background:none; border:none; cursor:pointer; font-size: 20px;">
                <i class="fas fa-user-circle"></i>
            </button>
            <div id="profile-dropdown" style="
            display: none;
            position: absolute;
            right: 0;
            background: white;
            min-width: 150px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 999;
            border-radius: 6px;
            overflow: hidden;
        ">
                <ul style="list-style: none; margin:0; padding:0;">
                    @if(Auth::user() && (Auth::user()->role === 'employee' || Auth::user()->role === 'admin'))

                    @php $isGuest = Auth::user()->role === 'guest'; @endphp
                    <li>
                        @if(!$isGuest)
                        <a href="{{ route('profile.edit') }}" style="
                        display:block; 
                        padding: 15px 15px;
                        font-family: 'Poppins', sans-serif; 
                        text-decoration:none; 
                        font-size: 16px;
font-weight: 400;

                        color:#333;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                            Edit Profile
                        </a>
                        @else
                        <a href="javascript:void(0)" onclick="alert('Login terlebih dahulu untuk mengakses profil!')" style="display:block; padding: 10px 15px; text-decoration:none; color:#999;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                            Edit Profile
                        </a>
                        @endif
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="width: 100%;
                        font-family: 'Poppins', sans-serif; font-size: 16px;
font-weight: 400;

                            text-align: left; padding: 15px 15px; background: none; border: none; color: #333; cursor: pointer;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                                Logout
                            </button>
                        </form>
                    </li>

                    @endif
                    @if(Auth::user() && (Auth::user()->role === 'guest'))
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="width: 100%;
                        font-family: 'Poppins', sans-serif;font-size: 16px;
font-weight: 400;

                            text-align: left; padding: 15px 15px; background: none; border: none; color: #333; cursor: pointer;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                                Login
                            </button>
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

    </div>

</div>

<nav class="sidebar">
    <div class="sidebar-header">
        <img src="/images/Philips.png" alt="Logo" class="logo-image" />
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="/dashboard" class="{{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a>
        </li>
        <li>
            <a href="/list" class="{{ Request::is('list*') ? 'active' : '' }}">Room List</a>

        </li>


        @if(Auth::user() && (Auth::user()->role === 'employee' || Auth::user()->role === 'admin'))
        <li>
            <a href="/apply" class="{{ Request::is('apply') ? 'active' : '' }}">Apply Room</a>
        </li>
        @endif
        @if(Auth::user()->role === 'admin')
        <li>
            <a href="{{ route('rooms.index') }}" class="{{ Request::is('rooms*') ? 'active' : '' }}">Manage Rooms</a>
        </li>
        @endif
        @if(Auth::user()->role === 'admin')
        <li>
            <a href="/bookings" class="{{ Request::is('bookings') ? 'active' : '' }}">Apply Status</a>
        </li>
        @endif
        @if(Auth::user()->role === 'admin')
        <li>
            <a href="/users" class="{{ Request::is('users') ? 'active' : '' }}">Data User</a>
        </li>
        @endif

        @if(Auth::user() && (Auth::user()->role === 'employee' || Auth::user()->role === 'admin'))
        <li>
            <a href="/report" class="{{ Request::is('report') ? 'active' : '' }}">Reports</a>
        </li>
        <li>
            <a href="/borrow-status" class="{{ Request::is('borrow-status') ? 'active' : '' }}">My Borrow</a>
        </li>
        @endif
        @if(Auth::user() && (Auth::user()->role === 'employee' || Auth::user()->role === 'admin'))
        <li>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </li>
        @endif
        @if(Auth::user()->role === 'guest')
        <li>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Log in</button>
            </form>
        </li>
        @endif
    </ul>
</nav>
<script>
    document.getElementById('profile-btn').addEventListener('click', function() {
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Click outside to close
    document.addEventListener('click', function(e) {
        const profileWrapper = document.getElementById('profile-wrapper');
        if (!profileWrapper.contains(e.target)) {
            document.getElementById('profile-dropdown').style.display = 'none';
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifBtn = document.getElementById('notification-btn');
        const notifDropdown = document.getElementById('notification-dropdown');
        const notifCount = document.getElementById('notification-count');
        const notifList = document.getElementById('notification-list');

        // Fetch notifikasi
        async function fetchNotifications() {
            try {
                const response = await fetch('{{ route("notifications.get") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) throw new Error('Gagal ambil notifikasi');

                const data = await response.json();

                // Tampilkan jumlah unread
                notifCount.style.display = data.unread_count > 0 ? 'inline-block' : 'none';
                notifCount.textContent = data.unread_count;

                notifList.innerHTML = '';

                if (data.notifications.length === 0) {
                    notifList.innerHTML = '<li style="padding:10px;text-align:center;">No notification</li>';
                } else {
                    data.notifications.forEach(notif => {
                        const li = document.createElement('li');
                        li.dataset.id = notif.id;
                        li.style.padding = '8px';
                        li.style.borderBottom = '1px solid #eee';
                        li.style.position = 'relative';

                        if (!notif.read_at) {
                            li.style.backgroundColor = '#eef6ff';
                            li.style.fontWeight = 'bold';
                        }

                        let title = 'System';
                        let message = notif.message || '';



                        li.innerHTML = `
                            <div><strong>${title}</strong> - <small style="color:#999;">${notif.created_at}</small></div>
                            <div>${message}</div>
                            <button class="delete-btn" style="position:absolute;right:10px;top:10px;background:red;color:#fff;border:none;padding:2px 6px;cursor:pointer;">x</button>
                        `;

                        notifList.appendChild(li);

                        // Tombol delete
                        li.querySelector('.delete-btn').addEventListener('click', async () => {
                            await deleteNotification(notif.id);
                            li.remove();
                        });

                        // Swipe gesture untuk mobile
                        let touchStartX = 0;
                        li.addEventListener('touchstart', e => touchStartX = e.changedTouches[0].screenX);
                        li.addEventListener('touchend', async e => {
                            const touchEndX = e.changedTouches[0].screenX;
                            if (touchStartX - touchEndX > 50) {
                                await deleteNotification(notif.id);
                                li.remove();
                            }
                        });
                    });
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Hapus notifikasi
        async function deleteNotification(id) {
            try {
                await fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
            } catch (e) {
                console.error('Gagal hapus notifikasi', e);
            }
        }

        // Tandai notif sebagai dibaca
        async function markNotificationsAsRead() {
            try {
                await fetch('{{ route("notifications.markAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                notifCount.style.display = 'none';
            } catch (e) {
                console.error('Gagal tandai dibaca', e);
            }
        }

        // Saat tombol notifikasi diklik
        notifBtn.addEventListener('click', async () => {
            const isOpen = notifDropdown.style.display === 'block';
            notifDropdown.style.display = isOpen ? 'none' : 'block';

            if (!isOpen) {
                await fetchNotifications();
                await markNotificationsAsRead(); // hapus badge
            }
        });

        // Klik luar => tutup
        document.addEventListener('click', e => {
            if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.style.display = 'none';
            }
        });

        // Auto-fetch di awal
        fetchNotifications();
    });
</script>
<script>
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const body = document.body;

    hamburgerBtn.addEventListener('click', () => {
        body.classList.toggle('sidebar-open');
    });
</script>