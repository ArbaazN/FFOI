<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\WebsiteSetting;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:page.view|page.edit')->only(['index', 'show']);
        $this->middleware('permission:page.edit')->only(['update']);
    }

    public function index()
    {
        $settings = WebsiteSetting::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = WebsiteSetting::first();

        if (!$settings) {
            $settings = new WebsiteSetting();
        }

        // Fields except images
        $data = $request->except([
            'logo',
            'favicon',
            'footer_logo',
            'og_image'
        ]);

        // Upload Image Fields Manually (No foreach)
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('website', 'public');
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = $request->file('favicon')->store('website', 'public');
        }

        if ($request->hasFile('footer_logo')) {
            $data['footer_logo'] = $request->file('footer_logo')->store('website', 'public');
        }

        if ($request->hasFile('og_image')) {
            $data['og_image'] = $request->file('og_image')->store('website', 'public');
        }

        // Create or update
        $settings->fill($data);
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}