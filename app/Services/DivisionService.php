<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DivisionService
{
    /**
     * Get all divisions with counts
     */
    public function getAllDivisions(?string $status = null, ?string $search = null): Collection
    {
        $query = Division::withCount(['activeInterns as interns_count', 'mentors'])->orderBy('name');

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search) {
            $query->where(function ($builder) use ($search) {
                $builder->where('code', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        return $query->get();
    }

    /**
     * Store a new division
     */
    public function createDivision(array $data): Division
    {
        return Division::create($data);
    }

    /**
     * Update an existing division
     */
    public function updateDivision(Division $division, array $data): bool
    {
        return $division->update($data);
    }

    /**
     * Delete a division if it has no members
     */
    public function deleteDivision(Division $division): bool
    {
        if ($division->activeInterns()->exists() || $division->mentors()->exists()) {
            return false;
        }

        return $division->delete();
    }
}
