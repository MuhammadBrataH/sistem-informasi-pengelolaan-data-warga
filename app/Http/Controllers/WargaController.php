<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WargaController extends Controller
{
    /**
     * Tampilkan daftar warga dengan filter status dasar dan RT (untuk Admin RT).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->get('status', 'hidup'); // Default: hanya warga hidup
        
        $query = Warga::with('kartuKeluarga');
        
        // Filter berdasarkan RT untuk Admin RT
        if ($user->isAdminRT()) {
            $query->whereHas('kartuKeluarga', function($q) use ($user) {
                $q->where('rt', $user->rt_number);
            });
        }
        
        // Filter berdasarkan RT dari query parameter (untuk clickable dashboard)
        if ($request->has('rt') && $request->rt != '') {
            $query->whereHas('kartuKeluarga', function($q) use ($request) {
                $q->where('rt', $request->rt);
            });
        }
        
        // Filter ulang tahun hari ini (dari clickable dashboard)
        if ($request->has('ulang_tahun') && $request->ulang_tahun == 'hari_ini') {
            $query->ulangTahunHariIni();
        }
        
        // Filter berdasarkan status dasar
        switch ($statusFilter) {
            case 'hidup':
                $query->hidup();
                break;
            case 'meninggal':
                $query->meninggal();
                break;
            case 'pindah':
                $query->pindah();
                break;
            case 'semua':
                // Tidak ada filter, tampilkan semua
                break;
            default:
                $query->hidup();
        }
        
        // Search (opsional)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik','like', "%{$search}%");
            });
        }
        
        $wargaList = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('warga.index', compact('wargaList', 'statusFilter'));
    }

    /**
     * Tampilkan detail warga.
     */
    public function show($id)
    {
        $warga = Warga::with('kartuKeluarga')->findOrFail($id);
        $user = Auth::user();
        
        // Check authorization: Admin RT hanya bisa lihat warga RT sendiri
        if ($user->isAdminRT() && $warga->kartuKeluarga->rt != $user->rt_number) {
            return redirect()->route('home')
                ->with('error', 'Anda tidak memiliki akses untuk melihat warga RT ' . $warga->kartuKeluarga->rt . '. Anda hanya dapat mengakses data RT ' . $user->rt_number . '.');
        }
        
        return view('warga.show', compact('warga'));
    }

    /**
     * Lapor warga meninggal (update status, TIDAK hapus data).
     */
    public function laporMeninggal($id)
    {
        $warga = Warga::findOrFail($id);
        
        // Update status dasar menjadi Meninggal
        $warga->update([
            'status_dasar' => 'Meninggal',
        ]);
        
        return redirect()->back()
            ->with('success', "Status warga {$warga->nama_lengkap} berhasil diubah menjadi Meninggal.");
    }

    /**
     * Lapor warga pindah (update status, TIDAK hapus data).
     */
    public function laporPindah($id)
    {
        $warga = Warga::findOrFail($id);
        
        // Update status dasar menjadi Pindah
        $warga->update([
            'status_dasar' => 'Pindah',
        ]);
        
        return redirect()->back()
            ->with('success', "Status warga {$warga->nama_lengkap} berhasil diubah menjadi Pindah.");
    }

    /**
     * Kembalikan status warga menjadi Hidup.
     */
    public function kembalikanHidup($id)
    {
        $warga = Warga::findOrFail($id);
        
        // Update status dasar menjadi Hidup
        $warga->update([
            'status_dasar' => 'Hidup',
        ]);
        
        return redirect()->back()
            ->with('success', "Status warga {$warga->nama_lengkap} berhasil dikembalikan menjadi Hidup.");
    }

    /**
     * Tampilkan halaman cetak surat pengantar untuk warga.
     */
    public function cetakSuratPengantar($id)
    {
        $warga = Warga::with('kartuKeluarga')->findOrFail($id);
        
        // View khusus untuk print (tanpa layout utama)
        return view('warga.surat-pengantar', compact('warga'));
    }

    /**
     * Tampilkan form untuk input warga baru ke KK tertentu.
     */
    public function create(Request $request)
    {
        $kkId = $request->get('kk_id');
        $kartuKeluarga = null;
        
        if ($kkId) {
            $kartuKeluarga = KartuKeluarga::findOrFail($kkId);
        }
        
        return view('warga.create', compact('kartuKeluarga'));
    }

    /**
     * Simpan warga baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kk_id' => 'required|exists:kartu_keluarga,id',
            'nik' => 'required|string|size:16|unique:warga,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'status_keluarga' => 'required|in:Kepala Keluarga,Istri,Anak,Famili Lain',
            'status_kependudukan' => 'required|in:Tetap,Kontrak',
        ], [
            'kk_id.required' => 'Kartu Keluarga wajib dipilih.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'agama.required' => 'Agama wajib diisi.',
            'status_perkawinan.required' => 'Status perkawinan wajib diisi.',
            'pekerjaan.required' => 'Pekerjaan wajib diisi.',
            'status_keluarga.required' => 'Status keluarga wajib dipilih.',
            'status_kependudukan.required' => 'Status kependudukan wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Warga::create([
            'kk_id' => $request->kk_id,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_perkawinan' => $request->status_perkawinan,
            'pekerjaan' => $request->pekerjaan,
            'status_keluarga' => $request->status_keluarga,
            'status_kependudukan' => $request->status_kependudukan,
            'status_dasar' => 'Hidup',
        ]);

        return redirect()->route('kartu-keluarga.show', $request->kk_id)
            ->with('success', 'Warga baru berhasil ditambahkan ke Kartu Keluarga!');
    }

    /**
     * Tampilkan form untuk memilih warga lama yang akan dipindahkan ke KK tertentu.
     */
    public function formPilihWarga(Request $request)
    {
        $kkId = $request->get('kk_id');
        $kartuKeluarga = KartuKeluarga::findOrFail($kkId);
        
        // Ambil semua warga yang TIDAK di KK ini dan masih hidup
        $wargaList = Warga::with('kartuKeluarga')
            ->where('kk_id', '!=', $kkId)
            ->hidup()
            ->orderBy('nama_lengkap', 'asc')
            ->get();
        
        return view('warga.pilih-warga', compact('kartuKeluarga', 'wargaList'));
    }

    /**
     * Pindahkan warga dari KK lama ke KK baru.
     */
    public function pindahkanWarga(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warga_id' => 'required|exists:warga,id',
            'kk_id_baru' => 'required|exists:kartu_keluarga,id',
        ], [
            'warga_id.required' => 'Warga wajib dipilih.',
            'kk_id_baru.required' => 'Kartu Keluarga tujuan wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $warga = Warga::findOrFail($request->warga_id);
        $kkLama = $warga->kartuKeluarga;
        $kkBaru = KartuKeluarga::findOrFail($request->kk_id_baru);

        // Update kk_id warga
        $warga->update([
            'kk_id' => $request->kk_id_baru,
        ]);

        return redirect()->route('kartu-keluarga.show', $request->kk_id_baru)
            ->with('success', "Warga {$warga->nama_lengkap} berhasil dipindahkan dari KK {$kkLama->nomor_kk} (RT {$kkLama->rt}) ke KK {$kkBaru->nomor_kk} (RT {$kkBaru->rt})!");
    }
}
