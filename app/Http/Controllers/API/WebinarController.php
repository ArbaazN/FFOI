<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\WebinarRegistrationConfirmationMail;
use Illuminate\Http\Request;
use App\Models\WebinarUpcomingSession;
use App\Models\WebinarUpcomingSessionCategory;
use App\Models\WebinarRegistration;
use App\Models\Webinar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;
use Validator;

class WebinarController extends Controller
{
    public function show(Request $request)
    {
        $type = $request->query('type');

        if ($request->has('upcoming')) {
            $upcoming = strtolower((string) $request->query('upcoming'));

            if (in_array($upcoming, ['1', 'true', 'yes', 'upcoming'], true)) {
                $type = 'upcoming';
            } elseif (in_array($upcoming, ['0', 'false', 'no', 'other'], true)) {
                $type = 'other';
            }
        }

        $query = Webinar::query();

        if (in_array($type, ['upcoming', 'other'], true)) {
            $query->where('webinar_type', $type);
        }

        $webinars = $query->latest()->get();

        if ($webinars->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Webinar not found'
            ], 404);
        }

        // $sessions = WebinarUpcomingSessionCategory::orderBy('created_at', 'asc')->get();
        $sessions = WebinarUpcomingSessionCategory::with('session')
                    ->orderBy('created_at', 'asc')
                    ->get();
                    
        return response()->json([
            'status' => true,
            'webinars' => $webinars->map(fn ($webinar) => $this->formatWebinar($webinar)),

            'upcoming_sessions' => $sessions->map(function ($category) {
                $session = $category->session->first();
                return [
                    'id' => $category->id,
                    'session_name' => $category->session_name,
                    'session_detail_slug' => $session->slug,
                    'slug' => $category->slug ?? null,
                    'heading' => $category->heading,
                    'short_desc' => $category->short_desc,
                    'image_url' => $category->image
                                ? asset('storage/' . $category->image)
                                : null,
                    'from' => $session->time_from ?? null,
                    'to'   => $session->time ?? null,

                    'created_at' => $category->created_at,
                ];
            }),
        ]);
    }

    private function formatWebinar(Webinar $webinar): array
    {
        $names = json_decode($webinar->name_new ?? '[]');
        $designations = json_decode($webinar->Designation_new ?? '[]');
        $descriptions = json_decode($webinar->Description_new ?? '[]');
        $expertise = json_decode($webinar->Areaofexperties_new ?? '[]');
        $linkedin = json_decode($webinar->linkedIn_new ?? '[]');

        $images = json_decode($webinar->image_new ?? '[]');
        $logo1 = json_decode($webinar->logo_image1_new ?? '[]');
        $logo2 = json_decode($webinar->logo_image2_new ?? '[]');

        return [
            'id' => $webinar->id,
            'webinar_type' => $webinar->webinar_type,
            'meeting_link' => $webinar->meeting_link,
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
            'desc' => json_decode($webinar->desc),
            'perfect_for_desc' => json_decode($webinar->perfect_for_desc),
            'perfect_for_desclaimer' => $webinar->perfect_for_desclaimer,
            'works_desc' => json_decode($webinar->works_desc),
            'why_ffoi_heading' => $webinar->why_ffoi_heading,
            'why_ffoi_desc' => json_decode($webinar->why_ffoi_desc),
            'faqs' => collect(json_decode($webinar->faqs_question ?? '[]'))
                ->map(function ($question, $index) use ($webinar) {
                    $answers = json_decode($webinar->faqs_answer ?? '[]');
                    return [
                        'question' => $question,
                        'answer' => $answers[$index] ?? ''
                    ];
                }),
            'best_of_industry_heading' => $webinar->best_of_industries_heading,
            'best_of_industry' => collect($names)->map(function ($name, $index) use ($designations, $descriptions, $expertise, $linkedin, $images, $logo1, $logo2) {
                return [
                    'name' => $name,
                    'designation' => $designations[$index] ?? null,
                    'description' => $descriptions[$index] ?? null,
                    'area_of_expertise' => $expertise[$index] ?? null,
                    'linkedin_url' => $linkedin[$index] ?? null,
                    'profile_image_url' => isset($images[$index])
                        ? asset('storage/' . $images[$index])
                        : null,
                    'logo1_url' => isset($logo1[$index])
                        ? asset('storage/' . $logo1[$index])
                        : null,
                    'logo2_url' => isset($logo2[$index])
                        ? asset('storage/' . $logo2[$index])
                        : null,
                ];
            }),
            'final_CTA_desc' => $webinar->final_CTA_desc,
        ];
    }

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

                'instructor_image' => $session->instructor_image 
                                ? asset('storage/' . $session->instructor_image)
                                : null,
                'instructor_name' => $session->instructor_name,
                'instructor_designation' => $session->instructor_designation,
                'instructor_experience' => $session->instructor_experience,
                'instructor_desc' => $session->instructor_desc,
                'instructor_logo_image1' => $session->instructor_logo_image1 
                                        ? asset('storage/' . $session->instructor_logo_image1)
                                        : null,
                'instructor_logo_image2' => $session->instructor_logo_image2 
                                        ? asset('storage/' . $session->instructor_logo_image2)
                                        : null,


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
                'webinar_id' => 'nullable|exists:webinar,id',
                'webinar_slug' => 'nullable|string|exists:webinar,slug',
                'webinar_type' => 'nullable|in:upcoming,other',
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

            $selectedWebinar = null;

            if ($request->filled('webinar_id')) {
                $selectedWebinar = Webinar::find($request->webinar_id);
            } elseif ($request->filled('webinar_slug')) {
                $selectedWebinar = Webinar::where('slug', $request->webinar_slug)->first();
            } else {
                $selectedType = $request->input('webinar_type', 'upcoming');
                $selectedWebinar = Webinar::query()
                    ->when(in_array($selectedType, ['upcoming', 'other'], true), function ($query) use ($selectedType) {
                        $query->where('webinar_type', $selectedType);
                    })
                    ->latest()
                    ->first();
            }

            $webinar = WebinarRegistration::create([
                'webinar_id' => $selectedWebinar?->id,
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'city' => $request->city,
                'highest_qualification' => $request->highest_qualification,
                'current_status' => $request->current_status,
                'topic_interested_in' => $request->topic_interested_in,
            ]);

            try {
                Mail::to($webinar->email)
                    ->send(new WebinarRegistrationConfirmationMail($webinar, $selectedWebinar));
            } catch (\Exception $mailException) {
                Log::channel('api')->error('Webinar registration mail send error: ' . $mailException->getMessage(), [
                    'registration_id' => $webinar->id,
                    'email' => $webinar->email,
                    'webinar_id' => $selectedWebinar?->id,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Webinar form submitted successfully.',
                'data' => $webinar
            ], 200);
        } catch (\Exception $e) {

            // Log error for debugging
            Log::channel('api')->error('Webinar registration API Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

}
