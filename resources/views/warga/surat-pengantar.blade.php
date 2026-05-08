<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengantar RT/RW - {{ $warga->nama_lengkap }}</title>
    
    <style>
        /* General Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background-color: #fff;
            padding: 20px;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 40px;
        }
        
        /* Kop Surat */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .kop-surat h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .kop-surat h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .kop-surat p {
            font-size: 11pt;
            margin: 2px 0;
        }
        
        /* Header Surat */
        .nomor-surat {
            margin: 20px 0;
            text-align: center;
        }
        
        .nomor-surat h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .nomor-surat p {
            font-size: 11pt;
        }
        
        /* Isi Surat */
        .isi-surat {
            margin: 30px 0;
            text-align: justify;
        }
        
        .isi-surat p {
            margin-bottom: 15px;
            text-indent: 50px;
        }
        
        .isi-surat p.no-indent {
            text-indent: 0;
        }
        
        /* Data Warga Table */
        .data-warga {
            margin: 20px 0 20px 50px;
        }
        
        .data-warga table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-warga td {
            padding: 5px 0;
            vertical-align: top;
        }
        
        .data-warga td:first-child {
            width: 200px;
        }
        
        .data-warga td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        
        /* Tanda Tangan */
        .tanda-tangan {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .ttd-box {
            width: 45%;
            text-align: center;
        }
        
        .ttd-box p {
            margin-bottom: 5px;
        }
        
        .ttd-box .jabatan {
            font-weight: bold;
            margin-top: 80px;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding: 0 20px;
        }
        
        /* Print Styles */
        @media print {
            body {
                padding: 0;
            }
            
            .container {
                padding: 20mm;
            }
            
            .no-print {
                display: none !important;
            }
            
            @page {
                size: A4;
                margin: 20mm;
            }
            
            /* Prevent page break inside elements */
            .kop-surat, .data-warga, .tanda-tangan {
                page-break-inside: avoid;
            }
        }
        
        /* Tombol Print */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14pt;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 15px 30px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14pt;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .back-button:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <!-- Tombol Print & Back (hidden saat print) -->
    <a href="{{ route('warga.show', $warga->id) }}" class="back-button no-print">← Kembali</a>
    <button onclick="window.print()" class="print-button no-print">🖨️ Cetak Surat</button>
    
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <h1>PEMERINTAH KOTA BANDUNG</h1>
            <h2>RUKUN WARGA 04</h2>
            <p>Kelurahan ..........................., Kecamatan .............................</p>
            <p>Alamat: ........................................................................................................................</p>
        </div>
        
        <!-- Nomor Surat -->
        <div class="nomor-surat">
            <h3>SURAT PENGANTAR</h3>
            <p>Nomor: ......./RW.04/{{ date('m') }}/{{ date('Y') }}</p>
        </div>
        
        <!-- Isi Surat -->
        <div class="isi-surat">
            <p>Yang bertanda tangan di bawah ini, Ketua RT {{ $warga->kartuKeluarga->rt }} / RW {{ $warga->kartuKeluarga->rw }}, dengan ini menerangkan bahwa:</p>
            
            <div class="data-warga">
                <table>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>:</td>
                        <td><strong>{{ $warga->nama_lengkap }}</strong></td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>{{ $warga->nik }}</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Umur</td>
                        <td>:</td>
                        <td>{{ $warga->umur }} tahun</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>{{ $warga->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td>Agama</td>
                        <td>:</td>
                        <td>{{ $warga->agama }}</td>
                    </tr>
                    <tr>
                        <td>Status Perkawinan</td>
                        <td>:</td>
                        <td>{{ $warga->status_perkawinan }}</td>
                    </tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td>:</td>
                        <td>{{ $warga->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $warga->kartuKeluarga->alamat }}, RT {{ $warga->kartuKeluarga->rt }}/RW {{ $warga->kartuKeluarga->rw }}</td>
                    </tr>
                </table>
            </div>
            
            <p>Adalah benar warga kami yang bertempat tinggal di wilayah RT {{ $warga->kartuKeluarga->rt }} / RW {{ $warga->kartuKeluarga->rw }}.</p>
            
            <p>Surat pengantar ini dibuat untuk keperluan: <strong>.................................................................................................................................................................</strong></p>
            
            <p>Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>
        
        <!-- Tanda Tangan -->
        <div class="tanda-tangan">
            <div class="ttd-box">
                <p>Mengetahui,</p>
                <p><strong>Ketua RW {{ $warga->kartuKeluarga->rw }}</strong></p>
                <p class="jabatan">( .................................. )</p>
            </div>
            
            <div class="ttd-box">
                <p>Bandung, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                <p><strong>Ketua RT {{ $warga->kartuKeluarga->rt }}</strong></p>
                <p class="jabatan">( .................................. )</p>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-focus untuk print (opsional)
        // window.onload = function() {
        //     // Uncomment untuk auto-print saat halaman load
        //     // window.print();
        // }
    </script>
</body>
</html>
