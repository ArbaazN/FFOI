<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\MemberShipController;
use App\Models\MembershipType;
use App\Models\MembershipBenefit;
use App\Models\Membership;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class MemberShipController extends Controller
{
    //=============================== MembershipType ================================

    public function typeList(Request $request)
    {
        try {
            $query = MembershipType::query();
            $members = $query->orderBy('id', 'asc')->paginate(config('pagination.per_page'));

            return view('admin.membership.membershiptypeList', compact('members'));
        } catch (Exception $e) {
            Log::error("Error fetching members: " . $e->getMessage());
            return back()->with('error', 'Failed to load members.');
        }
    }

    public function typeCreate($id = null)
    {
        // return view('admin.webinar.addupcomingsession');
        $member = $id 
            ? MembershipType::findOrFail($id)
            : new MembershipType();
        return view('admin.membership.addmembershipType', compact('member'));
    }

    public function typeStore(Request $request)
    {
        try {
            MembershipType::create([
                'headline' => $request->headline,
                'sub_headline' => $request->sub_headline,
                'purpose' => $request->purpose,
                'short_desc' => $request->short_desc,
                'priviledges_key' => $request->priviledges_key,
               
                // Points (JSON)
                'it_is_for' => json_encode($request->it_is_for ?? []),
                'contribute_through' => json_encode($request->contribute_through ?? []),
                'priviledges' => json_encode($request->priviledges ?? []),
                'planned_access' => json_encode($request->planned_access ?? []),
            ]);

            return redirect()
                ->route('membership.type.list')
                ->with('success', 'Type created successfully.');
        } catch (Exception $e) {
            Log::error('Type Create Failed: ' . $e->getMessage(), [
                'data' => $data
            ]);

            return back()
                ->with('error', 'Failed to Type. Please try again.')
                ->withInput();
        }
    }

    public function typeUpdate(Request $request,$id)
    {
        $type = MembershipType::findOrFail($id);
        
        try {
            $type->update([
                'headline' => $request->headline,
                'sub_headline' => $request->sub_headline,
                'purpose' => $request->purpose,
                'short_desc' => $request->short_desc,
               
                // Points (JSON)
                'it_is_for' => json_encode($request->it_is_for ?? []),
                'contribute_through' => json_encode($request->contribute_through ?? []),
                'priviledges' => json_encode($request->priviledges ?? []),
                'priviledges_key' => json_encode($request->priviledges_key ?? []),
                'planned_access' => json_encode($request->planned_access ?? [])
            ]);

            return redirect()
                ->route('membership.type.list')
                ->with('success', 'Type updated successfully.');

        } catch (\Exception $e) {

            Log::error('Type Update Failed: '.$e->getMessage(), [
                'data' => $data,
                'id'   => $id
            ]);

            return back()
                ->with('error', 'Failed to update Type.')
                ->withInput();
        }

    }

    //=============================== MembershipBenefit ================================

    public function benefitList(Request $request)
    {
        try {
            $query = MembershipBenefit::query();
            $members = $query->orderBy('id', 'asc')->paginate(config('pagination.per_page'));

            return view('admin.membership.membershipBenefitList', compact('members'));
        } catch (Exception $e) {
            Log::error("Error fetching members: " . $e->getMessage());
            return back()->with('error', 'Failed to load members.');
        }
    }

    public function benefitCreate($id = null)
    {
        // return view('admin.webinar.addupcomingsession');
        $member = $id 
            ? MembershipBenefit::findOrFail($id)
            : new MembershipBenefit();
        return view('admin.membership.addmembershipBenefit', compact('member'));
    }

    public function benefitStore(Request $request)
    {
        try {
            MembershipBenefit::create([
                'Benefits' => $request->Benefits,
                'Honorary' => $request->Honorary,
                'Literacy' => $request->Literacy,
                'Student' => $request->Student,
                'Professional' => $request->Professional,
                'Institutional' => $request->Institutional,
                'disclaaimer' => $request->disclaaimer
            ]);

            return redirect()
                ->route('membership.benefit.list')
                ->with('success', 'Benefit created successfully.');
        } catch (Exception $e) {
            Log::error('Benefit Create Failed: ' . $e->getMessage(), [
                'data' => $data
            ]);

            return back()
                ->with('error', 'Failed to Benefit. Please try again.')
                ->withInput();
        }
    }

    public function benefitUpdate(Request $request,$id)
    {
        $type = MembershipBenefit::findOrFail($id);
        
        try {
            $type->update([
                'Benefits' => $request->Benefits,
                'Honorary' => $request->Honorary,
                'Literacy' => $request->Literacy,
                'Student' => $request->Student,
                'Professional' => $request->Professional,
                'Institutional' => $request->Institutional,
                'disclaaimer' => $request->disclaaimer
            ]);

            return redirect()
                ->route('membership.benefit.list')
                ->with('success', 'Benefit updated successfully.');

        } catch (\Exception $e) {

            Log::error('Benefit Update Failed: '.$e->getMessage(), [
                'data' => $data,
                'id'   => $id
            ]);

            return back()
                ->with('error', 'Failed to update Benefit.')
                ->withInput();
        }

    }


    //=============================== Membership ================================

    public function memberList(Request $request)
    {
        try {
            $query = Membership::query();
            $members = $query->orderBy('id', 'asc')->paginate(config('pagination.per_page'));

            return view('admin.membership.membershipList', compact('members'));
        } catch (Exception $e) {
            Log::error("Error fetching members: " . $e->getMessage());
            return back()->with('error', 'Failed to load members.');
        }
    }

    public function memberCreate($id = null)
    {
        // return view('admin.webinar.addupcomingsession');
        $member = $id 
            ? Membership::findOrFail($id)
            : new Membership();
        return view('admin.membership.addmembership', compact('member'));
    }

    public function memberStore(Request $request)
    {
        try {
            Membership::create([
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_key' => $request->meta_key,
                'slug' => Str::slug($request->title),
                'title' => $request->title,
                'short_desc' => $request->short_desc,
                'headline' => $request->headline,
                'sub_headline' => $request->sub_headline,
                'primary_cta' => $request->primary_cta,
                'secondory_cta' => $request->secondory_cta,
                'what_ffoi_membership_desclaimer' => $request->what_ffoi_membership_desclaimer,
                'why_ffoi_created_desclaimer' => $request->why_ffoi_created_desclaimer,
                'category_status_desc' => $request->category_status_desc,
                'life_membership_disclaimer' => $request->life_membership_disclaimer,
                'primary_call_heading' => $request->primary_call_heading,
                'primary_call_desc' => $request->primary_call_desc,
                'primary_call_primary_CTA' => $request->primary_call_primary_CTA,
                'primary_call_secondary_CTA' => $request->primary_call_secondary_CTA,
                'next_build_disclaimer' => $request->next_build_disclaimer,
               
                // Points (JSON)
                'what_ffoi_membership' => json_encode($request->what_ffoi_membership ?? []),
                'what_ffoi_membership_not' => json_encode($request->what_ffoi_membership_not ?? []),
                'why_ffoi_created' => json_encode($request->why_ffoi_created ?? []),
                'why_ffoi_created_progress' => json_encode($request->why_ffoi_created_progress ?? []),
                'membership_status' => json_encode($request->membership_status ?? []),
                'anual_membership' => json_encode($request->anual_membership ?? []),
                'life_membership' => json_encode($request->life_membership ?? []),
                'footer_text' => json_encode($request->footer_text ?? []),
                'next_build' => json_encode($request->next_build ?? []),

            ]);

            return redirect()
                ->route('membership.list')
                ->with('success', 'Membership created successfully.');
        } catch (Exception $e) {
            Log::error('Membership Create Failed: ' . $e->getMessage(), [
                'data' => $request->all
            ]);

            return back()
                ->with('error', 'Failed to Membership. Please try again.')
                ->withInput();
        }
    }

    public function memberUpdate(Request $request,$id)
    {
        $type = Membership::findOrFail($id);
        
        try {
            if ($type->title != $request->title) {
                $slug = Str::slug($request->title);
                $slugCount = Membership::where('slug', 'like', $slug . '%')
                    ->where('id', '!=', $type->id)
                    ->count();
                if ($slugCount > 0) {
                    $slug = $slug . '-' . ($slugCount + 1);
                }
            } else {
                $slug = $type->slug;
            }

            $type->update([
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_key' => $request->meta_key,
                'title' => $request->title,
                'short_desc' => $request->short_desc,
                'headline' => $request->headline,
                'sub_headline' => $request->sub_headline,
                'primary_cta' => $request->primary_cta,
                'secondory_cta' => $request->secondory_cta,
                'what_ffoi_membership_desclaimer' => $request->what_ffoi_membership_desclaimer,
                'why_ffoi_created_desclaimer' => $request->why_ffoi_created_desclaimer,
                'category_status_desc' => $request->category_status_desc,
                'life_membership_disclaimer' => $request->life_membership_disclaimer,
                'primary_call_heading' => $request->primary_call_heading,
                'primary_call_desc' => $request->primary_call_desc,
                'primary_call_primary_CTA' => $request->primary_call_primary_CTA,
                'primary_call_secondary_CTA' => $request->primary_call_secondary_CTA,
                'next_build_disclaimer' => $request->next_build_disclaimer,
               
                // Points (JSON)
                'what_ffoi_membership' => json_encode($request->what_ffoi_membership ?? []),
                'what_ffoi_membership_not' => json_encode($request->what_ffoi_membership_not ?? []),
                'why_ffoi_created' => json_encode($request->why_ffoi_created ?? []),
                'why_ffoi_created_progress' => json_encode($request->why_ffoi_created_progress ?? []),
                'membership_status' => json_encode($request->membership_status ?? []),
                'anual_membership' => json_encode($request->anual_membership ?? []),
                'life_membership' => json_encode($request->life_membership ?? []),
                'footer_text' => json_encode($request->footer_text ?? []),
                'next_build' => json_encode($request->next_build ?? []),

            ]);

            return redirect()
                ->route('membership.list')
                ->with('success', 'Membership updated successfully.');

        } catch (\Exception $e) {

            Log::error('Membership Update Failed: '.$e->getMessage(), [
                'id'   => $id
            ]);

            return back()
                ->with('error', 'Failed to update Membership.')
                ->withInput();
        }

    }
}
