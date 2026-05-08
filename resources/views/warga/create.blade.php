@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-person-plus-fill"></i> Tambah Warga Baru</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.index') }}">Kartu Keluarga</a></li>
                        @if($kartuKeluarga)
                            <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.show', $kartuKeluarga->id) }}">Detail KK</a></li>
                        @endif
                        <li class="breadcrumb-item active">Tambah Warga</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Info KK -->
        @if($kartuKeluarga)
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Menambahkan warga baru ke <strong>KK {{ $kartuKeluarga->nomor_kk }}</strong> 
                ({{ $kartuKeluarga->kepala_keluarga }}, RT {{ $kartuKeluarga->rt }}/RW {{ $kartuKeluarga->rw }})
            </div>
        @endif

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-person"></i> Form Input Warga Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('warga.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kk_id" value="{{ $kartuKeluarga->id ?? '' }}">

                    <div class="row">
                        <!-- NIK -->
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                   id="nik" name="nik" value="{{ old('nik') }}" 
                                   placeholder="16 digit NIK" maxlength="16" required>
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" 
                                   placeholder="Nama lengkap sesuai KTP" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                   id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                   placeholder="Kota tempat lahir" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Agama -->
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                            <select class="form-select @error('agama') is-invalid @enderror" 
                                    id="agama" name="agama" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Perkawinan -->
                        <div class="col-md-6 mb-3">
                            <label for="status_perkawinan" class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                            <select class="form-select @error('status_perkawinan') is-invalid @enderror" 
                                    id="status_perkawinan" name="status_perkawinan" required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                                <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                                <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                                <option value="Cerai Mati" {{ old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                            </select>
                            @error('status_perkawinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pekerjaan -->
                        <div class="col-md-6 mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror" 
                                   id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" 
                                   placeholder="Pekerjaan" required>
                            @error('pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Keluarga -->
                        <div class="col-md-6 mb-3">
                            <label for="status_keluarga" class="form-label">Status dalam Keluarga <span class="text-danger">*</span></label>
                            <select class="form-select @error('status_keluarga') is-invalid @enderror" 
                                    id="status_keluarga" name="status_keluarga" required>
                                <option value="">Pilih Status</option>
                                <option value="Kepala Keluarga" {{ old('status_keluarga') == 'Kepala Keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                <option value="Istri" {{ old('status_keluarga') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                <option value="Anak" {{ old('status_keluarga') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                <option value="Famili Lain" {{ old('status_keluarga') == 'Famili Lain' ? 'selected' : '' }}>Famili Lain</option>
                            </select>
                            @error('status_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Kependudukan -->
                        <div class="col-md-6 mb-3">
                            <label for="status_kependudukan" class="form-label">Status Kependudukan <span class="text-danger">*</span></label>
                            <select class="form-select @error('status_kependudukan') is-invalid @enderror" 
                                    id="status_kependudukan" name="status_kependudukan" required>
                                <option value="">Pilih Status</option>
                                <option value="Tetap" {{ old('status_kependudukan') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                <option value="Kontrak" {{ old('status_kependudukan') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('status_kependudukan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ $kartuKeluarga ? route('kartu-keluarga.show', $kartuKeluarga->id) : route('warga.index') }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Warga Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
