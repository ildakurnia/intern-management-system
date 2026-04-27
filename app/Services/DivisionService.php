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
    public function getAllDivisions(): Collection
    {
        return Division::withCount(['interns', 'mentors'])->latest()->get();
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
        if ($division->interns()->exists() || $division->mentors()->exists()) {
            return false;
        }

        return $division->delete();
    }
}
