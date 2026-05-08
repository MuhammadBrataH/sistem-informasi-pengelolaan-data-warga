<?php

namespace App\Http\Controllers;

use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class KartuKeluargaController extends Controller
{
    /**
     * Tampilkan daftar semua Kartu Keluarga (dengan filter RT untuk Admin RT).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = KartuKeluarga::withCount('warga');
        
        // Filter berdasarkan role
        if ($user->isAdminRT()) {
            // Admin RT hanya lihat RT sendiri
            $query->where('rt', $user->rt_number);
        }
        
        // Filter berdasarkan RT dari query parameter (untuk clickable dashboard)
        if ($request->has('rt') && $request->rt != '') {
            $query->where('rt', $request->rt);
        }
        
        // Search berdasarkan nomor KK atau nama kepala keluarga
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_kk', 'like', "%{$search}%")
                  ->orWhere('kepala_keluarga', 'like', "%{$search}%");
            });
        }
        
        $kartuKeluargaList = $query->orderBy('rt', 'asc')
            ->orderBy('kepala_keluarga', 'asc')
            ->paginate(10);

        return view('kartu_keluarga.index', compact('kartuKeluargaList'));
    }

    /**
     * Tampilkan form untuk membuat Kartu Keluarga baru.
     */
    public function create()
    {
        return view('kartu_keluarga.create');
    }

    /**
     * Simpan Kartu Keluarga baru ke database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Admin RT hanya boleh input RT sendiri
        $rtValidation = 'required|in:01,02,03,04,05';
        if ($user->isAdminRT()) {
            $rtValidation = 'required|in:' . $user->rt_number;
        }
        
        $validator = Validator::make($request->all(), [
            'nomor_kk' => 'required|string|size:16|unique:kartu_keluarga,nomor_kk',
            'kepala_keluarga' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt' => $rtValidation,
            'rw' => 'nullable|string|max:10',
        ], [
            'nomor_kk.required' => 'Nomor KK wajib diisi.',
            'nomor_kk.size' => 'Nomor KK harus 16 digit.',
            'nomor_kk.unique' => 'Nomor KK sudah terdaftar.',
            'kepala_keluarga.required' => 'Nama Kepala Keluarga wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rt.in' => $user->isAdminRT() ? 'Anda hanya boleh menambahkan KK untuk RT ' . $user->rt_number : 'RT tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        KartuKeluarga::create([
            'nomor_kk' => $request->nomor_kk,
            'kepala_keluarga' => $request->kepala_keluarga,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw ?? '04',
        ]);

        return redirect()->route('kartu-keluarga.index')
            ->with('success', 'Kartu Keluarga berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail Kartu Keluarga beserta daftar anggota keluarga.
     */
    public function show($id)
    {
        $kartuKeluarga = KartuKeluarga::with('warga')->findOrFail($id);
        $user = Auth::user();
        
        // Check authorization: Admin RT hanya bisa lihat KK RT sendiri
        if ($user->isAdminRT() && $kartuKeluarga->rt != $user->rt_number) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses untuk melihat Kartu Keluarga RT ' . $kartuKeluarga->rt . '. Anda hanya dapat mengakses data RT ' . $user->rt_number . '.');
        }

        return view('kartu_keluarga.show', compact('kartuKeluarga'));
    }

    /**
     * Tampilkan form untuk mengedit Kartu Keluarga.
     */
    public function edit($id)
    {
        $kartuKeluarga = KartuKeluarga::findOrFail($id);
        $user = Auth::user();
        
        // Check authorization: Admin RT hanya bisa edit KK RT sendiri
        if ($user->isAdminRT() && $kartuKeluarga->rt != $user->rt_number) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit Kartu Keluarga RT ' . $kartuKeluarga->rt . '. Anda hanya dapat mengakses data RT ' . $user->rt_number . '.');
        }

        return view('kartu_keluarga.edit', compact('kartuKeluarga'));
    }

    /**
     * Update data Kartu Keluarga di database.
     */
    public function update(Request $request, $id)
    {
        $kartuKeluarga = KartuKeluarga::findOrFail($id);
        $user = Auth::user();
        
        // Check authorization: Admin RT hanya bisa update KK RT sendiri
        if ($user->isAdminRT() && $kartuKeluarga->rt != $user->rt_number) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate Kartu Keluarga RT ' . $kartuKeluarga->rt . '.');
        }
        
        // Admin RT hanya boleh update RT sendiri
        $rtValidation = 'required|in:01,02,03,04,05';
        if ($user->isAdminRT()) {
            $rtValidation = 'required|in:' . $user->rt_number;
        }

        $validator = Validator::make($request->all(), [
            'nomor_kk' => 'required|string|size:16|unique:kartu_keluarga,nomor_kk,' . $id,
            'kepala_keluarga' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt' => $rtValidation,
            'rw' => 'nullable|string|max:10',
        ], [
            'nomor_kk.required' => 'Nomor KK wajib diisi.',
            'nomor_kk.size' => 'Nomor KK harus 16 digit.',
            'nomor_kk.unique' => 'Nomor KK sudah terdaftar.',
            'kepala_keluarga.required' => 'Nama Kepala Keluarga wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'rt.required' => 'RT wajib diisi.',
            'rt.in' => $user->isAdminRT() ? 'Anda hanya boleh mengupdate KK untuk RT ' . $user->rt_number : 'RT tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kartuKeluarga->update([
            'nomor_kk' => $request->nomor_kk,
            'kepala_keluarga' => $request->kepala_keluarga,
            'alamat' => $request->alamat,
            'rt' => $request->rt,
            'rw' => $request->rw ?? '04',
        ]);

        return redirect()->route('kartu-keluarga.show', $id)
            ->with('success', 'Kartu Keluarga berhasil diperbarui!');
    }

    /**
     * Hapus Kartu Keluarga dari database.
     */
    public function destroy($id)
    {
        $kartuKeluarga = KartuKeluarga::findOrFail($id);
        $user = Auth::user();
        
        // Check authorization: Admin RT hanya bisa delete KK RT sendiri
        if ($user->isAdminRT() && $kartuKeluarga->rt != $user->rt_number) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus Kartu Keluarga RT ' . $kartuKeluarga->rt . '.');
        }
        
        // Hapus KK beserta semua warga (cascade)
        $kartuKeluarga->delete();

        return redirect()->route('kartu-keluarga.index')
            ->with('success', 'Kartu Keluarga beserta semua anggota berhasil dihapus!');
    }
}
