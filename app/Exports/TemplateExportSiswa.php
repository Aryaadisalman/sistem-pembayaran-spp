<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateExportSiswa implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Data contoh
        return [
            ['Ahmad Fauzi',    '12345', 'X RPL 1',  '2026-07-14', 'ahmad@gmail.com',  'password123'],
            ['Siti Nurhaliza', '12346', 'XI TAV',   '2025-07-14', 'siti@gmail.com',   'password123'],
            ['Budi Santoso',   '12347', 'XII TKR 1','2024-07-14', 'budi@gmail.com',   'password123'],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'nis', 'kelas', 'tanggal_masuk', 'email', 'password'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1D4ED8']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 15,
            'D' => 18,
            'E' => 30,
            'F' => 15,
        ];
    }
}
