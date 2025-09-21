<ul class="navbar-nav">
    @if($activeRole === 'admin')
        @include('themes.partials.sidebar-admin')
    @elseif($activeRole === 'dosen')
        @include('themes.partials.sidebar-dosen')
    @elseif($activeRole === 'tendik')
        @include('themes.partials.sidebar-tendik')
    @elseif($activeRole === 'mahasiswa')
        @include('themes.partials.sidebar-mahasiswa')
    @elseif($activeRole === 'peserta-pmb')
        @include('themes.partials.sidebar-peserta-pmb')
    @elseif($activeRole === 'alumni')
        @include('themes.partials.sidebar-alumni')
    @endif

    <li class="nav-item">
        <a class="nav-link" href="{{ route($activeRole.'.auth.handle-logout') }}">
            <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="icon icon-1">
                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                    <path d="M9 12h12l-3 -3" />
                    <path d="M18 15l3 -3" />
                </svg>
            </span>
            <span class="nav-link-title">Logout</span>
        </a>
    </li>
</ul>