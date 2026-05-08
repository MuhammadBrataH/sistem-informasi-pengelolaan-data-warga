@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-person-badge-fill"></i> Data Warga RW 04</h2>
                <p class="text-muted">Manajemen data penduduk dengan sistem mutasi</p>
            </div>
            <div style="visibility: hidden;">
                <!-- Tombol Tambah Warga disembunyikan - hanya bisa tambah dari Detail KK -->
                <a href="#" class="btn btn-primary" style="pointer-events: none; opacity: 0;">
                    <i class="bi bi-plus-circle"></i> Tambah Warga
                </a>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('warga.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="status" class="form-label"><strong>Filter Status Dasar:</strong></label>
                            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                <option value="hidup" {{ $statusFilter == 'hidup' ? 'selected' : '' }}>
                                    ✅ Hanya Hidup
                                </option>
                                <option value="semua" {{ $statusFilter == 'semua' ? 'selected' : '' }}>
                                    📋 Tampilkan Semua
                                </option>
                                <option value="meninggal" {{ $statusFilter == 'meninggal' ? 'selected' : '' }}>
                                    ⚰️ Meninggal
                                </option>
                                <option value="pindah" {{ $statusFilter == 'pindah' ? 'selected' : '' }}>
                                    📦 Pindah
                                </option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="search" class="form-label"><strong>Cari Warga:</strong></label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Cari berdasarkan Nama atau NIK..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>

                        @if(request('search'))
                            <div class="col-md-2">
                                <a href="{{ route('warga.index', ['status' => $statusFilter]) }}" class="btn btn-secondary w-100">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Badge Info -->
        <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle"></i> 
            <strong>Filter Aktif:</strong> 
            @if($statusFilter == 'hidup')
                Menampilkan warga dengan status <span class="badge bg-success">Hidup</span>
            @elseif($statusFilter == 'meninggal')
                Menampilkan warga dengan status <span class="badge bg-dark">Meninggal</span>
            @elseif($statusFilter == 'pindah')
                Menampilkan warga dengan status <span class="badge bg-warning text-dark">Pindah</span>
            @else
                Menampilkan <span class="badge bg-primary">Semua Status</span>
            @endif
            (Total: <strong>{{ $wargaList->total() }}</strong> warga)
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Warga</h5>
            </div>
            <div class="card-body">
                @if($wargaList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>TTL</th>
                                    <th>JK</th>
                                    <th>Kartu Keluarga</th>
                                    <th>Status Dasar</th>
                                    <th width="250">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wargaList as $index => $warga)
                                <tr class="{{ $warga->status_dasar != 'Hidup' ? 'table-secondary' : '' }}">
                                    <td>{{ $wargaList->firstItem() + $index }}</td>
                                    <td><small>{{ $warga->nik }}</small></td>
                                    <td>
                                        <strong>{{ $warga->nama_lengkap }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $warga->pekerjaan }}</small>
                                    </td>
                                    <td>
                                        {{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir->format('d-m-Y') }}
                                        <br><small class="text-muted">Umur: {{ $warga->umur }} tahun</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $warga->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $warga->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('kartu-keluarga.show', $warga->kartuKeluarga->id) }}" class="text-decoration-none">
                                            {{ $warga->kartuKeluarga->kepala_keluarga }}
                                            <br><small class="text-muted">RT {{ $warga->kartuKeluarga->rt }}</small>
                                        </a>
                                    </td>
                                    <td>
                                        @if($warga->status_dasar == 'Hidup')
                                            <span class="badge bg-success">Hidup</span>
                                        @elseif($warga->status_dasar == 'Meninggal')
                                            <span class="badge bg-dark">Meninggal</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pindah</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('warga.show', $warga->id) }}" 
                                               class="btn btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if($warga->status_dasar == 'Hidup')
                                                <!-- Tombol Lapor Meninggal -->
                                                <button type="button" class="btn btn-dark" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalMeninggal{{ $warga->id }}"
                                                        title="Lapor Meninggal">
                                                    <i class="bi bi-heartbreak"></i>
                                                </button>

                                                <!-- Tombol Lapor Pindah -->
                                                <button type="button" class="btn btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalPindah{{ $warga->id }}"
                                                        title="Lapor Pindah">
                                                    <i class="bi bi-box-arrow-right"></i>
                                                </button>
                                            @else
                                                <!-- Tombol Kembalikan - berbeda untuk Meninggal vs Pindah -->
                                                @if($warga->status_dasar == 'Meninggal')
                                                    <button type="button" class="btn btn-secondary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalKembalikan{{ $warga->id }}"
                                                            title="Kembalikan dari Meninggal">
                                                        <i class="bi bi-heart-pulse"></i>
                                                    </button>
                                                @else
                                                    <!-- Status Pindah -->
                                                    <button type="button" class="btn btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalKembalikan{{ $warga->id }}"
                                                            title="Batalkan Kepindahan">
                                                        <i class="bi bi-house-check"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Modal Lapor Meninggal -->
                                        <div class="modal fade" id="modalMeninggal{{ $warga->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title">Konfirmasi Lapor Meninggal</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin melaporkan bahwa:</p>
                                                        <div class="alert alert-warning">
                                                            <strong>{{ $warga->nama_lengkap }}</strong>
                                                            <br>NIK: {{ $warga->nik }}
                                                        </div>
                                                        <p>telah <strong>meninggal dunia</strong>?</p>
                                                        <p class="text-muted">
                                                            <small><i class="bi bi-info-circle"></i> Data tidak akan dihapus, hanya status akan diubah.</small>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('warga.lapor-meninggal', $warga->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-dark">
                                                                <i class="bi bi-check-circle"></i> Ya, Lapor Meninggal
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Lapor Pindah -->
                                        <div class="modal fade" id="modalPindah{{ $warga->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title">Konfirmasi Lapor Pindah</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin melaporkan bahwa:</p>
                                                        <div class="alert alert-info">
                                                            <strong>{{ $warga->nama_lengkap }}</strong>
                                                            <br>NIK: {{ $warga->nik }}
                                                        </div>
                                                        <p>telah <strong>pindah</strong> dari wilayah ini?</p>
                                                        <p class="text-muted">
                                                            <small><i class="bi bi-info-circle"></i> Data tidak akan dihapus, hanya status akan diubah.</small>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('warga.lapor-pindah', $warga->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-warning">
                                                                <i class="bi bi-check-circle"></i> Ya, Lapor Pindah
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Kembalikan ke Hidup -->
                                        <div class="modal fade" id="modalKembalikan{{ $warga->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">Konfirmasi Kembalikan Status</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin mengembalikan status:</p>
                                                        <div class="alert alert-warning">
                                                            <strong>{{ $warga->nama_lengkap }}</strong>
                                                            <br>NIK: {{ $warga->nik }}
                                                            <br>Status Saat Ini: 
                                                            @if($warga->status_dasar == 'Meninggal')
                                                                <span class="badge bg-dark">⚰️ {{ $warga->status_dasar }}</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">📦 {{ $warga->status_dasar }}</span>
                                                            @endif
                                                        </div>
                                                        <p>menjadi <strong><span class="badge bg-success">✅ Hidup</span></strong>?</p>
                                                        @if($warga->status_dasar == 'Meninggal')
                                                            <p class="text-muted"><small><i class="bi bi-info-circle"></i> Mengembalikan status dari Meninggal ke Hidup.</small></p>
                                                        @else
                                                            <p class="text-muted"><small><i class="bi bi-info-circle"></i> Membatalkan kepindahan dan mengembalikan warga ke status aktif.</small></p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('warga.kembalikan-hidup', $warga->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="bi bi-check-circle"></i> Ya, Kembalikan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $wargaList->firstItem() }} - {{ $wargaList->lastItem() }} 
                                dari {{ $wargaList->total() }} warga
                            </small>
                        </div>
                        <div>
                            {{ $wargaList->appends(['status' => $statusFilter, 'search' => request('search')])->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> 
                        Tidak ada data warga 
                        @if($statusFilter != 'semua')
                            dengan status <strong>{{ ucfirst($statusFilter) }}</strong>
                        @endif
                        yang ditemukan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
