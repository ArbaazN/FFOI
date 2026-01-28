<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct()
    {
        // Resource permissions
        $this->middleware('permission:user.view|user.edit')->only(['index', 'show']);
        $this->middleware('permission:user.create')->only(['create', 'store']);
        $this->middleware('permission:user.edit')->only(['edit', 'update']);
        // $this->middleware('permission:user.delete')->only(['destroy']);

        // Permission Management (IMPORTANT)
        $this->middleware('permission:user.edit')->only(['editPermission', 'updatePermission']);
    }

    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $users = User::when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
                ->orderBy('id', 'DESC')
                ->paginate(config('pagination.per_page'));

            // Keep search in pagination links
            $users->appends(['search' => $search]);

            return view('admin.users.list', compact('users', 'search'));
        } catch (\Throwable $e) {
            Log::error("User Index Error: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', 'Unable to load user list.');
        }
    }

    public function create()
    {
        abort(403, 'Create user disabled.');
        try {
            return view('admin.users.add');
        } catch (\Throwable $e) {
            Log::error("User Create Page Error: " . $e->getMessage());
            return back()->with('error', 'Unable to open create form.');
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'mobile'    => 'nullable|string|max:20',
                'password'  => 'required|string|min:6',
                'role'      => 'required|string|in:admin,subadmin',
            ]);

            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'mobile'    => $request->mobile,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Throwable $e) {

            Log::error("User Store Error: " . $e->getMessage(), [
                'data' => $request->all(),
            ]);

            return back()->with('error', 'Failed to create user. Try again.')
                ->withInput();
        }
    }

    // public function show(User $user)
    // {
    //     try {
    //         return view('admin.users.show', compact('user'));

    //     } catch (\Throwable $e) {
    //         Log::error("User Show Error: ".$e->getMessage(), [
    //             'user_id' => $user->id,
    //         ]);
    //         return back()->with('error', 'Unable to load user details.');
    //     }
    // }

    public function edit(User $user)
    {
        try {
            return view('admin.users.add', compact('user'));
        } catch (\Throwable $e) {
            Log::error("User Edit Page Error: " . $e->getMessage(), [
                'user_id' => $user->id,
            ]);
            return back()->with('error', 'Unable to open edit form.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $user->id,
                'mobile'   => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6', // not required
                'role'     => 'required|string|in:admin,subadmin',
            ]);

            $data = [
                'name'    => $request->name,
                'email'   => $request->email,
                'mobile'  => $request->mobile,
                'role'    => $request->role,
            ];
            // Update password only if user entered it
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Throwable $e) {

            Log::error("User Update Error: " . $e->getMessage(), [
                'data' => $request->all(),
            ]);

            return back()->with('error', 'Failed to update user. Try again.')
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Throwable $e) {
            Log::error("User Delete Error: " . $e->getMessage(), [
                'user_id' => $user->id,
            ]);

            return back()->with('error', 'Failed to delete user. Try again.');
        }
    }

    public function editPermission(User $user)
    {
        if($user->role === 'admin'){
            abort(419,"Not Allowed");
        }
        // Group permissions by module
        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0];
        });

        // User's permissions (including role permissions)
        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        return view('admin.users.permission', compact('user', 'permissions', 'userPermissions'));
    }

    public function updatePermission(Request $request, User $user)
    {
        $user->syncPermissions($request->permissions ?? []); // assign selected permissions

        return redirect()
            ->route('users.permission', $user->id)
            ->with('success', 'Permissions updated successfully!');
    }
}
