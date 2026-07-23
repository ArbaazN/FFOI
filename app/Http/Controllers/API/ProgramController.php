<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\Programs;
use Illuminate\Support\Facades\Log;

class ProgramController extends Controller
{
    public function index()
    {
        try {
            $programs = Programs::active()
                ->with(['pages' => function ($q) {
                    $q->select('id', 'pageable_id', 'pageable_type', 'slug', 'title', 'product_code', 'status', 'menu_order')
                        ->orderByRaw('menu_order IS NULL')
                        ->orderBy('menu_order', 'ASC');
                }])
                ->orderBy('name', 'ASC')
                ->get();

            $mapProgram = function (Programs $program) {
                $page = $program->pages->first();

                return [
                    'id'                  => $program->id,
                    'name'                => $program->name,
                    'type'                => $program->type,
                    'description'         => $program->description,
                    'product_code'        => $program->product_code ?? $page?->product_code,
                    'product_description' => $program->product_description,
                    'product_image'       => $program->product_image
                        ? asset('storage/' . $program->product_image)
                        : null,
                    'slug'                => $page?->slug,
                    'status'              => (int) $program->status,
                ];
            };

            $degree = $programs
                ->where('type', 'degree')
                ->values()
                ->map($mapProgram);

            $certificate = $programs
                ->where('type', 'certificate')
                ->values()
                ->map($mapProgram);

            return response()->json([
                'status' => true,
                'data'   => [
                    'degree'      => $degree,
                    'certificate' => $certificate,
                ],
            ], 200);
        } catch (\Throwable $e) {
            Log::channel('api')->error('Programs API Error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while fetching programs.',
            ], 500);
        }
    }
}
