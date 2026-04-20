<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InternImportTemplateExport implements FromArray, ShouldAutoSize, WithHeadings
{
    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'nama',
            'email',
            'nim_nis',
            'tipe_peserta',
            'divisi',
            'tanggal_mulai_magang',
            'tanggal_selesai_magang',
        ];
    }

    /**
     * @return list<list<string>>
     */
    public function array(): array
    {
        return [
            [
                'Budi Santoso',
                'budi@example.com',
                '2023121210',
                'mahasiswa',
                'IT',
                '2026-05-01',
                '2026-08-01',
            ],
            [
                'Siti Aminah',
                'siti@example.com',
                '1234567890',
                'siswa',
                'HR',
                '2026-05-01',
                '2026-08-01',
            ],
        ];
    }
}
