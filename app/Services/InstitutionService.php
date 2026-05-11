<?php

namespace App\Services;

use App\Models\Intern;
use App\Models\Institution;
use Illuminate\Support\Collection;

class InstitutionService
{
    public function getAllowanceEligibleInstitutionId(): ?int
    {
        return Institution::query()
            ->active()
            ->where('is_allowance_eligible', true)
            ->value('id');
    }

    public function requiresBankAccount(?string $type, ?string $institutionId): bool
    {
        if ($type !== config('allowance.eligible_type', 'mahasiswa')) {
            return false;
        }

        if (! $institutionId) {
            return false;
        }

        return (int) $institutionId === (int) $this->getAllowanceEligibleInstitutionId();
    }

    public function search(string $query, int $limit = 8): Collection
    {
        $query = trim($query);

        if ($query === '') {
            return collect();
        }

        $this->syncLegacyManualInstitutions($query);

        return Institution::query()
            ->active()
            ->where('name', 'like', '%'.$query.'%')
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name']);
    }

    public function resolveSelection(?string $institutionId, ?string $manualName): array
    {
        $institution = null;

        if ($institutionId) {
            $institution = Institution::query()
                ->active()
                ->find((int) $institutionId);
        }

        if ($institution) {
            return [
                'institution_id' => $institution->id,
                'institution_manual_name' => null,
                'institution' => $institution->name,
            ];
        }

        $manualName = trim((string) $manualName);

        if ($manualName !== '') {
            $institution = $this->firstOrCreateNormalizedInstitution($manualName);

            return [
                'institution_id' => $institution->id,
                'institution_manual_name' => null,
                'institution' => $institution->name,
            ];
        }

        return [
            'institution_id' => null,
            'institution_manual_name' => null,
            'institution' => null,
        ];
    }

    public function syncLegacyManualInstitutions(?string $query = null): void
    {
        $legacyNames = Intern::query()
            ->whereNotNull('institution_manual_name')
            ->where('institution_manual_name', '!=', '')
            ->when($query, function ($builder, $query) {
                $builder->where('institution_manual_name', 'like', '%'.trim((string) $query).'%');
            })
            ->distinct()
            ->pluck('institution_manual_name');

        foreach ($legacyNames as $legacyName) {
            $normalizedName = trim((string) $legacyName);

            if ($normalizedName === '') {
                continue;
            }

            $institution = $this->firstOrCreateNormalizedInstitution($normalizedName);

            Intern::query()
                ->whereNull('institution_id')
                ->whereRaw('LOWER(institution_manual_name) = ?', [mb_strtolower($normalizedName)])
                ->update([
                    'institution_id' => $institution->id,
                    'institution_manual_name' => null,
                    'institution' => $institution->name,
                ]);
        }
    }

    private function firstOrCreateNormalizedInstitution(string $name): Institution
    {
        $normalizedName = trim($name);

        $institution = Institution::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($normalizedName)])
            ->first();

        if (! $institution) {
            return Institution::query()->create([
                'name' => $normalizedName,
                'is_active' => true,
                'is_allowance_eligible' => false,
            ]);
        }

        if (! $institution->is_active) {
            $institution->forceFill(['is_active' => true])->save();
        }

        return $institution;
    }
}
