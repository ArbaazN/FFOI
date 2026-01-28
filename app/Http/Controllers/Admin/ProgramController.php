<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use App\Models\Admin\Programs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:program.view|program.create|program.edit')->only(['index', 'show']);
        $this->middleware('permission:program.create')->only(['create', 'store']);
        $this->middleware('permission:program.edit')->only(['edit', 'update']);
        // $this->middleware('permission:program.delete')->only(['destroy']);
    }
    public function index(Request $request)
    {
        try {
            $programs = Programs::query()
                ->leftJoin('pages', function ($join) {
                    $join->on('pages.pageable_id', '=', 'programs.id')
                        ->where('pages.pageable_type', Programs::class);
                })
                ->when(
                    $request->search,
                    fn($q) =>
                    $q->where('programs.name', 'LIKE', "%{$request->search}%")
                )
                ->orderByRaw('pages.menu_order IS NULL')
                ->orderBy('pages.menu_order', 'ASC')
                ->select('programs.*')
                ->paginate(config('pagination.per_page'));

            return view('admin.program.list', compact('programs'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to load programs.');
        }
    }


    public function create()
    {
        try {
            $program = null;
            return view('admin.program.add', compact('program'));
        } catch (Exception $e) {
            Log::error("Error displaying program form: " . $e->getMessage());
            return redirect()->route('programs.index')->with('error', 'Failed to load program form.');
        }
    }

    public function edit(Programs $program)
    {
        try {
            return view('admin.program.add', compact('program'));
        } catch (Exception $e) {
            Log::error("Error displaying campus form: " . $e->getMessage());
            return redirect()->route('campuses.index')
                ->with('error', 'Failed to load campus form.');
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'nullable|in:mms,pgdm',
            'status' => 'required|in:0,1',
            'show_in_menu' => 'nullable|boolean',
            'menu_order'   => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $slugPrefix = match ($data['type'] ?? null) {
                'mms'  => 'mms_programs/',
                'pgdm' => 'pgdm_programs/',
                default => 'programs/',
            };

            $slug = $slugPrefix . Str::slug($data['name']);
            $showInMenu = !empty($data['show_in_menu']);

            $program = Programs::create($data);

            $menuOrder = null;
            if ($showInMenu) {
                $menuOrder = $data['menu_order']
                    ?? (Page::where('slug', 'like', $slugPrefix . '%')
                        ->where('show_in_menu', true)
                        ->max('menu_order') ?? 0) + 1;
            }

            $jsonContent = file_get_contents(resource_path('page-templates/programs.json'));

            $program->pages()->create([
                'title'        => $data['name'],
                'slug'         => $slug,
                'content'      => $jsonContent,
                'status'       => $data['status'] ? 'published' : 'draft',
                'show_in_menu' => $showInMenu,
                'menu_order'   => $menuOrder,
            ]);

            DB::commit();

            return redirect()->route('programs.index')
                ->with('success', 'Program created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'nullable|in:mms,pgdm',
            'status' => 'required|in:0,1',
            'show_in_menu' => 'nullable|boolean',
            'menu_order'   => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $program = Programs::findOrFail($id);
            $page    = $program->pages()->first();

            $slugPrefix = match ($data['type'] ?? null) {
                'mms'  => 'mms_programs/',
                'pgdm' => 'pgdm_programs/',
                default => 'programs/',
            };

            $slug = $slugPrefix . Str::slug($data['name']);
            $showInMenu = !empty($data['show_in_menu']);

            $menuOrder = null;

            if ($showInMenu) {
                if (!empty($data['menu_order'])) {
                    $newOrder = (int) $data['menu_order'];

                    // SHIFT others
                    Page::where('slug', 'like', $slugPrefix . '%')
                        ->where('show_in_menu', true)
                        ->where('id', '!=', $page->id)
                        ->where('menu_order', '>=', $newOrder)
                        ->increment('menu_order');

                    $menuOrder = $newOrder;
                } else {
                    $menuOrder = $page->menu_order;
                }
            }

            $program->update($data);

            $page->update([
                'title'        => $data['name'],
                'slug'         => $slug,
                'status'       => $data['status'] ? 'published' : 'draft',
                'show_in_menu' => $showInMenu,
                'menu_order'   => $menuOrder,
            ]);

            DB::commit();

            return redirect()->route('programs.index')
                ->with('success', 'Program updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->withInput()->with('error', 'Something went wrong.');
        }
    }


    public function duplicateStore(Request $request, $id)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $original = Programs::with('pages')->findOrFail($id);

            $basePath = $original->type ? $original->type . '_programs/' : 'programs/';

            $baseSlug = $basePath . Str::slug($request->new_name);
            $uniqueSlug = $baseSlug;
            $count = 1;

            while (Page::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $baseSlug . '-' . $count++;
            }

            $program = Programs::create([
                'name'   => $request->new_name,
                'type'  => $original->type,
                'status' => $original->status,
            ]);

            if ($original->pages()->exists()) {
                $page = $original->pages()->first();

                $program->pages()->create([
                    'title'   => $request->new_name,
                    'slug'    => $uniqueSlug,
                    'content' => $page->content,
                ]);
            } else {
                $jsonContent = file_get_contents(resource_path('page-templates/programs.json'));

                $program->pages()->create([
                    'title'   => $request->new_name,
                    'slug'    => $uniqueSlug,
                    'content' => $jsonContent,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('programs.index')
                ->with('success', 'Program duplicated successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("Error duplicating program: " . $e->getMessage());

            return back()->with('error', 'Failed to duplicate program.');
        }
    }

    public function destroy($id)
    {
        try {
            $program = Programs::withTrashed()->findOrFail($id);
            $program->pages()->update([
                'status' => 'deleted'
            ]);

            $program->pages()->delete();

            $program->status = 5; // deleted
            $program->save();

            $program->delete();

            return response()->json([
                'success' => true,
                'message' => 'Program deleted successfully'
            ]);
        } catch (\Exception $e) {

            Log::error('Error deleting program: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete program'
            ], 500);
        }
    }
}
