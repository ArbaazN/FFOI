<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\UtmLinks;
use Illuminate\Http\Request;

class UtmApiController extends Controller
{
    public function getUtmLink($name)
    {
        $utm = UtmLinks::where('name', $name)->first();

        if (!$utm) {
            return response()->json([
                'success' => false,
                'message' => 'UTM link not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'name' => $utm->name,
            'original_url' => $utm->original_url,
            'full_url' => $utm->full_url,
            'utm' => [
                'source'   => $utm->utm_source,
                'medium'   => $utm->utm_medium,
                'campaign' => $utm->utm_campaign,
                'term'     => $utm->utm_term,
                'content'  => $utm->utm_content,
            ],
        ]);
    }
}
