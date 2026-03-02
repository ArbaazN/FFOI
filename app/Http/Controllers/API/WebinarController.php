<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebinarUpcomingSession;
use App\Models\WebinarUpcomingSessionCategory;
use App\Models\WebinarRegistration;
use App\Models\Webinar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Validator;

class WebinarController extends Controller
{
    public function show()
    {
        $webinar = Webinar::latest()->first(); // Only one saved

        if (!$webinar) {
            return response()->json([
                'status' => false,
                'message' => 'Webinar not found'
            ], 404);
        }

        $sessions = WebinarUpcomingSessionCategory::orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => true,
            'webinar' => [
                'id' => $webinar->id,
                'title' => $webinar->title,
                'subtitle' => $webinar->subtitle,
                'short_desc' => $webinar->short_desc,
                'slug' => $webinar->slug,

                // Decode JSON Fields
                'desc' => json_decode($webinar->desc),
                'perfect_for_desc' => json_decode($webinar->perfect_for_desc),
                'perfect_for_desclaimer' => $webinar->perfect_for_desclaimer,
                'works_desc' => json_decode($webinar->works_desc),
                'why_ffoi_heading' => $webinar->why_ffoi_heading,
                'why_ffoi_desc' => json_decode($webinar->why_ffoi_desc),
                'faqs' => collect(json_decode($webinar->faqs_question))
                            ->map(function ($question, $index) use ($webinar) {
                                $answers = json_decode($webinar->faqs_answer);
                                return [
                                    'question' => $question,
                                    'answer' => $answers[$index] ?? ''
                                ];
                            }),
                'final_CTA_desc' => $webinar->final_CTA_desc,
            ],
            'upcoming_sessions' => $sessions
        ]);
    }

    public function sessionDetail($id)
    {
        $session = WebinarUpcomingSession::where('session_id', $id)->first();

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'session' => $session
        ]);
    }


    public function saveWebinar(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'contact' => 'required|string|max:20',
                'city' => 'nullable|string|max:100',
                'highest_qualification' => 'nullable|string|max:255',
                'current_status' => 'nullable|in:Student,Fresher,Working',
                'topic_interested_in' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            $webinar = WebinarRegistration::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Webinar form submitted successfully.',
                'data' => $webinar
            ], 200);
        } catch (\Exception $e) {

            // Log error for debugging
            Log::error('Webinar registration API Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

}
