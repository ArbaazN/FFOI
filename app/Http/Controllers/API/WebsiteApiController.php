<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use App\Models\Admin\WebsiteSetting;
use Illuminate\Http\Request;

class WebsiteApiController extends Controller
{
    public function index()
    {
        try {
            $settings = WebsiteSetting::first();

            $settings->makeHidden([
                'id',
                'city',
                'state',
                'country',
                'pincode',
                'address',
                'linkedin_company_id',
                'linkedin_share_enable',
                'admission_form_link',
                'maintenance_mode',
                'enable_registration',
                'is_active',
                'created_by',
                'updated_by',
                'telegram',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

            $pages = Page::where('status', 'published')
                ->where('show_in_menu', true)
                ->orderBy('menu_order', 'ASC')
                ->get();

            if (!$settings ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Settings not found'
                ], 404);
            }

            $mapPage = fn($p) => [
                "name"   => $p->title,
                "href"   => "/" . $p->slug,
                "target" => $p->target ?? "_self"
            ];

            $mapPage1 = fn($p) => [
                "title"   => $p->title,
                "href"   => "/" . $p->slug,
                "target" => $p->target ?? "_self"
            ];

            // Group pages by slug prefix
            $group = fn($prefix) =>
            $pages->filter(fn($p) => str_starts_with($p->slug, $prefix))
                ->map($mapPage)
                ->values();

            // Helper for single page
            $page = fn($slug) => $pages->firstWhere("slug", $slug);

            // Build menu JSON
            $menu = [
                [
                    "title"  => "Home",
                    "target" => "_self",
                    "href"   => "/home",
                ],
                [
                    "title"  => "About Us",
                    "target" => "_self",
                    "href"   => "/about-us",
                ],
                [
                    "title"  => "Education Series",
                    "target" => "_self",
                    "href"   => "/education-series",
                ],
                [
                    "title"  => "Programs",
                    "target" => "_self",
                    "dropdownData" => [
                        [
                            "name" => "Degree Programs",
                            "subDropdownData" => $group("pgdm_programs/")
                        ],
                        [
                            "name" => "Certificate Programs",
                            "subDropdownData" => $group("mms_programs/")
                        ]
                    ]
                ],
                [
                    "title"  => "Workshop",
                    "target" => "_self",
                    "href"   => "/workshop",
                ],
                [
                    "title"  => "Membership",
                    "target" => "_self",
                    "href"   => "/membership",
                ],
                // Single page direct links
                $mapPage1($page("partner-with-us")),
                $mapPage1($page("blogs")),
            ];

            return response()->json([
                'status'   => true,
                'message'  => 'Website settings retrieved successfully',
                'settings' => $settings,
                'menu'     => $menu,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
