@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-pencil-square"></i> Edit Kartu Keluarga</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('kartu-keluarga.index') }}">Kartu Keluarga</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Form Edit Kartu Keluarga</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kartu-keluarga.update', $kartuKeluarga->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nomor_kk" class="form-label">Nomor Kartu Keluarga <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomor_kk') is-invalid @enderror" 
                               id="nomor_kk" name="nomor_kk" value="{{ old('nomor_kk', $kartuKeluarga->nomor_kk) }}" 
                               placeholder="Masukkan 16 digit Nomor KK" maxlength="16" required>
                        @error('nomor_kk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: 3273010101010001</small>
                    </div>

                    <div class="mb-3">
                        <label for="kepala_keluarga" class="form-label">Nama Kepala Keluarga <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kepala_keluarga') is-invalid @enderror" 
                               id="kepala_keluarga" name="kepala_keluarga" value="{{ old('kepala_keluarga', $kartuKeluarga->kepala_keluarga) }}" 
                               placeholder="Masukkan nama lengkap Kepala Keluarga" required>
                        @error('kepala_keluarga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" 
                                  placeholder="Masukkan alamat lengkap (nama jalan, nomor rumah, dll)" required>{{ old('alamat', $kartuKeluarga->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
                            <select class="form-select @error('rt') is-invalid @enderror" id="rt" name="rt" required>
                                <option value="">Pilih RT</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                            {{ old('rt', $kartuKeluarga->rt) == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        RT {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rw" class="form-label">RW</label>
                            <input type="text" class="form-control @error('rw') is-invalid @enderror" 
                                   id="rw" name="rw" value="{{ old('rw', $kartuKeluarga->rw) }}" readonly>
                            @error('rw')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Default: RW 04</small>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kartu-keluarga.show', $kartuKeluarga->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Kartu Keluarga
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
