<?php

namespace App\Exports;

use App\Models\Proposal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapProposalExport implements FromView, WithEvents, WithDrawings
{
    protected $year;
    protected $status;

    public function __construct($year, $status)
    {
        $this->year = $year;
        $this->status = $status;
    }

    // 1. QUERY DATA
    public function view(): View
    {
        $query = Proposal::with(['user', 'identitas', 'biaya'])
            ->where('tahun_pelaksanaan', $this->year);

        if ($this->status == 'didanai') {
            $query->where('status_progress', 4);
        } elseif ($this->status == 'ditolak') {
            $query->where('status_progress', 99);
        } else {
            // Default: Proses
            $query->where('status_progress', '<', 4)
                ->where('status_progress', '!=', 99);
        }

        return view('admin.admin_export_rekapitulasi', [
            'data'   => $query->latest()->get(),
            'year'   => $this->year,
            'status' => ucfirst($this->status)
        ]);
    }

    // 2. SETTING GAMBAR (LOGO)
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Universitas YARSI');
        $drawing->setDescription('Logo');

        $path = public_path('images/logoyarsi.jpg');
        if (!file_exists($path)) {
            $path = public_path('images/logo_yarsi.png');
            if (!file_exists($path)) return [];
        }
        $drawing->setPath($path);

        // Logo Tinggi 150 (Pas untuk area 9 baris)
        $drawing->setHeight(150);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(15);

        return [$drawing];
    }

    // 3. STYLING & MERGING CELLS
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // A. MERGE CELLS AREA LOGO (Baris 1-9)
                $sheet->mergeCells('A1:J9');

                // B. MERGE AREA JUDUL (Baris 10, 11, 12)
                $sheet->mergeCells('A10:J10'); // Universitas Yarsi
                $sheet->mergeCells('A11:J11'); // Rekapitulasi...
                $sheet->mergeCells('A12:J12'); // Status...

                // C. ATUR LEBAR KOLOM
                $sheet->getColumnDimension('A')->setWidth(8);   // No
                $sheet->getColumnDimension('B')->setWidth(25);  // Dosen
                $sheet->getColumnDimension('C')->setWidth(15);  // NIDN
                $sheet->getColumnDimension('D')->setWidth(65);  // Judul
                $sheet->getColumnDimension('E')->setWidth(20);  // Dana
                $sheet->getColumnDimension('F')->setWidth(15);  // Tanggal
                $sheet->getColumnDimension('G')->setWidth(25);  // Status
                $sheet->getColumnDimension('H')->setWidth(10);  // Artikel
                $sheet->getColumnDimension('I')->setWidth(12);  // Sertifikat
                $sheet->getColumnDimension('J')->setWidth(10);  // HKI

                // D. STYLE HEADER TABEL (Baris 14)
                // (9 baris logo + 3 baris judul + 1 spasi = 13 baris terpakai, Header di 14)
                $headerRange = 'A14:J14';
                $headerStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFCCCCCC'], // Abu-abu
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ];
                $sheet->getStyle($headerRange)->applyFromArray($headerStyle);

                // E. STYLE DATA (Mulai Baris 15)
                $highestRow = $sheet->getHighestRow();
                if ($highestRow >= 15) {
                    $contentStyle = [
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                        ],
                    ];
                    $sheet->getStyle('A15:J' . $highestRow)->applyFromArray($contentStyle);

                    // Wrap Text Judul (Kolom D)
                    $sheet->getStyle('D15:D' . $highestRow)->getAlignment()->setWrapText(true);

                    // Rata Tengah (No, NIDN, Tanggal)
                    $centerColumns = ['A', 'C', 'F', 'G', 'H', 'I', 'J'];
                    foreach ($centerColumns as $col) {
                        $sheet->getStyle($col . '15:' . $col . $highestRow)
                              ->getAlignment()
                              ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    // Format Rupiah (Kolom E)
                    $sheet->getStyle('E15:E' . $highestRow)
                        ->getNumberFormat()
                        ->setFormatCode('_("Rp"* #,##0_);_("Rp"* (#,##0);_("Rp"* "-"_);_(@_)');
                }
            },
        ];
    }
}
