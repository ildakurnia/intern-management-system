<?php

namespace App\Http\Controllers;

use App\Services\InstitutionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstitutionSearchController extends Controller
{
    public function __construct(
        private readonly InstitutionService $institutionService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $query = $request->string('q')->toString();

        return response()->json([
            'data' => $this->institutionService->search($query)
                ->map(fn ($institution) => [
                    'id' => $institution->id,
                    'name' => $institution->name,
                ])
                ->values(),
        ]);
    }
}
