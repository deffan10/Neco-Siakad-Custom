
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
    <a class="nav-link {{ request()->routeIs('dosen.krs.perwalian*') ? 'active' : '' }}" href="{{ route('dosen.krs.perwalian') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1z" /><path d="M3 19h16" /><path d="M9 8h6" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
        </span>
        <span class="nav-link-title">Bimbingan & Perwalian</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('dosen.nilai*') ? 'active' : '' }}" href="{{ route('dosen.nilai.kelas-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15h3.5a5 5 0 1 0 0 -10h-11.5v6" /><path d="M9 9l-3 3l3 3" /></svg>
        </span>
        <span class="nav-link-title">Input Nilai Kuliah</span>
    </a>
</li>