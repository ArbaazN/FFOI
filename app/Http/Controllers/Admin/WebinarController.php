<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebinarUpcomingSessionCategory;
use App\Models\WebinarUpcomingSession;
use App\Models\Webinar;
use App\Models\WebinarRegistration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class WebinarController extends Controller
{
    public function sessionList(Request $request)
    {
        try {
            $query = WebinarUpcomingSessionCategory::query();
            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('session_name', 'LIKE', "%{$search}%");
                });
            }

            $sessions = $query->orderBy('session_name', 'asc')->paginate(config('pagination.per_page'));

            $sessions->appends($request->only('search'));

            return view('admin.webinar.upcomingsessionlist', compact('sessions'));
        } catch (Exception $e) {
            Log::error("Error fetching sessions: " . $e->getMessage());
            return back()->with('error', 'Failed to load sessions.');
        }
    }

    public function sessionCreate($id = null)
    {
        // return view('admin.webinar.addupcomingsession');
        $session = $id 
            ? WebinarUpcomingSessionCategory::findOrFail($id)
            : new WebinarUpcomingSessionCategory();
        return view('admin.webinar.addupcomingsession', compact('session'));
    }

    public function sessionStore(Request $request)
    {
        $data = $request->validate([
            'session_name'   => 'required|unique:webinar_upcoming_session_category,session_name',
            'heading'        => 'required|string',
            'short_desc'     => 'required|string',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            $data['slug'] = Str::slug($request->session_name);

            $data['image'] = null;
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('webinar/category', 'public');
            }

            WebinarUpcomingSessionCategory::create($data);

            return redirect()
                ->route('webinar.session.list')
                ->with('success', 'Upcoming Session created successfully.');
        } catch (Exception $e) {
            Log::error('Session Create Failed: ' . $e->getMessage(), [
                'data' => $data
            ]);

            return back()
                ->with('error', 'Failed to create Session. Please try again.')
                ->withInput();
        }
    }

    public function sessionUpdate(Request $request,$id)
    {
        $session = WebinarUpcomingSessionCategory::findOrFail($id);
        $data = $request->validate([
            'session_name' => 'required|unique:webinar_upcoming_session_category,session_name,' . $id,
            'heading'      => 'required|string',
            'short_desc'   => 'required|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            $data['slug'] = Str::slug($data['session_name']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('webinar/category', 'public');
            }
            $session->update($data);
            return redirect()
                ->route('webinar.session.list')
                ->with('success', 'Upcoming Session updated successfully.');

        } catch (\Exception $e) {

            Log::error('Session Update Failed: '.$e->getMessage(), [
                'data' => $data,
                'id'   => $id
            ]);

            return back()
                ->with('error', 'Failed to update Session.')
                ->withInput();
        }

    }

    public function sessionDelete(Request $request,$id)
    {
        try {
            $session = WebinarUpcomingSessionCategory::findOrFail($id);
            $session->delete();

            return back()->with('success', 'Deleted Successfully');

        } catch (\Exception $e) {

            Log::error('Session Delete Failed: '.$e->getMessage(), [
                'id' => $id
            ]);

            return back()->with('error', 'Failed to delete.');
        }
    }

    // =============================================================

    public function sessionDetailList(Request $request)
    {
        try {
            $query = WebinarUpcomingSession::with('category');
            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('topic_name', 'LIKE', "%{$search}%");
                });
            }

            $sessions = $query->orderBy('topic_name', 'asc')->paginate(config('pagination.per_page'));
            $sessions->appends($request->only('search'));

            return view('admin.webinar.sessionDetailList', compact('sessions'));
        } catch (Exception $e) {
            Log::error("Error fetching sessions: " . $e->getMessage());
            return back()->with('error', 'Failed to load sessions.');
        }
    }

    public function sessionDetailsAdd($id = null)
    {
        // return view('admin.webinar.addupcomingsession');
        $category = WebinarUpcomingSessionCategory::all();
        if ($id) {
            $session = WebinarUpcomingSession::findOrFail($id);
        } else {
            $session = new WebinarUpcomingSession();
        }
    //    dd($session->date);
        return view('admin.webinar.addsessionDetail', compact('session','category'));
    }

    public function sessionDetailStore(Request $request)
    {
        try{
            $request->validate([
                'session_id' => 'required',
                'topic_name' => 'required',
                'title' => 'required|unique:webinar_upcoming_session,title',
                'date' => 'required',
                'time' => 'required',
                'time_from' => 'required',
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image_attend' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'instructor_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'instructor_logo_image1' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'instructor_logo_image2' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            $bannerImage = null;
            $image = null;
            $imageAttend = null;

            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')->store('webinar/session', 'public');
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('webinar/session', 'public');
            }

            if ($request->hasFile('image_attend')) {
                $imageAttend = $request->file('image_attend')->store('webinar/session', 'public');
            }

            if ($request->hasFile('instructor_image')) {
                $instructor_image = $request->file('instructor_image')->store('webinar/session', 'public');
            }

            if ($request->hasFile('instructor_logo_image1')) {
                $instructor_logo_image1 = $request->file('instructor_logo_image1')->store('webinar/session', 'public');
            }

            if ($request->hasFile('instructor_logo_image2')) {
                $instructor_logo_image2 = $request->file('instructor_logo_image2')->store('webinar/session', 'public');
            }

            WebinarUpcomingSession::create([
                'session_id' => $request->session_id,
                'topic_name' => $request->topic_name,
                'title' => $request->title,
                'slug' => Str::slug($request->topic_name),  // ✅ unique slug
                'subtitle' => $request->subtitle,
                'date' => $request->date,
                'time' => $request->time,
                'time_from' => $request->time_from,
                'mode' => $request->mode,
                'by' => $request->by,

                // Images
                'banner_image' => $bannerImage,
                'image' => $image,
                'image_attend' => $imageAttend,
                'instructor_image' => $instructor_image,
                'instructor_logo_image1' => $instructor_logo_image1,
                'instructor_logo_image2' => $instructor_logo_image2,

                'instructor_name' => $request->instructor_name,
                'instructor_designation' => $request->instructor_designation,
                'instructor_experience' => $request->instructor_experience,
                'instructor_desc' => $request->instructor_desc,

                // Section Headings
                'why_attend_section_heading' => $request->why_attend_section_heading,
                'why_learn_heading' => $request->why_learn_heading,
                'who_attend_heading' => $request->who_attend_heading,
                'career_role_heading' => $request->career_role_heading,
                'how_session_help_heading' => $request->how_session_help_heading,
                'learn_with_ffoi_heading' => $request->learn_with_ffoi_heading,

                // Section disclaimer
                'who_attend_disclaimer' => $request->who_attend_disclaimer,
                'career_role_disclaimer' => $request->career_role_disclaimer,
                'how_session_help_disclaimer' => $request->how_session_help_disclaimer,
               
                // Points (JSON)
                'why_attend_section_points' => json_encode($request->why_attend_section_points ?? []),
                'why_learn_points' => json_encode($request->why_learn_points ?? []),
                'who_attend_points' => json_encode($request->who_attend_points ?? []),
                'career_role_points' => json_encode($request->career_role_points ?? []),
                'how_session_help_points' => json_encode($request->how_session_help_points ?? []),
                'learn_with_ffoi_points' => json_encode($request->learn_with_ffoi_points ?? []),

                // FAQs
                'faqs_question' => json_encode($request->faqs_question ?? []),
                'faqs_answer' => json_encode($request->faqs_answer ?? []),

                'final_CTA_desc' => $request->final_CTA_desc,

            ]);

            return redirect()
                ->route('webinar.session.detail.list')
                ->with('success', 'Session created successfully.');
        } catch (\Exception $e) {

        // Log error for debugging
        Log::error('Session Create Error: '.$e->getMessage());

        return back()
            ->withInput()
            ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function sessionDetailUpdate(Request $request, $id)
    {
        try {
            $session = WebinarUpcomingSession::findOrFail($id);
            $request->validate([
                'session_id' => 'required',
                'topic_name' => 'required',
                'title' => 'required|unique:webinar_upcoming_session,title,' . $session->id,
                'date' => 'required',
                'time' => 'required',
                'time_from' => 'required',
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image_attend' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'instructor_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'instructor_logo_image1' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'instructor_logo_image2' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            if ($session->topic_name != $request->topic_name) {
                $slug = Str::slug($request->topic_name);
                $slugCount = WebinarUpcomingSession::where('slug', 'like', $slug . '%')
                    ->where('id', '!=', $session->id)
                    ->count();
                if ($slugCount > 0) {
                    $slug = $slug . '-' . ($slugCount + 1);
                }

            } else {
                $slug = $session->slug;
            }

            $bannerImage = null;
            $image = null;
            $imageAttend = null;

            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')->store('webinar/session', 'public');
            }else{
                $bannerImage = $session->banner_image;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('webinar/session', 'public');
            }else{
                $bannerImage = $session->image;
            }

            if ($request->hasFile('image_attend')) {
                $imageAttend = $request->file('image_attend')->store('webinar/session', 'public');
            }else{
                $bannerImage = $session->image_attend;
            }

            if ($request->hasFile('instructor_image')) {
                $instructor_image = $request->file('instructor_image')->store('webinar/session', 'public');
            }else{
                $instructor_image = $session->instructor_image;
            }

            if ($request->hasFile('instructor_logo_image1')) {
                $instructor_logo_image1 = $request->file('instructor_logo_image1')->store('webinar/session', 'public');
            }else{
                $instructor_logo_image1 = $session->instructor_logo_image1;
            }

            if ($request->hasFile('instructor_logo_image2')) {
                $instructor_logo_image2 = $request->file('instructor_logo_image2')->store('webinar/session', 'public');
            }else{
                $instructor_logo_image2 = $session->instructor_logo_image2;
            }

            $session->update([
                'session_id' => $request->session_id,
                'topic_name' => $request->topic_name,
                'title' => $request->title,
                'slug' => $slug,
                'subtitle' => $request->subtitle,
                'date' => $request->date,
                'time' => $request->time,
                'time_from' => $request->time_from,
                'mode' => $request->mode,
                'by' => $request->by,

                // Images
                'banner_image' => $bannerImage,
                'image' => $image,
                'image_attend' => $imageAttend,

                'instructor_image' => $instructor_image,
                'instructor_logo_image1' => $instructor_logo_image1,
                'instructor_logo_image2' => $instructor_logo_image2,

                'instructor_name' => $request->instructor_name,
                'instructor_designation' => $request->instructor_designation,
                'instructor_experience' => $request->instructor_experience,
                'instructor_desc' => $request->instructor_desc,

                // Section Headings
                'why_attend_section_heading' => $request->why_attend_section_heading,
                'why_learn_heading' => $request->why_learn_heading,
                'who_attend_heading' => $request->who_attend_heading,
                'career_role_heading' => $request->career_role_heading,
                'how_session_help_heading' => $request->how_session_help_heading,
                'learn_with_ffoi_heading' => $request->learn_with_ffoi_heading,

                // Disclaimers
                'who_attend_disclaimer' => $request->who_attend_disclaimer,
                'career_role_disclaimer' => $request->career_role_disclaimer,
                'how_session_help_disclaimer' => $request->how_session_help_disclaimer,

                // JSON Fields
                'why_attend_section_points' => json_encode($request->why_attend_section_points ?? []),
                'why_learn_points' => json_encode($request->why_learn_points ?? []),
                'who_attend_points' => json_encode($request->who_attend_points ?? []),
                'career_role_points' => json_encode($request->career_role_points ?? []),
                'how_session_help_points' => json_encode($request->how_session_help_points ?? []),
                'learn_with_ffoi_points' => json_encode($request->learn_with_ffoi_points ?? []),

                'faqs_question' => json_encode($request->faqs_question ?? []),
                'faqs_answer' => json_encode($request->faqs_answer ?? []),

                'final_CTA_desc' => $request->final_CTA_desc,
            ]);

            return redirect()
                ->route('webinar.session.detail.list')
                ->with('success', 'Session updated successfully.');

        } catch (\Exception $e) {

            Log::error('Session Update Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while updating.');
        }
    }

    // =================================================================

    public function webinarList(Request $request)
    {
        try {
            $query = Webinar::withCount('registrations');
            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('webinar_type')) {
                $query->where('webinar_type', $request->webinar_type);
            }

            $webinar = $query->orderBy('title', 'asc')->paginate(config('pagination.per_page'));
            $webinar->appends($request->only('search', 'webinar_type'));

            return view('admin.webinar.webinarList', compact('webinar'));
        } catch (Exception $e) {
            Log::error("Error fetching webinar: " . $e->getMessage());
            return back()->with('error', 'Failed to load webinar.');
        }
    }

    public function webinarRegistrationList(Request $request, $id)
    {
        try {
            $webinar = Webinar::findOrFail($id);

            $query = WebinarRegistration::where('webinar_id', $webinar->id);

            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('contact', 'LIKE', "%{$search}%")
                        ->orWhere('city', 'LIKE', "%{$search}%")
                        ->orWhere('highest_qualification', 'LIKE', "%{$search}%")
                        ->orWhere('current_status', 'LIKE', "%{$search}%")
                        ->orWhere('topic_interested_in', 'LIKE', "%{$search}%");
                });
            }

            $registrations = $query->latest()->paginate(config('pagination.per_page'));
            $registrations->appends($request->only('search'));

            return view('admin.webinar.registrationList', compact('webinar', 'registrations'));
        } catch (Exception $e) {
            Log::error("Error fetching webinar registrations: " . $e->getMessage());
            return back()->with('error', 'Failed to load webinar registrations.');
        }
    }

    public function webinarAdd($id = null)
    {
        // return view('admin.webinar.addupcomingsession');
        if ($id) {
            $webinar = Webinar::findOrFail($id);
        } else {
            $webinar = new Webinar();
        }
        
        return view('admin.webinar.webinarAdd', compact('webinar'));
    }

    public function webinarStore(Request $request)
    {
        try {
            $request->validate([
                'webinar_type' => 'required|in:upcoming,other',
                'meeting_link' => 'nullable|url|max:2048',
                'title' => 'required|unique:webinar,title',
                'subtitle' => 'required',
                'short_desc' => 'required',
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            // Generate Unique Slug
            $slug = Str::slug($request->title);

            $bannerImage = null;
            $image = null;

            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')->store('webinar', 'public');
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('webinar', 'public');
            }

            $image_news = [];
            $logo1_news = [];
            $logo2_news = [];

            /* Profile Images */
            if ($request->hasFile('image_new')) {
                foreach ($request->file('image_new') as $file) {
                    if ($file) {
                        $path = $file->store('webinar', 'public');
                        $image_news[] = $path;
                    }
                }
            }

            /* Logo Image1 */
            if ($request->hasFile('logo_image1_new')) {
                foreach ($request->file('logo_image1_new') as $file) {
                    if ($file) {
                        $path = $file->store('webinar', 'public');
                        $logo1_news[] = $path;
                    }
                }
            }

            /* Logo Image2 */
            if ($request->hasFile('logo_image2_new')) {
                foreach ($request->file('logo_image2_new') as $file) {
                    if ($file) {
                        $path = $file->store('webinar', 'public');
                        $logo2_news[] = $path;
                    }
                }
            }

            Webinar::create([
                'webinar_type' => $request->webinar_type,
                'meeting_link' => $request->meeting_link,

                'title' => $request->title,
                'slug' => $slug,
                'subtitle' => $request->subtitle,
                'short_desc' => $request->short_desc,

                // Images
                'banner_image' => $bannerImage,
                'image' => $image,

                // JSON Fields
                'desc' => json_encode($request->desc ?? []),
                'perfect_for_desc' => json_encode($request->perfect_for_desc ?? []),
                'works_desc' => json_encode($request->works_desc ?? []),
                'why_ffoi_desc' => json_encode($request->why_ffoi_desc ?? []),

                'why_ffoi_heading' => $request->why_ffoi_heading,
                'perfect_for_desclaimer' => $request->perfect_for_desclaimer,

                'faqs_question' => json_encode($request->faqs_question ?? []),
                'faqs_answer' => json_encode($request->faqs_answer ?? []),

                'name_new' => json_encode($request->name_new ?? []),
                'Designation_new' => json_encode($request->Designation_new ?? []),
                'Description_new' => json_encode($request->Description_new ?? []),
                'Areaofexperties_new' => json_encode($request->Areaofexperties_new ?? []),
                'linkedIn_new' => json_encode($request->linkedIn_new ?? []),
                'image_new' => json_encode($image_news ?? []),
                'logo_image1_new' => json_encode($logo1_news ?? []),
                'logo_image2_new' => json_encode($logo2_news ?? []),

                'final_CTA_desc' => $request->final_CTA_desc,
            ]);

            return redirect()
                ->route('webinar.list')
                ->with('success', 'Webinar created successfully.');

        } catch (\Exception $e) {

            Log::error('Webinar Store Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function webinarUpdate(Request $request, $id)
    {
        try {

            $webinar = Webinar::findOrFail($id);
            $request->validate([
                'webinar_type' => 'required|in:upcoming,other',
                'meeting_link' => 'nullable|url|max:2048',
                'title' => 'required|unique:webinar,title,' . $webinar->id,
                'subtitle' => 'required',
                'short_desc' => 'required',
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            $slug = Str::slug($request->title);
            $bannerImage = null;
            $image = null;

            $bannerImage = $webinar->banner_image;
            $image = $webinar->image;
            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')->store('webinar', 'public');
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('webinar', 'public');
            }

            $oldImages = json_decode($webinar->image_new ?? '[]', true);
            $oldLogo1 = json_decode($webinar->logo_image1_new ?? '[]', true);
            $oldLogo2 = json_decode($webinar->logo_image2_new ?? '[]', true);
 
            $image_news = [];
            foreach ($request->name_new ?? [] as $index => $name) {
                if ($request->hasFile('image_new') && isset($request->file('image_new')[$index])) {
                    $file = $request->file('image_new')[$index];
                    $path = $file->store('webinar', 'public');
                    $image_news[] = $path;
                } else {
                    $image_news[] = $oldImages[$index] ?? null;
                }
            }

            /* Logo Image1 */
            $logo1_news = [];
            foreach ($request->name_new ?? [] as $index => $name) {
                if ($request->hasFile('logo_image1_new') && isset($request->file('logo_image1_new')[$index])) {
                    $file = $request->file('logo_image1_new')[$index];
                    $path = $file->store('webinar', 'public');
                    $logo1_news[] = $path;
                } else {
                    $logo1_news[] = $oldLogo1[$index] ?? null;
                }
            }

            /* Logo Image2 */
            $logo2_news = [];
            foreach ($request->name_new ?? [] as $index => $name) {
                if ($request->hasFile('logo_image2_new') && isset($request->file('logo_image2_new')[$index])) {
                    $file = $request->file('logo_image2_new')[$index];
                    $path = $file->store('webinar', 'public');
                    $logo2_news[] = $path;
                } else {
                    $logo2_news[] = $oldLogo2[$index] ?? null;
                }
            }

            $webinar->update([
                'webinar_type' => $request->webinar_type,
                'meeting_link' => $request->meeting_link,
                'title' => $request->title,
                'slug' => $slug,
                'subtitle' => $request->subtitle,
                'short_desc' => $request->short_desc,
                'best_of_industries_heading' => $request->best_of_industries_heading,

                // Images
                'banner_image' => $bannerImage,
                'image' => $image,

                // JSON Fields
                'desc' => json_encode($request->desc ?? []),
                'perfect_for_desc' => json_encode($request->perfect_for_desc ?? []),
                'works_desc' => json_encode($request->works_desc ?? []),
                'why_ffoi_desc' => json_encode($request->why_ffoi_desc ?? []),

                'why_ffoi_heading' => $request->why_ffoi_heading,
                'perfect_for_desclaimer' => $request->perfect_for_desclaimer,

                'faqs_question' => json_encode($request->faqs_question ?? []),
                'faqs_answer' => json_encode($request->faqs_answer ?? []),

                'name_new' => json_encode($request->name_new ?? []),
                'Designation_new' => json_encode($request->Designation_new ?? []),
                'Description_new' => json_encode($request->Description_new ?? []),
                'Areaofexperties_new' => json_encode($request->Areaofexperties_new ?? []),
                'linkedIn_new' => json_encode($request->linkedIn_new ?? []),
                'image_new' => json_encode($image_news ?? []),
                'logo_image1_new' => json_encode($logo1_news ?? []),
                'logo_image2_new' => json_encode($logo2_news ?? []),
                
                'final_CTA_desc' => $request->final_CTA_desc,
            ]);

            return redirect()
                ->route('webinar.list')
                ->with('success', 'Webinar updated successfully.');

        } catch (\Exception $e) {

            Log::error('Webinar Update Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}
