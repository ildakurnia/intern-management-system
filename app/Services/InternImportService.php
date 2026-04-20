<?php

namespace App\Services;

use App\Imports\InternsImport;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;

class InternImportService
{
    /**
     * @return array{imported: int, skipped: int, errors: list<string>}
     */
    public function import(UploadedFile $file): array
    {
        $import = new InternsImport();

        Excel::import($import, $file);

        return $import->result();
    }
}
