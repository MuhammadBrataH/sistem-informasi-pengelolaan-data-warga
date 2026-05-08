@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-people-fill"></i> Daftar Kartu Keluarga</h2>
                <p class="text-muted">Manajemen data Kartu Keluarga RW 04</p>
            </div>
            <div>
                <a href="{{ route('kartu-keluarga.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Kartu Keluarga
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('kartu-keluarga.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari Nomor KK atau Nama KK..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="rt" class="form-select">
                                <option value="">Semua RT</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                            {{ request('rt') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                        RT {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                        @if(request('search') || request('rt'))
                            <div class="col-md-2">
                                <a href="{{ route('kartu-keluarga.index') }}" class="btn btn-secondary w-100">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Data Kartu Keluarga</h5>
            </div>
            <div class="card-body">
                @if($kartuKeluargaList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nomor KK</th>
                                    <th>Kepala Keluarga</th>
                                    <th>Alamat</th>
                                    <th>RT/RW</th>
                                    <th>Jumlah Anggota</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kartuKeluargaList as $index => $kk)
                                <tr>
                                    <td>{{ $kartuKeluargaList->firstItem() + $index }}</td>
                                    <td><strong>{{ $kk->nomor_kk }}</strong></td>
                                    <td>{{ $kk->kepala_keluarga }}</td>
                                    <td>{{ Str::limit($kk->alamat, 40) }}</td>
                                    <td>
                                        <span class="badge bg-info">RT {{ $kk->rt }} / RW {{ $kk->rw }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $kk->warga_count }} orang</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('kartu-keluarga.show', $kk->id) }}" class="btn btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('kartu-keluarga.edit', $kk->id) }}" class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('kartu-keluarga.destroy', $kk->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus KK ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $kartuKeluargaList->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Belum ada data Kartu Keluarga.
                        <a href="{{ route('kartu-keluarga.create') }}" class="alert-link">Klik di sini untuk menambahkan.</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
