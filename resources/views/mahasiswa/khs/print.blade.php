<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KHS - {{ $mahasiswa->nim }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: white;
            color: black;
            padding: 3rem;
        }

        .header-print {
            border-bottom: 3px double #000;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .university-title {
            font-size: 1.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doc-title {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 2rem;
            text-decoration: underline;
        }

        .table th {
            background-color: #f8fafc !important;
            color: black !important;
            border: 1px solid #cbd5e1 !important;
        }

        .table td {
            border: 1px solid #cbd5e1 !important;
        }

        .signature-section {
            margin-top: 4rem;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header / Kop Surat -->
        <div class="header-print text-center">
            <h3 class="university-title mb-1">KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h3>
            <h4 class="fw-bold mb-1">POLITEKNIK NEGERI BENGKALIS</h4>
            <p class="text-muted mb-0">Jl. Bathin Alam, Sungai Alam, Bengkalis, Riau | Telp: (0766) 24566</p>
        </div>

        <div class="doc-title">KARTU HASIL STUDI (KHS)</div>

        <!-- Student Info -->
        <div class="row mb-4" style="font-size: 0.95rem;">
            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="30%"><strong>Nama</strong></td>
                        <td>: {{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIM</strong></td>
                        <td>: <code>{{ $mahasiswa->nim }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan</strong></td>
                        <td>: {{ $mahasiswa->jurusan }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="40%"><strong>Tahun Akademik</strong></td>
                        <td>: 2025/2026</td>
                    </tr>
                    <tr>
                        <td><strong>Semester</strong></td>
                        <td>: Ganjil</td>
                    </tr>
                    <tr>
                        <td><strong>Angkatan</strong></td>
                        <td>: {{ $mahasiswa->angkatan }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Grades Table -->
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode MK</th>
                    <th class="text-start">Mata Kuliah</th>
                    <th width="10%">SKS</th>
                    <th width="12%">Nilai Angka</th>
                    <th width="12%">Nilai Huruf</th>
                    <th width="10%">Bobot</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($khs as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><code>{{ $item->mataKuliah->kode_mk }}</code></td>
                        <td class="text-start fw-bold">{{ $item->mataKuliah->nama_mk }}</td>
                        <td>{{ $item->mataKuliah->sks }}</td>
                        <td>{{ $item->nilai ? $item->nilai->nilai_angka : '-' }}</td>
                        <td class="fw-bold">{{ $item->nilai ? $item->nilai->nilai_huruf : '-' }}</td>
                        <td>
                            @if($item->nilai)
                                @php
                                    $pt = 0;
                                    switch($item->nilai->nilai_huruf) {
                                        case 'A': $pt = 4; break;
                                        case 'B': $pt = 3; break;
                                        case 'C': $pt = 2; break;
                                        case 'D': $pt = 1; break;
                                        case 'E': $pt = 0; break;
                                    }
                                    echo $pt * $item->mataKuliah->sks;
                                @endphp
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada data nilai akademik.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        <div class="row mt-4" style="font-size: 0.95rem;">
            <div class="col-8">
                <div class="border p-3" style="border-radius: 8px; background-color: #fafafa;">
                    <strong>Keterangan Bobot Nilai:</strong><br>
                    A = 4 | B = 3 | C = 2 | D = 1 | E = 0
                </div>
            </div>
            <div class="col-4">
                <table class="table table-bordered text-center align-middle">
                    <tr>
                        <td class="fw-bold bg-light">Jumlah SKS</td>
                        <td class="fw-bold">{{ $totalSks }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold bg-light">IP Semester</td>
                        <td class="fw-bold text-primary">{{ number_format($ips, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Signatures -->
        <div class="row signature-section" style="font-size: 0.95rem;">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Bengkalis, {{ date('d F Y') }}<br><strong>Wakil Direktur Bidang Akademik,</strong></p>
                <p class="mb-0 fw-bold"><u>Dr. Ir. H. Hermawan, M.T.</u></p>
                <small class="text-muted">NIP. 19741021 200212 1 002</small>
            </div>
        </div>

    </div>

    <!-- Print Button (Only visible on screen) -->
    <div class="text-center mt-5 no-print">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
            Cetak Ulang Dokumen
        </button>
        <button onclick="window.close()" class="btn btn-secondary px-4 py-2 ms-2" style="border-radius: 8px;">
            Tutup Halaman
        </button>
    </div>

    <script>
        // Trigger print dialog automatically when loaded
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
    </script>
</body>
</html>
