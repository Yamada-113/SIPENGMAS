<table>
    <thead>
        {{-- 1. SPACER ATAS (Harus ada 10 <td> kosong agar Excel bisa mendeteksi lebar kolom) --}}
        @for ($i = 0; $i < 9; $i++)
            <tr>
                <td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td>
            </tr>
        @endfor

        {{-- 2. JUDUL LAPORAN (Perhatikan colspan="10") --}}
        <tr>
            <td colspan="10" style="font-weight: bold; font-size: 16px; text-align: center; vertical-align: middle;">
                UNIVERSITAS YARSI
            </td>
        </tr>
        <tr>
            <td colspan="10" style="font-weight: bold; font-size: 14px; text-align: center; vertical-align: middle;">
                REKAPITULASI PROPOSAL PENGABDIAN KEPADA MASYARAKAT
            </td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: center; vertical-align: middle;">
                Status: {{ $status }} | Tahun: {{ $year }}
            </td>
        </tr>

        <tr>
            <td colspan="10" style="height: 10px;"></td>
        </tr>

        {{-- 3. HEADER TABEL (Total 10 Kolom) --}}
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Dosen Pengusul</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">NIDN</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Judul Proposal</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Dana (Rp)</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Tanggal Masuk</th>
            
            @if ($status == 'Ditolak')
                <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Alasan Penolakan</th>
            @else
                <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Status</th>
            @endif

            {{-- 3 Kolom Luaran (Dipindah ke Paling Kanan) --}}
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Artikel</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Sertifikat</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">HKI</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $index => $row)
            @php
                // Hitung Total Dana
                $totalDana = 0;
                if ($row->biaya) {
                    $totalDana =
                        ($row->biaya->honor_output ?? 0) +
                        ($row->biaya->belanja_non_operasional ?? 0) +
                        ($row->biaya->bahan_habis_pakai ?? 0) +
                        ($row->biaya->transportasi ?? 0) +
                        ($row->biaya->lain_lain ?? 0);
                }

                // Hitung Jumlah Luaran
                $jmlArtikel = 0;
                $jmlSertif = 0;
                $jmlHKI = 0;

                if ($row->lampiran && $row->lampiran->count() > 0) {
                    $jmlArtikel = $row->lampiran->where('kategori', 'artikel')->count();
                    $jmlSertif  = $row->lampiran->where('kategori', 'sertifikat')->count();
                    $jmlHKI     = $row->lampiran->where('kategori', 'hki')->count();
                }
            @endphp
            
            <tr>
                {{-- 1. No --}}
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">{{ $index + 1 }}</td>

                {{-- 2. Dosen --}}
                <td style="border: 1px solid #000000; vertical-align: top;">
                    {{ $row->user->name ?? 'User Tidak Ditemukan' }}
                </td>

                {{-- 3. NIDN --}}
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                    {{ isset($row->user->nidn) ? "'" . $row->user->nidn : '-' }}
                </td>

                {{-- 4. Judul --}}
                <td style="border: 1px solid #000000; vertical-align: top; word-wrap: break-word;">
                    {{ $row->identitas->judul ?? 'Judul Belum Diisi' }}
                </td>

                {{-- 5. Dana --}}
                <td style="border: 1px solid #000000; text-align: right; vertical-align: top;">
                    {{ $totalDana }}
                </td>

                {{-- 6. Tanggal Masuk --}}
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                    {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : '-' }}
                </td>

                {{-- 7. Status --}}
                @if ($status == 'Ditolak')
                    <td style="border: 1px solid #000000; color: #ff0000; vertical-align: top;">
                        {{ $row->feedback ?? '-' }}
                    </td>
                @else
                    <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                        {{ $status }}
                    </td>
                @endif

                {{-- 8. Artikel --}}
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                    {{ $jmlArtikel }}
                </td>

                {{-- 9. Sertifikat --}}
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                    {{ $jmlSertif }}
                </td>

                {{-- 10. HKI --}}
                <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                    {{ $jmlHKI }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>