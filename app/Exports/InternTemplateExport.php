<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InternTemplateExport implements FromArray, WithHeadings
{
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

    public function array(): array
    {
        return [[
            'Budi Santoso',
            'budi.santoso@example.com',
            '23123456',
            'mahasiswa',
            'IT',
            now()->startOfMonth()->format('Y-m-d'),
            now()->startOfMonth()->addMonths(3)->format('Y-m-d'),
        ]];
    }
}
