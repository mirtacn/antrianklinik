<nav class="sidebar p-3 d-none d-lg-block">
    <div class="text-center mb-4">
        <img src="{{ asset('admin/img/logo.png') }}" alt="Logo Klinik" class="logo" width="70">
        <h6>Klinik Mabarrot Hasyimiyah Manyar Gresik</h6>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="dashboard">
                <i class="bi bi-house"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center
               {{ request()->is('pasienumum*', 'pasienbpjs*', 'datalayanan*', 'datapoli*', 'datadokter*', 'datajadwaldokter*') ? 'active' : '' }}"
               href="#" data-bs-toggle="collapse" data-bs-target="#submenuData" aria-expanded="{{ request()->is('pasienumum*', 'pasienbpjs*', 'datalayanan*', 'datapoli*', 'datadokter*', 'datajadwaldokter*') ? 'true' : 'false' }}">
                <span><i class="bi bi-folder me-2"></i> Master</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <ul class="submenu collapse {{ request()->is('pasienumum*', 'pasienbpjs*', 'datalayanan*', 'datapoli*', 'datadokter*', 'datajadwaldokter*') ? 'show' : '' }}" id="submenuData">
                <li><a class="nav-link {{ request()->is('pasienumum*') ? 'active' : '' }}" href="/pasienumum">➝ Pasien Umum</a></li>
                <li><a class="nav-link {{ request()->is('pasienbpjs*') ? 'active' : '' }}" href="/pasienbpjs">➝ Pasien BPJS</a></li>
                <li><a class="nav-link {{ request()->is('datalayanan*') ? 'active' : '' }}" href="/datalayanan">➝ Data Layanan</a></li>
                <li><a class="nav-link {{ request()->is('datapoli*') ? 'active' : '' }}" href="/datapoli">➝ Data Poli</a></li>
                <li><a class="nav-link {{ request()->is('datadokter*') ? 'active' : '' }}" href="/datadokter">➝ Data Dokter</a></li>
                <li><a class="nav-link {{ request()->is('datajadwaldokter*') ? 'active' : '' }}" href="/datajadwaldokter">➝ Jadwal Dokter</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('panggil*') ? 'active' : '' }}" href="panggil">
                <i class="bi bi-megaphone"></i> Panggil Antrian
            </a>
            <a class="nav-link {{ request()->is('report*') ? 'active' : '' }}" href="report">
                <i class="bi bi-file-earmark-text"></i> Report Antrian
            </a>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>