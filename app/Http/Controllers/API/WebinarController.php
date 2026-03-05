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

                'banner_image_url' => $webinar->banner_image 
                    ? asset('storage/' . $webinar->banner_image) 
                    : null,

                'who_should_attend_image_url' => $webinar->image 
                    ? asset('storage/' . $webinar->image) 
                    : null,

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
            'upcoming_sessions' => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'session_name' => $session->session_name ?? null,
                    'session_detail_slug' => $session->slug ?? null,
                    'slug' => $session->slug ?? null,
                    'heading' => $session->heading ?? null,
                    'short_desc' => $session->short_desc ?? null,
                    'image_url' => $session->image 
                        ? asset('storage/' . $session->image)
                        : null,

                    'created_at' => $session->created_at,
                ];
            }),
        ]);
    }

    // public function sessionDetail($slug)
    // {
    //     $session = WebinarUpcomingSession::where('slug', $slug)->first();

    //     if (!$session) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Session not found'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'session' => $session
    //     ]);
    // }

    public function sessionDetail($slug)
    {
        $session = WebinarUpcomingSession::where('slug', $slug)->first();

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Session not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'session' => [

                'id' => $session->id,
                'slug' => $session->slug,
                'session_id' => $session->session_id,
                'topic_name' => $session->topic_name,
                'title' => $session->title,
                'subtitle' => $session->subtitle,

                'date' => \Carbon\Carbon::parse($session->date)->format('Y-m-d'),

                'time' => $session->time,
                'mode' => $session->mode,
                'by' => $session->by,

                'banner_image_url' => $session->banner_image 
                    ? asset('storage/' . $session->banner_image)
                    : null,

                'image_url' => $session->image 
                    ? asset('storage/' . $session->image)
                    : null,

                'image_attend_url' => $session->image_attend 
                    ? asset('storage/' . $session->image_attend)
                    : null,

                'why_attend_section_heading' => $session->why_attend_section_heading,
                'why_attend_section_points' => json_decode($session->why_attend_section_points),

                'why_learn_heading' => $session->why_learn_heading,
                'why_learn_points' => json_decode($session->why_learn_points),

                'who_attend_heading' => $session->who_attend_heading,
                'who_attend_points' => json_decode($session->who_attend_points),
                'who_attend_disclaimer' => $session->who_attend_disclaimer,

                'career_role_heading' => $session->career_role_heading,
                'career_role_points' => json_decode($session->career_role_points),
                'career_role_disclaimer' => $session->career_role_disclaimer,

                'image_attend_url' => $session->image_attend 
                    ? asset('storage/' . $session->image_attend)
                    : null,

                'how_session_help_heading' => $session->how_session_help_heading,
                'how_session_help_points' => json_decode($session->how_session_help_points),
                'how_session_help_disclaimer' => $session->how_session_help_disclaimer,

                'learn_with_ffoi_heading' => $session->learn_with_ffoi_heading,
                'learn_with_ffoi_points' => json_decode($session->learn_with_ffoi_points),

                'faqs' => collect(json_decode($session->faqs_question))
                    ->map(function ($question, $index) use ($session) {
                        $answers = json_decode($session->faqs_answer);
                        return [
                            'question' => $question,
                            'answer' => $answers[$index] ?? ''
                        ];
                    }),

                'final_CTA_desc' => $session->final_CTA_desc,
            ]
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
