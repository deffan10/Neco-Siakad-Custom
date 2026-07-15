<!-- UTAMA & PORTAL -->
<li class="nav-item">
    <div class="nav-link text-muted fw-bold text-uppercase py-1" style="font-size: 10px; letter-spacing: 0.5px; pointer-events: none;">
        UTAMA & PORTAL
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'show' : '' }}" href="#navbar-referensi" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" role="button" aria-expanded="{{ request()->routeIs($activeRole . '.referensi*') ? 'true' : 'false' }}">
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
    <div class="dropdown-menu {{ request()->routeIs('dashboard.*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs($activeRole.'.dashboard-infra') ? 'active' : '' }}" href="{{ route($activeRole.'.dashboard-infra') }}">Dashboard Infrastruktur</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole.'.dashboard-referensi') ? 'active' : '' }}" href="{{ route($activeRole.'.dashboard-referensi') }}">Dashboard Referensi</a>
            </div>
        </div>
    </div>
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

<!-- MASTER & INSTITUSI -->
<li class="nav-item mt-2">
    <div class="nav-link text-muted fw-bold text-uppercase py-1" style="font-size: 10px; letter-spacing: 0.5px; pointer-events: none;">
        MASTER & INSTITUSI
    </div>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.kampus-setting.index') ? 'active' : '' }}" href="{{ route('admin.kampus-setting.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" /></svg>
        </span>
        <span class="nav-link-title">Badan Hukum PT</span>
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs($activeRole . '.referensi*') ? 'show' : '' }}" href="#navbar-referensi" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" role="button" aria-expanded="{{ request()->routeIs($activeRole . '.referensi*') ? 'true' : 'false' }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-replace-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 11v-3c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-6m0 0l3 3m-3 -3l3 -3" /><path d="M3 13.013v3c0 .53 .211 1.039 .586 1.414c.375 .375 .884 .586 1.414 .586h6m0 0l-3 -3m3 3l-3 3" /><path d="M16 16.502c0 .53 .211 1.039 .586 1.414c.375 .375 .884 .586 1.414 .586c.53 0 1.039 -.211 1.414 -.586c.375 -.375 .586 -.884 .586 -1.414c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414z" /><path d="M4 4.502c0 .53 .211 1.039 .586 1.414c.375 .375 .884 .586 1.414 .586c.53 0 1.039 -.211 1.414 -.586c.375 -.375 .586 -.884 .586 -1.414c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414z" /><path d="M21 21.499c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414" /><path d="M9 9.499c0 -.53 -.211 -1.039 -.586 -1.414c-.375 -.375 -.884 -.586 -1.414 -.586h-2c-.53 0 -1.039 .211 -1.414 .586c-.375 .375 -.586 .884 -.586 1.414" /></svg>
        </span>
        <span class="nav-link-title">Data Pengguna</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs($activeRole . '.referensi*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.users.user*') ? 'active' : '' }}" href="{{ route($activeRole . '.users.user-index') }}">User</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.users.role*') ? 'active' : '' }}" href="{{ route($activeRole . '.users.role-index') }}">Role</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.users.subrole*') ? 'active' : '' }}" href="#">Subrole ( Soon )</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.users.alamat*') ? 'active' : '' }}" href="{{ route($activeRole . '.users.alamat-index') }}">Alamat</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.users.keluarga*') ? 'active' : '' }}" href="{{ route($activeRole . '.users.keluarga-index') }}">Keluarga</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.users.pendidikan*') ? 'active' : '' }}" href="{{ route($activeRole . '.users.pendidikan-index') }}">Pendidikan</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pegawai.index') ? 'active' : '' }}" href="{{ route('admin.pegawai.index') }}">Data Pegawai</a>
                <a class="dropdown-item {{ request()->routeIs('admin.dosen.index') ? 'active' : '' }}" href="{{ route('admin.dosen.index') }}">Data Dosen</a>
            </div>
        </div>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs($activeRole . '.referensi*') ? 'show' : '' }}" href="#navbar-referensi" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" role="button" aria-expanded="{{ request()->routeIs($activeRole . '.referensi*') ? 'true' : 'false' }}">
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
        <span class="nav-link-title">Data Referensi</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs($activeRole . '.referensi*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.agama*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.agama-index') }}">Agama</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.golongan-darah*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.golongan-darah-index') }}">Golongan Darah</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.jenis-kelamin*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.jenis-kelamin-index') }}">Jenis Kelamin</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.kewarganegaraan*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.kewarganegaraan-index') }}">Kewarganegaraan</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.semester*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.semester-index') }}">Semester</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.status-mahasiswa*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.status-mahasiswa-index') }}">Status Mahasiswa</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.referensi.jabatan*') ? 'active' : '' }}" href="{{ route($activeRole . '.referensi.jabatan-index') }}">Jabatan</a>
            </div>
        </div>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs($activeRole . '.infra*', $activeRole . '.inventaris*', $activeRole . '.transaksi-barang*', $activeRole . '.perawatan*') ? 'show' : '' }}" href="#navbar-infra" data-bs-toggle="dropdown" data-bs-auto-close="outside"
        role="button" aria-expanded="{{ request()->routeIs($activeRole . '.infra*', $activeRole . '.inventaris*', $activeRole . '.transaksi-barang*', $activeRole . '.perawatan*') ? 'true' : 'false' }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-1">
                <path d="M3 12h1l3 8l3 -16l3 16l3 -8h4" />
                <path d="M4 4l1 1" />
                <path d="M18 5l1 -1" />
                <path d="M17 10l1 1" />
                <path d="M13 10l1 1" />
                <path d="M9 10l1 1" />
                <path d="M5 10l1 1" />
            </svg>
        </span>
        <span class="nav-link-title">Master Infrastruktur</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs($activeRole . '.infra*', $activeRole . '.inventaris*', $activeRole . '.transaksi-barang*', $activeRole . '.perawatan*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.infra.gedung*') ? 'active' : '' }}" href="{{ route($activeRole . '.infra.gedung-index') }}">Data Gedung</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole . '.infra.ruangan*') ? 'active' : '' }}" href="{{ route($activeRole . '.infra.ruangan-index') }}">Data Ruangan</a>
                <div class="dropend">
                    <a class="dropdown-item dropdown-toggle {{ request()->routeIs($activeRole . '.inventaris*') ? 'show' : '' }}" href="#sidebar-inventaris"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button"
                        aria-expanded="{{ request()->routeIs($activeRole . '.inventaris*') ? 'true' : 'false' }}">
                        Data Inventaris
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs($activeRole . '.inventaris*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.inventaris.kategori-barang*') ? 'active' : '' }}" href="{{ route($activeRole . '.inventaris.kategori-barang-index') }}">Kategori Barang</a>
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.inventaris.barang*') ? 'active' : '' }}" href="{{ route($activeRole . '.inventaris.barang-index') }}">Barang</a>
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.inventaris.barang-inventaris*') ? 'active' : '' }}" href="{{ route($activeRole . '.inventaris.barang-inventaris-index') }}">Barang Inventaris</a>
                    </div>
                </div>
                <div class="dropend">
                    <a class="dropdown-item dropdown-toggle {{ request()->routeIs($activeRole . '.transaksi-barang*') ? 'show' : '' }}" href="#sidebar-transaksi-barang"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button"
                        aria-expanded="{{ request()->routeIs($activeRole . '.transaksi-barang*') ? 'true' : 'false' }}">
                        Data Transaksi Barang
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs($activeRole . '.transaksi-barang*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.transaksi-barang.peminjaman*') ? 'active' : '' }}" href="{{ route($activeRole . '.transaksi-barang.peminjaman-index') }}">Peminjaman</a>
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.transaksi-barang.pengecekan*') ? 'active' : '' }}" href="{{ route($activeRole . '.transaksi-barang.pengecekan-index') }}">Pengecekan</a>
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.transaksi-barang.pengajuan*') ? 'active' : '' }}" href="{{ route($activeRole . '.transaksi-barang.pengajuan-index') }}">Pengajuan Perbaikan</a>
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.transaksi-barang.riwayat*') ? 'active' : '' }}" href="{{ route($activeRole . '.transaksi-barang.riwayat-index') }}">Riwayat Perbaikan</a>
                    </div>
                </div>
                <div class="dropend">
                    <a class="dropdown-item dropdown-toggle {{ request()->routeIs($activeRole . '.perawatan*') ? 'show' : '' }}" href="#sidebar-perawatan"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button"
                        aria-expanded="{{ request()->routeIs($activeRole . '.perawatan*') ? 'true' : 'false' }}">
                        Data Perawatan
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs($activeRole . '.perawatan*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.perawatan.jadwal*') ? 'active' : '' }}" href="{{ route($activeRole . '.perawatan.jadwal-index') }}">Jadwal Pemeliharaan</a>
                        <a class="dropdown-item {{ request()->routeIs($activeRole . '.perawatan.histori*') ? 'active' : '' }}" href="{{ route($activeRole . '.perawatan.histori-index') }}">Histori Pemeliharaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('akademik*') ? 'show' : '' }}" href="#navbar-akademik" data-bs-toggle="dropdown" data-bs-auto-close="outside"
        role="button" aria-expanded="{{ request()->routeIs('akademik*') ? 'true' : 'false' }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-1">
                <path d="M3 12h1l3 8l3 -16l3 16l3 -8h4" />
                <path d="M4 4l1 1" />
                <path d="M18 5l1 -1" />
                <path d="M17 10l1 1" />
                <path d="M13 10l1 1" />
                <path d="M9 10l1 1" />
                <path d="M5 10l1 1" />
            </svg>
        </span>
        <span class="nav-link-title">Master Akademik</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('akademik*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.tahun-akademik*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.tahun-akademik-index') }}">Tahun Akademik</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.fakultas*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.fakultas-index') }}">Fakultas</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.program-studi*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.program-studi-index') }}">Program Studi</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.kurikulum-*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.kurikulum-index') }}">Kurikulum</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.matakuliah-*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.matakuliah-index') }}">Mata Kuliah</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.kelas-perkuliahan*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.kelas-perkuliahan-index') }}">Kelas Perkuliahan</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.jadwal-perkuliahan*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.jadwal-perkuliahan-index') }}">Jadwal Perkuliahan</a>
                <a class="dropdown-item {{ request()->routeIs($activeRole. '.akademik.kelas-mahasiswa*') ? 'active' : '' }}" href="{{ route($activeRole. '.akademik.kelas-mahasiswa-index') }}">Kelas Mahasiswa</a>
                <a class="dropdown-item {{ request()->routeIs('admin.asisten-lab.index') ? 'active' : '' }}" href="{{ route('admin.asisten-lab.index') }}">Asisten Lab</a>
                <a class="dropdown-item {{ request()->routeIs('admin.surat-tugas.index') ? 'active' : '' }}" href="{{ route('admin.surat-tugas.index') }}">Surat Tugas Mengajar</a>
            </div>
        </div>
    </div>
</li>

<!-- AKADEMIK & KRS -->
<li class="nav-item mt-2">
    <div class="nav-link text-muted fw-bold text-uppercase py-1" style="font-size: 10px; letter-spacing: 0.5px; pointer-events: none;">
        AKADEMIK & KRS
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.krs*', 'admin.nilai*') ? 'show' : '' }}" href="#navbar-akademik-krs" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h11a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-11a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1z" /><path d="M3 19h16" /><path d="M9 8h6" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
        </span>
        <span class="nav-link-title">KRS & Nilai</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('admin.krs*', 'admin.nilai*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs('admin.krs.settings') ? 'active' : '' }}" href="{{ route('admin.krs.settings') }}">Jadwal & Aturan KRS</a>
                <a class="dropdown-item {{ request()->routeIs('admin.krs.perwalian') ? 'active' : '' }}" href="{{ route('admin.krs.perwalian') }}">Persetujuan KRS</a>
                <a class="dropdown-item {{ request()->routeIs('admin.nilai.kelas-index') ? 'active' : '' }}" href="{{ route('admin.nilai.kelas-index') }}">Input Nilai Kuliah</a>
                <a class="dropdown-item {{ request()->routeIs('admin.nilai.proses-ipk') ? 'active' : '' }}" href="{{ route('admin.nilai.proses-ipk') }}">Proses IPS/IPK</a>
                <a class="dropdown-item {{ request()->routeIs('admin.bimbingan-pa.index') ? 'active' : '' }}" href="{{ route('admin.bimbingan-pa.index') }}">Laporan Bimbingan PA</a>
                <a class="dropdown-item {{ request()->routeIs('admin.sesi-kuliah*') ? 'active' : '' }}" href="{{ route('admin.sesi-kuliah.kuliah') }}">Sesi Perkuliahan</a>
            </div>
        </div>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.lock-jurnal*') ? 'active' : '' }}" href="{{ route('admin.lock-jurnal.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M12 9a3 3 0 0 0 3 -3v-3h-6v3a3 3 0 0 0 3 3" /></svg>
        </span>
        <span class="nav-link-title">Lock Jurnal Dosen</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.cuti*') ? 'active' : '' }}" href="{{ route('admin.cuti.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 8v-3a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5" /><path d="M6 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M3 21v-1a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1" /></svg>
        </span>
        <span class="nav-link-title">Persetujuan Cuti</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.tugas-akhir*') ? 'active' : '' }}" href="{{ route('admin.tugas-akhir.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 4v16H5V4h14m0-2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" /><path d="M12 11h5v2h-5z" /><path d="M12 7h5v2h-5z" /><path d="M7 7h3v3H7z" /><path d="M7 12h3v3H7z" /></svg>
        </span>
        <span class="nav-link-title">Persetujuan Tugas Akhir</span>
    </a>
</li>

<!-- KEMAHASISWAAN & KELULUSAN -->
<li class="nav-item mt-2">
    <div class="nav-link text-muted fw-bold text-uppercase py-1" style="font-size: 10px; letter-spacing: 0.5px; pointer-events: none;">
        KEMAHASISWAAN & KELULUSAN
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.wisuda*', 'admin.skl*') ? 'show' : '' }}" href="#navbar-wisuda" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
        </span>
        <span class="nav-link-title">Wisuda & Kelulusan</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('admin.wisuda*', 'admin.skl*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs('admin.wisuda.settings') ? 'active' : '' }}" href="{{ route('admin.wisuda.settings') }}">Kegiatan Wisuda</a>
                <a class="dropdown-item {{ request()->routeIs('admin.skl.*') ? 'active' : '' }}" href="{{ route('admin.skl.index') }}">Cetak SKL</a>
                <a class="dropdown-item {{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}" href="{{ route('admin.alumni.index') }}">Data Alumni</a>
                <a class="dropdown-item {{ request()->routeIs('admin.aktivitas-mahasiswa.index') ? 'active' : '' }}" href="{{ route('admin.aktivitas-mahasiswa.index') }}">Aktivitas Lulus/Keluar</a>
            </div>
        </div>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.seminar*') ? 'active' : '' }}" href="{{ route('admin.seminar.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-presentation" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4l18 0" /><path d="M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-10" /><path d="M12 16l0 4" /><path d="M9 20l6 0" /><path d="M8 12l3 -3l2 2l3 -3" /></svg>
        </span>
        <span class="nav-link-title">Event Seminar</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.sertifikasi*') ? 'active' : '' }}" href="{{ route('admin.sertifikasi.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-certificate" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v3.5" /></svg>
        </span>
        <span class="nav-link-title">Sertifikasi (SKPI)</span>
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.beasiswa*') ? 'show' : '' }}" href="#navbar-beasiswa" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9m-6 0a6 6 0 1 0 12 0a6 6 0 1 0 -12 0" /><path d="M12 3v3" /><path d="M12 12v9" /><path d="M7 14h10" /></svg>
        </span>
        <span class="nav-link-title">Beasiswa</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('admin.beasiswa*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs('admin.beasiswa.jenis') ? 'active' : '' }}" href="{{ route('admin.beasiswa.jenis') }}">Jenis Beasiswa</a>
                <a class="dropdown-item {{ request()->routeIs('admin.beasiswa.data') ? 'active' : '' }}" href="{{ route('admin.beasiswa.data') }}">Penerima Beasiswa</a>
                <a class="dropdown-item {{ request()->routeIs('admin.beasiswa.salin') ? 'active' : '' }}" href="{{ route('admin.beasiswa.salin') }}">Salin Beasiswa</a>
            </div>
        </div>
    </div>
</li>


<!-- KEUANGAN & PMB -->
<li class="nav-item mt-2">
    <div class="nav-link text-muted fw-bold text-uppercase py-1" style="font-size: 10px; letter-spacing: 0.5px; pointer-events: none;">
        KEUANGAN & PMB
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.keuangan*') ? 'show' : '' }}" href="#navbar-keuangan" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M3 6v10c0 1.657 2.686 3 6 3" /><path d="M3 11c0 1.657 2.686 3 6 3" /></svg>
        </span>
        <span class="nav-link-title">Keuangan</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('admin.keuangan*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs('admin.keuangan.tarif-index') ? 'active' : '' }}" href="{{ route('admin.keuangan.tarif-index') }}">Pengaturan Tarif</a>
                <a class="dropdown-item {{ request()->routeIs('admin.keuangan.tagihan-index') ? 'active' : '' }}" href="{{ route('admin.keuangan.tagihan-index') }}">Tagihan Mahasiswa</a>
                <a class="dropdown-item {{ request()->routeIs('admin.keuangan.pembayaran-index') ? 'active' : '' }}" href="{{ route('admin.keuangan.pembayaran-index') }}">Verifikasi Pembayaran</a>
                <a class="dropdown-item {{ request()->routeIs('admin.dispensasi.index') ? 'active' : '' }}" href="{{ route('admin.dispensasi.index') }}">Dispensasi Keuangan</a>
            </div>
        </div>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.pmb*') ? 'show' : '' }}" href="#navbar-pmb" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
        </span>
        <span class="nav-link-title">Penerimaan PMB</span>
    </a>
    <div class="dropdown-menu {{ request()->routeIs('admin.pmb*') ? 'show' : '' }}">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.dashboard') ? 'active' : '' }}" href="{{ route('admin.pmb.dashboard') }}">Dashboard PMB</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.landing') ? 'active' : '' }}" href="{{ route('admin.pmb.landing') }}">Halaman Utama</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.settings') ? 'active' : '' }}" href="{{ route('admin.pmb.settings') }}">Aturan & Pengaturan</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.tuition') ? 'active' : '' }}" href="{{ route('admin.pmb.tuition') }}">Biaya & Pembayaran</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.admission') ? 'active' : '' }}" href="{{ route('admin.pmb.admission') }}">Ujian & Seleksi</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.promo') ? 'active' : '' }}" href="{{ route('admin.pmb.promo') }}">Promosi Blast</a>
                <a class="dropdown-item {{ request()->routeIs('admin.pmb.affiliate') ? 'active' : '' }}" href="{{ route('admin.pmb.affiliate') }}">Program Afiliasi</a>
            </div>
        </div>
    </div>
</li>

<!-- KOMUNIKASI & CONFIG -->
<li class="nav-item mt-2">
    <div class="nav-link text-muted fw-bold text-uppercase py-1" style="font-size: 10px; letter-spacing: 0.5px; pointer-events: none;">
        KOMUNIKASI & CONFIG
    </div>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.broadcast*') ? 'active' : '' }}" href="{{ route('admin.broadcast.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail-forward" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" /><path d="M3 6l9 6l9 -6" /><path d="M15 18h6" /><path d="M18 15l3 3l-3 3" /></svg>
        </span>
        <span class="nav-link-title">Broadcast Email</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.kuesioner*') ? 'active' : '' }}" href="{{ route('admin.kuesioner.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-forms" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a3 3 0 0 0 -3 3v12a3 3 0 0 0 3 3" /><path d="M6 3a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3" /><path d="M13 7h7" /><path d="M13 11h7" /><path d="M13 15h7" /></svg>
        </span>
        <span class="nav-link-title">Kuesioner & Tracer</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.shared-files.index') ? 'active' : '' }}" href="{{ route('admin.shared-files.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M12 20h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l2 2" /><path d="M14 21h6" /><path d="M17 18l3 3l-3 3" /></svg>
        </span>
        <span class="nav-link-title">Tukar File</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.borang.index') ? 'active' : '' }}" href="{{ route('admin.borang.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l-8 4.5l-8 -4.5z" /><path d="M12 12l8 4.5l-8 4.5l-8 -4.5z" /><path d="M12 21l8 -4.5l-8 -4.5l-8 4.5z" /></svg>
        </span>
        <span class="nav-link-title">Borang Akreditasi</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.export*') ? 'active' : '' }}" href="{{ route('admin.export.index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 17v-6" /><path d="M9.5 14.5l2.5 2.5l2.5 -2.5" /></svg>
        </span>
        <span class="nav-link-title">Export & Laporan</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs($activeRole . '.pengaturan-index') ? 'active' : '' }}" href="{{ route($activeRole . '.pengaturan-index') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-1">
                <path
                    d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
            </svg>
        </span>
        <span class="nav-link-title">Pengaturan</span>
    </a>
</li>