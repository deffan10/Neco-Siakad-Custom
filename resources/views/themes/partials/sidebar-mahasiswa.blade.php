
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs($activeRole.'.dashboard-index') ? 'active' : '' }}" href="{{ route($activeRole.'.dashboard-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-1">
                <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                <path d="M3 6l0 13" />
                <path d="M12 6l0 13" />
                <path d="M21 6l0 13" />
            </svg>
        </span>
        <span class="nav-link-title">Dashboard</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs($activeRole.'.profile-index') ? 'active' : '' }}" href="{{ route($activeRole.'.profile-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-1">
                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
            </svg>
        </span>
        <span class="nav-link-title">Profile</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('mahasiswa.keuangan*') ? 'active' : '' }}" href="{{ route('mahasiswa.keuangan.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M3 6v10c0 1.657 2.686 3 6 3" /><path d="M3 11c0 1.657 2.686 3 6 3" /></svg>
        </span>
        <span class="nav-link-title">Keuangan</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('mahasiswa.krs*') ? 'active' : '' }}" href="{{ route('mahasiswa.krs.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1z" /><path d="M3 19h16" /><path d="M9 8h6" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
        </span>
        <span class="nav-link-title">KRS Online</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('mahasiswa.khs*') ? 'active' : '' }}" href="{{ route('mahasiswa.khs.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-report" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M17 13v4h4" /><path d="M12 3v4a1 1 0 0 0 1 1h4" /><path d="M11.5 21h-6.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6" /></svg>
        </span>
        <span class="nav-link-title">KHS</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('mahasiswa.transkrip*') ? 'active' : '' }}" href="{{ route('mahasiswa.transkrip.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M15 11v4h4" /><path d="M10 5v14" /><path d="M4 7h6" /><path d="M4 11h6" /><path d="M4 15h6" /></svg>
        </span>
        <span class="nav-link-title">Transkrip Nilai</span>
    </a>
</li>