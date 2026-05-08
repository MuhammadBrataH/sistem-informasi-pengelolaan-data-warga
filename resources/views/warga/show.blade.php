@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-person-circle"></i> Detail Warga</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('warga.index') }}">Data Warga</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('warga.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Status Badge -->
        @if($warga->status_dasar != 'Hidup')
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                Status warga ini: 
                <span class="badge {{ $warga->status_dasar == 'Meninggal' ? 'bg-dark' : 'bg-warning text-dark' }}">
                    {{ $warga->status_dasar }}
                </span>
            </div>
        @endif

        <!-- Informasi Pribadi -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">NIK</th>
                                <td>: <strong>{{ $warga->nik }}</strong></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>: <strong>{{ $warga->nama_lengkap }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tempat, Tanggal Lahir</th>
                                <td>: {{ $warga->tempat_tanggal_lahir }}</td>
                            </tr>
                            <tr>
                                <th>Umur</th>
                                <td>: {{ $warga->umur }} tahun</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $warga->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td>: {{ $warga->agama }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Status Perkawinan</th>
                                <td>: {{ $warga->status_perkawinan }}</td>
                            </tr>
                            <tr>
                                <th>Pekerjaan</th>
                                <td>: {{ $warga->pekerjaan }}</td>
                            </tr>
                            <tr>
                                <th>Status Keluarga</th>
                                <td>: <span class="badge bg-info">{{ $warga->status_keluarga }}</span></td>
                            </tr>
                            <tr>
                                <th>Status Kependudukan</th>
                                <td>: {{ $warga->status_kependudukan }}</td>
                            </tr>
                            <tr>
                                <th>Status Dasar</th>
                                <td>: 
                                    @if($warga->status_dasar == 'Hidup')
                                        <span class="badge bg-success">Hidup</span>
                                    @elseif($warga->status_dasar == 'Meninggal')
                                        <span class="badge bg-dark">Meninggal</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pindah</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kartu Keluarga -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-house-door-fill"></i> Informasi Kartu Keluarga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Nomor KK</th>
                                <td>: <strong>{{ $warga->kartuKeluarga->nomor_kk }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kepala Keluarga</th>
                                <td>: {{ $warga->kartuKeluarga->kepala_keluarga }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">Alamat</th>
                                <td>: {{ $warga->kartuKeluarga->alamat_lengkap }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('kartu-keluarga.show', $warga->kartuKeluarga->id) }}" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i> Lihat Detail Kartu Keluarga
                    </a>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-gear-fill"></i> Aksi</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('warga.cetak-surat', $warga->id) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer-fill"></i> Cetak Surat Pengantar
                </a>
                
                @if($warga->status_dasar == 'Hidup')
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalMeninggal">
                        <i class="bi bi-heartbreak"></i> Lapor Meninggal
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalPindah">
                        <i class="bi bi-box-arrow-right"></i> Lapor Pindah
                    </button>
                @elseif($warga->status_dasar == 'Meninggal')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalKembalikan">
                        <i class="bi bi-arrow-counterclockwise"></i> Kembalikan Status Hidup
                    </button>
                @elseif($warga->status_dasar == 'Pindah')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalKembalikan">
                        <i class="bi bi-arrow-counterclockwise"></i> Batalkan Kepindahan
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals (sama seperti di index) -->
<div class="modal fade" id="modalMeninggal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Konfirmasi Lapor Meninggal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin melaporkan bahwa <strong>{{ $warga->nama_lengkap }}</strong> telah meninggal dunia?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('warga.lapor-meninggal', $warga->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-dark">Ya, Lapor Meninggal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPindah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Konfirmasi Lapor Pindah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin melaporkan bahwa <strong>{{ $warga->nama_lengkap }}</strong> telah pindah?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('warga.lapor-pindah', $warga->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">Ya, Lapor Pindah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKembalikan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Konfirmasi Kembalikan Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Kembalikan status <strong>{{ $warga->nama_lengkap }}</strong> menjadi Hidup?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('warga.kembalikan-hidup', $warga->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Ya, Kembalikan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
