<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>{{ $title }}</title>
  
  {{-- Google Fonts: Plus Jakarta Sans --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link href="/css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  @livewireStyles

  <style>
    /* Global Typography Reset */
    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: #F8FAFC;
        color: #334155;
    }

    #layoutSidenav_content {
        min-width: 0;
        overflow-x: hidden;
        background-color: #F8FAFC;
    }
    
    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    /* Modern Navbar Styling */
    .sb-topnav {
        background: #0F172A !important; /* Deep Navy */
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        height: 64px;
    }

    .navbar-brand.ps-3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 18px; 
        font-weight: 700; 
        color: #FFFFFF !important; 
        letter-spacing: 0.5px;
        text-decoration: none;
    }

    /* Logika Ikon Laptop: Sembunyi saat Sidebar terbuka, Tampil HANYA saat Sidebar ditutup */
    .brand-laptop-icon {
        display: none !important;
    }
    body.sb-sidenav-toggled .brand-laptop-icon {
        display: inline-block !important;
    }

    /* Sidebar Styling */
    .sb-sidenav-dark {
        background-color: #1E293B !important; /* Soft Slate Navy */
        border-right: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sb-sidenav-dark .sb-sidenav-menu .sb-sidenav-menu-heading {
        color: #64748B;
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 1.5rem 1rem 0.5rem;
    }

    .sb-sidenav-dark .sb-sidenav-menu .nav-link {
        color: #94A3B8;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin: 2px 12px;
        transition: all 0.2s ease-in-out;
    }

    .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
        color: #FFFFFF;
        background-color: rgba(255, 255, 255, 0.06);
    }

    .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
        color: #FFFFFF !important;
        background-color: #2563EB !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .sb-sidenav-dark .sb-sidenav-menu .nav-link .sb-nav-link-icon {
        color: #64748B;
        transition: all 0.2s ease-in-out;
    }

    .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover .sb-nav-link-icon,
    .sb-sidenav-dark .sb-sidenav-menu .nav-link.active .sb-nav-link-icon {
        color: #FFFFFF !important;
    }

    .sb-sidenav-footer {
        background-color: #0F172A !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem;
    }

    /* Footer Styling */
    #layoutError_footer {
        width: 100%;
        margin-top: auto;
    }

    .bgfooter {
        background-color: #0F172A; 
        color: #94A3B8;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        font-size: 0.875rem;
    }

    .bgfooter a {
        color: #38BDF8; 
        transition: color 0.2s ease;
    }

    .bgfooter a:hover {
        color: #7DD3FC;
    }

    #sidebarToggle {
        transition: all 0.25s ease-in-out;
    }

    .dropdown-menu-end {
        right: 0;
        left: auto;
    }

    .animated--grow-in {
        animation-name: growIn;
        animation-duration: 0.15s;
        animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes growIn {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>

<body class="sb-nav-fixed">

  {{-- ========================================== --}}
  {{-- 1. NAVBAR COMPONENT                        --}}
  {{-- ========================================== --}}
  <nav class="sb-topnav navbar navbar-expand navbar-dark">
    
    <!-- Sidebar Toggle (Garis Tiga Normal) -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-3 me-lg-0 text-slate-300 fs-5 ps-3" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Brand dengan Ikon Laptop Khusus Ditutup -->
    <a class="navbar-brand ps-2 d-flex align-items-center" href="{{ route('Dashboard') }}">
        <span>SISTEM PEMILIHAN LAPTOP</span>
    </a>
    
    <!-- Navbar Right Dropdowns-->
    <ul class="navbar-nav ms-auto me-0 me-md-3 my-2 my-md-0 d-flex align-items-center">
      
      <!-- Notifications Dropdown -->
      <li class="nav-item dropdown me-2">
        <a class="nav-link position-relative p-2 text-slate-300" 
           href="#" 
           id="navbarDropdownAlerts" 
           role="button" 
           data-bs-toggle="dropdown" 
           aria-expanded="false">
          <i class="bi bi-bell-fill fs-5"></i>
          @if(isset($jumlahSaranMasuk) && $jumlahSaranMasuk > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 3px 6px; margin-top: 6px; margin-left: -4px;">
              {{ $jumlahSaranMasuk }}
            </span>
          @endif
        </a>
        
        <div class="dropdown-menu dropdown-menu-end shadow-lg animated--grow-in py-0 border-0" 
             aria-labelledby="navbarDropdownAlerts" 
             style="width: 320px; max-height: 380px; overflow-y: auto; border-radius: 0.75rem; margin-top: 12px;">
            
            <div class="dropdown-header bg-primary text-white fw-bold py-3 rounded-top d-flex align-items-center">
                <i class="bi bi-envelope-open-text-fill me-2 fs-6"></i> Umpan Balik & Pesan Masuk
            </div>
            
            <div class="list-group list-group-flush">
                @if(isset($saranMasukDetail) && $saranMasukDetail->count() > 0)
                    @foreach($saranMasukDetail as $saran)
                        <a href="{{ route('saran.baca', $saran->id) }}" class="list-group-item list-group-item-action d-flex align-items-start py-3">
                            <div class="me-3">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-chat-left-text-fill small"></i>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small fw-bold text-dark">{{ $saran->nama_pengirim ?? 'Anonim' }}</span>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $saran->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-secondary mb-0 small text-truncate" style="max-width: 200px; font-size: 0.8rem;">
                                    "{{ $saran->isi_saran }}"
                                </p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-mailbox d-block fs-3 mb-2 text-slate-400"></i>
                        Tidak ada saran baru
                    </div>
                @endif
            </div>

            <a class="dropdown-item text-center small text-primary fw-bold py-2.5 border-top rounded-bottom bg-light" href="{{ route('saran.index') }}">
                Lihat Semua Umpan Balik
            </a>
        </div>
      </li>
      
      <!-- User Account Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center text-slate-300" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="bg-slate-700 text-white rounded-circle d-flex align-items-center justify-content-center me-1" style="width: 32px; height: 32px; font-weight: 600; font-size: 0.85rem;">
             {{ substr(auth()->user()->name, 0, 1) }}
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3" aria-labelledby="navbarDropdown">
          <li class="px-3 py-2 border-bottom">
            <span class="d-block text-muted small">Signed in as</span>
            <span class="fw-bold text-dark">{{ auth()->user()->name }}</span>
          </li>
          <form action="{{ route('logout') }}" method="post">
            @csrf
            <li>
                <button type="submit" class="dropdown-item text-danger d-flex align-items-center py-2 mt-1">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </li>
          </form>
        </ul>
      </li>
    </ul>
  </nav>

  <div id="layoutSidenav">
    {{-- ========================================== --}}
    {{-- 2. SIDEBAR COMPONENT                       --}}
    {{-- ========================================== --}}
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Menu Utama</div>
                    <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-grid-fill"></i></div>
                        Dashboard
                    </a>

                    <div class="sb-sidenav-menu-heading">Data Master</div>
                    <a class="nav-link {{ Request::is('dashboard/universitas*') ? 'active' : '' }}" href="{{ route('universitas.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-building"></i></div>
                        Data Akademik
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data-kriteria*') ? 'active' : '' }}" href="{{ route('data-kriteria.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-sliders"></i></div>
                        Kriteria
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data-sub-kriteria*') ? 'active' : '' }}" href="{{ route('data-sub-kriteria.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-list-nested"></i></div>
                        Sub Kriteria
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data-alternatif*') ? 'active' : '' }}" href="{{ route('data-alternatif.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-laptop-fill"></i></div>
                        Data Laptop (Alternatif)
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data-penilaian*') ? 'active' : '' }}" href="{{ route('data-penilaian.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-clipboard2-check-fill"></i></div>
                        Penilaian Matriks
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/data-hasil-akhir*') ? 'active' : '' }}" href="{{ route('data-hasil-akhir.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-trophy-fill"></i></div>
                        Hasil Perankingan
                    </a>

                    <div class="sb-sidenav-menu-heading">Umpan Balik</div>
                    <a class="nav-link {{ Request::is('dashboard/saran*') ? 'active' : '' }}" href="{{ route('saran.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-chat-left-dots-fill"></i></div>
                        Saran Masuk
                    </a>
                    <a class="nav-link {{ Request::is('dashboard/biodata*') ? 'active' : '' }}" href="{{ route('biodata.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-people-fill"></i></div>
                        Data Mahasiswa
                    </a>

                    @can('admin')
                    <div class="sb-sidenav-menu-heading">Pengaturan Sistem</div>
                    <a class="nav-link {{ Request::is('dashboard/data-user*') ? 'active' : '' }}" href="{{ route('data-user.index') }}">
                        <div class="sb-nav-link-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        Kelola Pengguna
                    </a>
                    @endcan
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small text-slate-400">Logged in as:</div>
                <span class="text-white fw-semibold">{{ auth()->user()->name }}</span>
            </div>
        </nav>
    </div>

    {{-- ========================================== --}}
    {{-- 3. KONTEN UTAMA & FOOTER                   --}}
    {{-- ========================================== --}}
    <div id="layoutSidenav_content">
      <main class="py-4">
          @yield('container')
      </main>

      <div id="layoutError_footer">
          <footer class="py-3 bgfooter">
              <div class="container-fluid px-4">
                  <div class="row align-items-center">
                      <div class="col-md-6 text-center text-md-start">
                          <p class="mb-0 text-slate-400">© 2026 SPK Rekomendasi Laptop - Mella. All Rights Reserved.</p>
                      </div>
                      <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                          <p class="mb-0">
                              <span class="text-slate-400">Bantuan Admin:</span> 
                              <a href="https://wa.me/6285832002991" target="_blank" class="text-decoration-none ms-2">
                                  <i class="bi bi-whatsapp me-1"></i> WhatsApp
                              </a> 
                              <span class="text-slate-600 mx-2">|</span> 
                              <a href="mailto:mellaputry04@gmail.com" class="text-decoration-none">
                                  <i class="bi bi-envelope-fill me-1"></i> Email
                              </a>
                          </p>
                      </div>
                  </div>
              </div>
          </footer>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
  </script>
  <script src="/js/scripts.js"></script>
  <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
    $(document).ready(function () {
        if ($('#example').length) {
            $('#example').DataTable({
                "scrollX": true, 
                "autoWidth": false
            });
        }
    });
  </script>
  
  @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
  @livewireScripts
  @stack('table')
  @stack('slug')
  @stack('delete')
  @stack('reset')
  @stack('deleteUser')
</body>

</html>