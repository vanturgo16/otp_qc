<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
class RoleController extends Controller
{

    // public function __construct()
    // {
    //    $this->middleware(['permission:roles.index|roles.create|roles.edit|roles.delete']);
    //    if(!$this->middleware('auth:sanctum')){
    //     return redirect('/login');
    // }
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        // latest()->when(request()->q, function ($roles) {
        //     $roles = $roles->where('name', 'like', '%' . request()->q . '%');
        // })->paginate(5);

        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retrieve all permissions
        $permissions = Permission::latest()->get();
    
        // Define the permission groups
        $permissionsGroups = [
            'Akunting', 'Warehouse', 'Marketing', 'Purchasing', 'Configuration', 'PPIC', 'Produksi'
        ];
    
        // Group permissions based on predefined groups
        $groupedPermissions = $permissions->groupBy(function($permission) use ($permissionsGroups) {
            foreach ($permissionsGroups as $group) {
                if (Str::contains($permission->name, $group)) {
                    return $group;
                }
            }
            return 'Other';
        });
    
        return view('role.create', compact('groupedPermissions', 'permissionsGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles'
        ]);

        $role = Role::create([
            'name' => $request->input('name')
        ]);

        //assign permission to role
        $role->syncPermissions($request->input('permissions'));

        if ($role) {
            //redirect dengan pesan sukses
            return redirect('/role')->with('status','Data Berhasil Ditambah');
        }
            else{
                return redirect('/role')->with('error','Gagal Ditambah');
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::latest()->get();
        return view('role.edit', compact('role', 'permissions'));
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role = Role::findOrFail($role->id);
        $role->update([
            'name' => $request->input('name')
        ]);

        //assign permission to role
        $role->syncPermissions($request->input('permissions'));

        if ($role) {
            //redirect dengan pesan sukses
            return redirect('/role')->with('status','Data Berhasil Ditambah');
        }
            else{
                return redirect('/role')->with('error','Gagal Ditambah');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions;
        $role->revokePermissionTo($permissions);
        $role->delete();

        if ($role) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
