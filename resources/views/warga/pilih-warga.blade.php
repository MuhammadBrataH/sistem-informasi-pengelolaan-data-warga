@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-arrow-left-right"></i> Pilih Warga untuk Dipindahkan</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.index') }}">Kartu Keluarga</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.show', $kartuKeluarga->id) }}">Detail KK</a></li>
                        <li class="breadcrumb-item active">Pilih Warga</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Info KK Tujuan -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            <strong>KK Tujuan:</strong> {{ $kartuKeluarga->nomor_kk }} 
            ({{ $kartuKeluarga->kepala_keluarga }}, RT {{ $kartuKeluarga->rt }}/RW {{ $kartuKeluarga->rw }})
            <br>
            <small>Pilih warga dari KK lain yang akan dipindahkan ke KK ini.</small>
        </div>

        <!-- Search & Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" id="searchWarga" class="form-control" 
                               placeholder="🔍 Cari berdasarkan Nama atau NIK...">
                    </div>
                    <div class="col-md-3">
                        <select id="filterRT" class="form-select">
                            <option value="">Semua RT</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">RT {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Warga dari KK Lain (Total: {{ $wargaList->count() }})</h5>
            </div>
            <div class="card-body">
                @if($wargaList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="tableWarga">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>TTL</th>
                                    <th>JK</th>
                                    <th>KK Saat Ini</th>
                                    <th>RT</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wargaList as $index => $warga)
                                <tr data-nama="{{ strtolower($warga->nama_lengkap) }}" 
                                    data-nik="{{ $warga->nik }}" 
                                    data-rt="{{ $warga->kartuKeluarga->rt }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><small>{{ $warga->nik }}</small></td>
                                    <td>
                                        <strong>{{ $warga->nama_lengkap }}</strong>
                                        <br><small class="text-muted">{{ $warga->pekerjaan }}</small>
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
                                        {{ $warga->kartuKeluarga->kepala_keluarga }}
                                        <br><small class="text-muted">KK: {{ $warga->kartuKeluarga->nomor_kk }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">RT {{ $warga->kartuKeluarga->rt }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPindah{{ $warga->id }}"
                                                title="Pindahkan ke KK ini">
                                            <i class="bi bi-arrow-right-circle"></i> Pilih
                                        </button>

                                        <!-- Modal Konfirmasi -->
                                        <div class="modal fade" id="modalPindah{{ $warga->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">Konfirmasi Pindahkan Warga</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin memindahkan:</p>
                                                        <div class="alert alert-warning">
                                                            <strong>{{ $warga->nama_lengkap }}</strong>
                                                            <br>NIK: {{ $warga->nik }}
                                                            <br>KK Lama: {{ $warga->kartuKeluarga->nomor_kk }} (RT {{ $warga->kartuKeluarga->rt }})
                                                        </div>
                                                        <p>ke Kartu Keluarga:</p>
                                                        <div class="alert alert-info">
                                                            <strong>{{ $kartuKeluarga->kepala_keluarga }}</strong>
                                                            <br>KK Baru: {{ $kartuKeluarga->nomor_kk }} (RT {{ $kartuKeluarga->rt }})
                                                        </div>
                                                        <p class="text-muted">
                                                            <small><i class="bi bi-info-circle"></i> Warga akan berpindah dari KK lama ke KK baru.</small>
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('warga.pindahkan') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="warga_id" value="{{ $warga->id }}">
                                                            <input type="hidden" name="kk_id_baru" value="{{ $kartuKeluarga->id }}">
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="bi bi-check-circle"></i> Ya, Pindahkan
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
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> 
                        Tidak ada warga dari KK lain yang tersedia untuk dipindahkan.
                        <br>
                        <small>Semua warga mungkin sudah ada di KK ini atau tidak ada warga lain yang terdaftar.</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-3">
            <a href="{{ route('kartu-keluarga.show', $kartuKeluarga->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail KK
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchWarga').addEventListener('keyup', function() {
        filterTable();
    });

    document.getElementById('filterRT').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchValue = document.getElementById('searchWarga').value.toLowerCase();
        const rtValue = document.getElementById('filterRT').value;
        const rows = document.querySelectorAll('#tableWarga tbody tr');

        rows.forEach(row => {
            const nama = row.getAttribute('data-nama');
            const nik = row.getAttribute('data-nik');
            const rt = row.getAttribute('data-rt');

            const matchSearch = nama.includes(searchValue) || nik.includes(searchValue);
            const matchRT = rtValue === '' || rt === rtValue;

            if (matchSearch && matchRT) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection
