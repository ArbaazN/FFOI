<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Campus;
use App\Services\Admin\CampusService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CampusController extends Controller
{
    protected $campusService;

    public function __construct(CampusService $campusService)
    {
        $this->campusService = $campusService;

        $this->middleware('permission:campus.view|campus.create|campus.edit')->only(['index', 'show']);
        $this->middleware('permission:campus.create')->only(['create', 'store', 'duplicate']);
        $this->middleware('permission:campus.edit')->only(['edit', 'update']);
        // $this->middleware('permission:campus.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $campuses = Campus::query()
                ->leftJoin('pages', function ($join) {
                    $join->on('pages.pageable_id', '=', 'campuses.id')
                        ->where('pages.pageable_type', Campus::class);
                })
                ->when($search, function ($query, $search) {
                    $query->where('campuses.name', 'LIKE', "%{$search}%");
                })
                ->orderByRaw('pages.menu_order IS NULL') // NULLs last
                ->orderBy('pages.menu_order', 'ASC')
                ->select('campuses.*')
                ->paginate(config('pagination.per_page'));

            $campuses->appends(['search' => $search]);

            return view('admin.campuses.list', compact('campuses', 'search'));
        } catch (\Exception $e) {
            Log::error("Error fetching campuses: " . $e->getMessage(), [
                'search' => $request->search
            ]);

            return redirect()->back()->with('error', 'Failed to load campuses.');
        }
    }


    public function create()
    {
        try {
            return view('admin.campuses.add');
        } catch (Exception $e) {
            Log::error("Error displaying campus form: " . $e->getMessage());
            return redirect()->route('campuses.index')
                ->with('error', 'Failed to load campus form.');
        }
    }

    public function edit(Campus $campus)
    {
        try {
            return view('admin.campuses.add', compact('campus'));
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
            'status' => 'required|in:0,1',
            'show_in_menu' => 'nullable|boolean',
            'menu_order' => 'nullable|integer|min:0',
        ]);

        try {
            $result = $this->campusService->saveCampus($data);

            return redirect()
                ->route('campuses.index')
                ->with('success', $result['message']);
        } catch (Exception $e) {
            Log::error("Error saving campus: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while saving the campus.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'show_in_menu' => 'nullable|boolean',
            'menu_order' => 'nullable|integer|min:0',
        ]);

        try {
            $result = $this->campusService->saveCampus($data, $id);

            return redirect()
                ->route('campuses.index')
                ->with('success', 'Campus updated successfully.');
        } catch (Exception $e) {
            Log::error("Error updating campus: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update campus.');
        }
    }

    // public function duplicateForm($id)
    // {
    //     $campus = Campus::findOrFail($id);
    //     return view('admin.campuses.list', compact('campus'));
    // }

    public function duplicateStore(Request $request, $id)
    {
        $request->validate([
            'new_name' => 'required|string|max:255',
        ]);

        try {
            $result = $this->campusService->duplicateCampus($id, $request->new_name);

            return redirect()
                ->route('campuses.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error("Duplicate Campus Error: " . $e->getMessage());
            return back()->with('error', 'Failed to duplicate campus.');
        }
    }

    public function destroy($id)
    {
        try {
            $campus = Campus::withTrashed()->findOrFail($id);
            $campus->pages()->update([
                'status' => 'deleted'
            ]);

            $campus->pages()->delete();
            $campus->status = 5; // deleted
            $campus->save();

            $campus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Campus deleted successfully'
            ]);
        } catch (\Exception $e) {

            Log::error('Error deleting campus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete campus'
            ], 500);
        }
    }
}
