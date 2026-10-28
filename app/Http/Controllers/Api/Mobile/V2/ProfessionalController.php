<?php

namespace App\Http\Controllers\Api\Mobile\V2;

use App\Http\Controllers\Controller;
use App\Models\Management\Professional;
use Illuminate\Http\JsonResponse;

class ProfessionalController extends Controller
{
    public function index(): JsonResponse
    {
        $professionals = Professional::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Professionals retrieved successfully',
            'data'    => $professionals->map(fn ($p) => [
                'uuid'        => $p->uuid,
                'name'        => $p->name,
            ]),
        ], 200);
    }
}