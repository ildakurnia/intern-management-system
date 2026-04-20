<?php

namespace App\Imports;

use App\Models\Division;
use App\Models\Intern;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InternsImport implements ToCollection, WithHeadingRow
{
    private int $imported = 0;

    private int $skipped = 0;

    /**
     * @var list<string>
     */
    private array $errors = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $name = $this->value($row, ['nama', 'name']);
            $email = $this->value($row, ['email']);
            $identifier = $this->value($row, ['nim_nis', 'nomor_induk', 'nomor_induk_peserta', 'nim', 'nis']);
            $type = strtolower($this->value($row, ['tipe_peserta', 'type', 'jenis_peserta']));
            $divisionName = $this->value($row, ['divisi', 'division']);
            $startDate = $this->parseDate($this->value($row, ['tanggal_mulai_magang', 'tanggal_mulai', 'start_date']));
            $endDate = $this->parseDate($this->value($row, ['tanggal_selesai_magang', 'tanggal_selesai', 'end_date']));

            if (! $name || ! $email || ! $identifier || ! in_array($type, ['siswa', 'mahasiswa'], true) || ! $divisionName || ! $startDate || ! $endDate) {
                $this->skipped++;
                $this->errors[] = "Baris {$line} dilewati karena kolom wajib belum lengkap.";
                continue;
            }

            $division = Division::firstOrCreate(
                ['code' => $this->divisionCode($divisionName)],
                ['name' => $divisionName, 'is_active' => true],
            );

            $intern = Intern::firstOrNew(['email' => $email]);

            $intern->fill([
                'division_id' => $division->id,
                'name' => $name,
                'type' => $type,
                'nim' => $type === 'mahasiswa' ? $identifier : null,
                'nis' => $type === 'siswa' ? $identifier : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            if (! $intern->exists || $intern->user_id === null) {
                $intern->registration_status = 'pending';
            }

            $intern->save();

            $this->imported++;
        }
    }

    /**
     * @return array{imported: int, skipped: int, errors: list<string>}
     */
    public function result(): array
    {
        return [
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
        ];
    }

    /**
     * @param Collection<int|string, mixed> $row
     * @param list<string> $keys
     */
    private function value(Collection $row, array $keys): string
    {
        foreach ($keys as $key) {
            $value = $row->get($key);

            if ($value !== null && $value !== '') {
                return trim((string) $value);
            }
        }

        return '';
    }

    private function divisionCode(string $name): string
    {
        return Str::of($name)->upper()->replaceMatches('/[^A-Z0-9]+/', '_')->trim('_')->limit(20, '')->toString();
    }

    private function parseDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::create(1899, 12, 30)->addDays((int) $value)->toDateString();
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}
