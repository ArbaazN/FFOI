<?php

namespace App\Services\Admin;

use App\Models\Admin\Campus;
use App\Models\Admin\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CampusService
{

    public function saveCampus(array $data, $id = null)
    {
        DB::beginTransaction();
        try {

            $showInMenu = isset($data['show_in_menu']) && $data['show_in_menu'] == 1;

            // Generate slug
            $slug = 'campuses/' . Str::slug($data['name']);
            $data['slug'] = $slug;

            $pageStatus = $data['status'] == 1 ? 'published' : 'draft';

            // -------------------------------
            // UPDATE CAMPUS
            // -------------------------------
            if ($id !== null) {

                $campus = Campus::findOrFail($id);
                $page   = $campus->pages()->first();

                // Slug uniqueness
                $exists = Campus::where('slug', $slug)
                    ->where('id', '!=', $id)
                    ->exists();

                if ($exists) {
                    throw new \Exception("Slug already exists for another campus.");
                }

                // Determine menu_order
                $menuOrder = null;

                if ($showInMenu) {

                    if (!empty($data['menu_order'])) {

                        $newOrder = (int) $data['menu_order'];
                        $oldOrder = $page->menu_order;

                        if ($oldOrder !== $newOrder) {
                            Page::where('slug', 'like', 'campuses/%')
                                ->where('show_in_menu', true)
                                ->where('id', '!=', $page->id)
                                ->where('menu_order', '>=', $newOrder)
                                ->increment('menu_order');
                        }

                        $menuOrder = $newOrder;
                    } elseif ($page && $page->show_in_menu) {
                        $menuOrder = $page->menu_order;
                    } else {
                        $menuOrder = (Page::where('slug', 'like', 'campuses/%')
                            ->where('show_in_menu', true)
                            ->max('menu_order') ?? 0) + 1;
                    }
                } else {
                    $menuOrder = null;
                }

                $campus->update($data);

                $page->update([
                    'title'        => $data['name'],
                    'slug'         => $slug,
                    'status'       => $pageStatus,
                    'show_in_menu' => $showInMenu,
                    'menu_order'   => $menuOrder,
                ]);

                DB::commit();
                return [
                    'status'  => true,
                    'message' => 'Campus updated successfully',
                ];
            }

            // -------------------------------
            // CREATE CAMPUS
            // -------------------------------

            $campus = Campus::create($data);

            $menuOrder = null;
            if ($showInMenu) {
                $menuOrder = Page::where('slug', 'like', 'campuses/%')
                    ->where('show_in_menu', true)
                    ->max('menu_order') ?? 0;
                $menuOrder += 1;
            }

            $jsonContent = file_get_contents(resource_path('page-templates/campus.json'));

            $campus->pages()->create([
                'title'        => $data['name'],
                'slug'         => $slug,
                'content'      => $jsonContent,
                'status'       => $pageStatus,
                'show_in_menu' => $showInMenu,
                'menu_order'   => $menuOrder,
            ]);

            DB::commit();
            return [
                'status'  => true,
                'message' => 'Campus created successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function duplicateCampus($id, $newName)
    {
        DB::beginTransaction();
        try {
            $original = Campus::with('pages')->findOrFail($id);

            // Create slug from NEW NAME
            $slug = 'campuses/' . Str::slug($newName);

            // Make slug unique
            $uniqueSlug = $slug;
            $count = 1;

            while (Campus::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $count++;
            }

            // Create new campus
            $newCampus = Campus::create([
                'name'   => $newName,
                'slug'   => $uniqueSlug,
                'status' => $original->status,
            ]);

            // Duplicate page
            if ($original->pages()->exists()) {
                $page = $original->pages()->first();

                $newCampus->pages()->create([
                    'title'   => $newName,
                    'slug'    => $uniqueSlug,
                    'content' => $page->content, // copy old content
                ]);
            }

            DB::commit();

            return [
                'status' => true,
                'message' => 'Campus duplicated successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
