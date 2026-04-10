<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberShip;
use App\Models\MembershipBenefit;
use App\Models\MembershipType;

use Illuminate\Support\Facades\Log;

class MembershipController extends Controller
{
    public function membershipDetail()
    {
        try {

           $membership = MemberShip::get();
            $features   = MembershipBenefit::get();
            $plans      = MembershipType::get();

            return response()->json([
                'status' => true,
                'data'   => [
                    'membership' => $membership,
                    'Benefit'   => $features,
                    'Type'      => $plans,
                ],
            ], 200);

        } catch (\Throwable $e) {

            Log::channel('api')->error('Membership API Error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while fetching membership data.',
            ], 500);
        }
    }
}
