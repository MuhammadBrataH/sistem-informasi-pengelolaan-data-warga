@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-file-earmark-text"></i> Detail Kartu Keluarga</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.index') }}">Kartu Keluarga</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Informasi Kartu Keluarga -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-card-heading"></i> Informasi Kartu Keluarga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Nomor KK</th>
                                <td>: <strong>{{ $kartuKeluarga->nomor_kk }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kepala Keluarga</th>
                                <td>: <strong>{{ $kartuKeluarga->kepala_keluarga }}</strong></td>
                            </tr>
                            <tr>
                                <th>RT / RW</th>
                                <td>: RT {{ $kartuKeluarga->rt }} / RW {{ $kartuKeluarga->rw }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Alamat</th>
                                <td>: {{ $kartuKeluarga->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Anggota</th>
                                <td>: <span class="badge bg-info">{{ $kartuKeluarga->warga->count() }} orang</span></td>
                            </tr>
                            <tr>
                                <th>Terdaftar Sejak</th>
                                <td>: {{ $kartuKeluarga->created_at->format('d F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('kartu-keluarga.edit', $kartuKeluarga->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Edit KK
                    </a>
                    <form action="{{ route('kartu-keluarga.destroy', $kartuKeluarga->id) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Yakin ingin menghapus Kartu Keluarga ini beserta semua anggotanya?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Hapus KK
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Anggota Keluarga -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Anggota Keluarga</h5>
                
                <!-- Dropdown Tambah Anggota (Dual Mode) -->
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-plus-fill"></i> Tambah Anggota Keluarga
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">Pilih Cara Menambah:</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('warga.create', ['kk_id' => $kartuKeluarga->id]) }}">
                                <i class="bi bi-person-plus text-success"></i> 
                                <strong>Input Warga Baru</strong>
                                <br>
                                <small class="text-muted">Untuk bayi lahir atau pendatang baru</small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('warga.form-pilih', ['kk_id' => $kartuKeluarga->id]) }}">
                                <i class="bi bi-arrow-left-right text-primary"></i> 
                                <strong>Pilih dari Warga Lain</strong>
                                <br>
                                <small class="text-muted">Untuk pecah KK atau pindah KK</small>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                @if($kartuKeluarga->warga->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>JK</th>
                                    <th>Status Keluarga</th>
                                    <th>Pekerjaan</th>
                                    <th>Status Dasar</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kartuKeluarga->warga as $index => $warga)
                                <tr class="{{ $warga->status_dasar != 'Hidup' ? 'table-secondary' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td><small>{{ $warga->nik }}</small></td>
                                    <td>
                                        <strong>{{ $warga->nama_lengkap }}</strong>
                                        @if($warga->status_dasar != 'Hidup')
                                            <br><small class="text-muted">({{ $warga->status_dasar }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir->format('d-m-Y') }}
                                        <br><small class="text-muted">Umur: {{ $warga->umur }} tahun</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $warga->jenis_kelamin == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $warga->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td>{{ $warga->status_keluarga }}</td>
                                    <td>{{ $warga->pekerjaan }}</td>
                                    <td>
                                        @if($warga->status_dasar == 'Hidup')
                                            <span class="badge bg-success">{{ $warga->status_dasar }}</span>
                                        @elseif($warga->status_dasar == 'Meninggal')
                                            <span class="badge bg-dark">{{ $warga->status_dasar }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $warga->status_dasar }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('warga.show', $warga->id) }}" class="btn btn-info" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Belum ada anggota keluarga yang terdaftar.
                        <a href="#" class="alert-link">Klik di sini untuk menambahkan.</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
