<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebinarUpcomingSessionCategory;
use App\Models\WebinarUpcomingSession;
use App\Models\Webinar;
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
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image_attend' => 'nullable|image|mimes:jpg,jpeg,png,webp',
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

            WebinarUpcomingSession::create([

                'session_id' => $request->session_id,
                'topic_name' => $request->topic_name,
                'title' => $request->title,
                'slug' => Str::slug($request->title),  // ✅ unique slug
                'subtitle' => $request->subtitle,
                'date' => $request->date,
                'time' => $request->time,
                'mode' => $request->mode,
                'by' => $request->by,

                // Images
                'banner_image' => $bannerImage,
                'image' => $image,
                'image_attend' => $imageAttend,

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
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image_attend' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            if ($session->title != $request->title) {
                $slug = Str::slug($request->title);
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
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('webinar/session', 'public');
            }

            if ($request->hasFile('image_attend')) {
                $imageAttend = $request->file('image_attend')->store('webinar/session', 'public');
            }

            $session->update([
                'session_id' => $request->session_id,
                'topic_name' => $request->topic_name,
                'title' => $request->title,
                'slug' => $slug,
                'subtitle' => $request->subtitle,
                'date' => $request->date,
                'time' => $request->time,
                'mode' => $request->mode,
                'by' => $request->by,

                // Images
                'banner_image' => $bannerImage,
                'image' => $image,
                'image_attend' => $imageAttend,

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
            $query = Webinar::query();
            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                });
            }

            $webinar = $query->orderBy('title', 'asc')->paginate(config('pagination.per_page'));
            $webinar->appends($request->only('search'));

            return view('admin.webinar.webinarList', compact('webinar'));
        } catch (Exception $e) {
            Log::error("Error fetching webinar: " . $e->getMessage());
            return back()->with('error', 'Failed to load webinar.');
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

            Webinar::create([

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
                'title' => 'required|unique:webinar,title,' . $webinar->id,
                'subtitle' => 'required',
                'short_desc' => 'required',
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            ]);

            $slug = Str::slug($request->title);
            $bannerImage = null;
            $image = null;

            if ($request->hasFile('banner_image')) {
                $bannerImage = $request->file('banner_image')->store('webinar', 'public');
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('webinar', 'public');
            }

            $webinar->update([
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
