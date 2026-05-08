<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan dashboard aplikasi dengan statistik dan notifikasi ulang tahun.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistik Total (filtered untuk Admin RT)
        if ($user->isAdminRT()) {
            // Admin RT hanya lihat statistik RT sendiri
            $totalKartuKeluarga = \App\Models\KartuKeluarga::where('rt', $user->rt_number)->count();
            $totalWarga = \App\Models\Warga::whereHas('kartuKeluarga', function($q) use ($user) {
                $q->where('rt', $user->rt_number);
            })->count();
            $totalWargaHidup = \App\Models\Warga::whereHas('kartuKeluarga', function($q) use ($user) {
                $q->where('rt', $user->rt_number);
            })->hidup()->count();
            $totalWargaMeninggal = \App\Models\Warga::whereHas('kartuKeluarga', function($q) use ($user) {
                $q->where('rt', $user->rt_number);
            })->meninggal()->count();
            $totalWargaPindah = \App\Models\Warga::whereHas('kartuKeluarga', function($q) use ($user) {
                $q->where('rt', $user->rt_number);
            })->pindah()->count();
        } else {
            // Admin RW lihat semua
            $totalKartuKeluarga = \App\Models\KartuKeluarga::count();
            $totalWarga = \App\Models\Warga::count();
            $totalWargaHidup = \App\Models\Warga::hidup()->count();
            $totalWargaMeninggal = \App\Models\Warga::meninggal()->count();
            $totalWargaPindah = \App\Models\Warga::pindah()->count();
        }

        // Statistik per RT (RT 01 - RT 05) - UPDATED RANGE
        $statistikPerRT = [];
        for ($i = 1; $i <= 5; $i++) {
            $rtNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            
            // Admin RT hanya lihat RT sendiri
            if ($user->isAdminRT() && $rtNumber != $user->rt_number) {
                continue;
            }
            
            $jumlahKK = \App\Models\KartuKeluarga::where('rt', $rtNumber)->count();
            $jumlahWarga = \App\Models\Warga::whereHas('kartuKeluarga', function($query) use ($rtNumber) {
                $query->where('rt', $rtNumber);
            })->hidup()->count();
            
            // Hanya simpan RT yang ada datanya
            if ($jumlahKK > 0 || $jumlahWarga > 0) {
                $statistikPerRT[] = [
                    'rt' => $rtNumber,
                    'jumlah_kk' => $jumlahKK,
                    'jumlah_warga' => $jumlahWarga,
                ];
            }
        }

        // Warga yang Ulang Tahun Hari Ini (filtered untuk Admin RT)
        $query = \App\Models\Warga::ulangTahunHariIni()->with('kartuKeluarga');
        
        if ($user->isAdminRT()) {
            $query->whereHas('kartuKeluarga', function($q) use ($user) {
                $q->where('rt', $user->rt_number);
            });
        }
        
        $wargaUlangTahunHariIni = $query->get();

        return view('home', compact(
            'totalKartuKeluarga',
            'totalWarga',
            'totalWargaHidup',
            'totalWargaMeninggal',
            'totalWargaPindah',
            'statistikPerRT',
            'wargaUlangTahunHariIni'
        ));
    }
}
