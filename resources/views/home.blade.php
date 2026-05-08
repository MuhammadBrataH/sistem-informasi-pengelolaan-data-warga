@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Welcome Header -->
        <div class="mb-4">
            <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
            <p class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong> 
                ({{ Auth::user()->isAdminRW() ? 'Admin RW' : 'Admin RT ' . Auth::user()->rt_number }})
            </p>
            <small class="text-muted">
                <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </small>
        </div>

        <!-- Notifikasi Ulang Tahun Hari Ini -->
        @if($wargaUlangTahunHariIni->count() > 0)
            <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert">
                <h5 class="alert-heading">
                    <i class="bi bi-cake2-fill"></i> 🎉 Warga Ulang Tahun Hari Ini!
                </h5>
                <hr>
                <div class="row">
                    @foreach($wargaUlangTahunHariIni as $warga)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="bi bi-person-circle fs-3 text-warning"></i>
                                </div>
                                <div>
                                    <strong>{{ $warga->nama_lengkap }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-house-door"></i> RT {{ $warga->kartuKeluarga->rt }}, 
                                        {{ $warga->kartuKeluarga->kepala_keluarga }}
                                        <br>
                                        <i class="bi bi-gift"></i> Berusia <strong>{{ $warga->umur }} tahun</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <!-- Total Kartu Keluarga -->
            <div class="col-md-3 mb-3">
                <a href="{{ route('kartu-keluarga.index', Auth::user()->isAdminRT() ? ['rt' => Auth::user()->rt_number] : []) }}" class="text-decoration-none" style="cursor: pointer;">
                    <div class="card text-white bg-primary shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-0">Total Kartu Keluarga</h6>
                                    <h2 class="mb-0">{{ $totalKartuKeluarga }}</h2>
                                </div>
                                <div>
                                    <i class="bi bi-people-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small>Lihat Detail <i class="bi bi-arrow-right"></i></small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Warga -->
            <div class="col-md-3 mb-3">
                <a href="{{ route('warga.index', Auth::user()->isAdminRT() ? ['rt' => Auth::user()->rt_number, 'status' => 'semua'] : ['status' => 'semua']) }}" class="text-decoration-none" style="cursor: pointer;">
                    <div class="card text-white bg-success shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-0">Total Warga</h6>
                                    <h2 class="mb-0">{{ $totalWarga }}</h2>
                                </div>
                                <div>
                                    <i class="bi bi-person-badge-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small>Hidup: {{ $totalWargaHidup }} | Meninggal: {{ $totalWargaMeninggal }} | Pindah: {{ $totalWargaPindah }}</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Warga Hidup -->
            <div class="col-md-3 mb-3">
                <a href="{{ route('warga.index', Auth::user()->isAdminRT() ? ['rt' => Auth::user()->rt_number, 'status' => 'hidup'] : ['status' => 'hidup']) }}" class="text-decoration-none" style="cursor: pointer;">
                    <div class="card text-white bg-info shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-0">Warga Hidup</h6>
                                    <h2 class="mb-0">{{ $totalWargaHidup }}</h2>
                                </div>
                                <div>
                                    <i class="bi bi-heart-pulse-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small>Status: Aktif Tercatat</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ulang Tahun Hari Ini -->
            <div class="col-md-3 mb-3">
                <a href="{{ route('warga.index', Auth::user()->isAdminRT() ? ['rt' => Auth::user()->rt_number, 'ulang_tahun' => 'hari_ini'] : ['ulang_tahun' => 'hari_ini']) }}" class="text-decoration-none" style="cursor: pointer;">
                    <div class="card text-white bg-warning shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title text-uppercase mb-0 text-dark">Ulang Tahun</h6>
                                    <h2 class="mb-0 text-dark">{{ $wargaUlangTahunHariIni->count() }}</h2>
                                </div>
                                <div>
                                    <i class="bi bi-cake2-fill text-dark" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small class="text-dark">Hari Ini</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Statistik per RT -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Statistik per RT</h5>
            </div>
            <div class="card-body">
                @if(count($statistikPerRT) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="100">RT</th>
                                    <th>Jumlah Kartu Keluarga</th>
                                    <th>Jumlah Warga (Hidup)</th>
                                    <th>Rata-rata Anggota per KK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistikPerRT as $stat)
                                    <tr class="cursor-pointer" onclick="window.location='{{ route('kartu-keluarga.index', ['rt' => $stat['rt']]) }}';" style="cursor: pointer;">
                                        <td>
                                            <span class="badge bg-info">RT {{ $stat['rt'] }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $stat['jumlah_kk'] }}</strong> KK
                                        </td>
                                        <td>
                                            <strong>{{ $stat['jumlah_warga'] }}</strong> orang
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $stat['jumlah_kk'] > 0 ? number_format($stat['jumlah_warga'] / $stat['jumlah_kk'], 1) : 0 }} orang/KK
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total RW 04</th>
                                    <th><strong>{{ $totalKartuKeluarga }}</strong> KK</th>
                                    <th><strong>{{ $totalWargaHidup }}</strong> orang</th>
                                    <th>
                                        <span class="badge bg-success">
                                            {{ $totalKartuKeluarga > 0 ? number_format($totalWargaHidup / $totalKartuKeluarga, 1) : 0 }} orang/KK
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Belum ada data warga yang terdaftar.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-hide birthday alert after 10 seconds
    setTimeout(function() {
        let birthdayAlert = document.querySelector('.alert-warning');
        if (birthdayAlert) {
            let bsAlert = new bootstrap.Alert(birthdayAlert);
            // Don't auto-close, let user dismiss manually
        }
    }, 10000);
</script>
@endpush
@endsection
