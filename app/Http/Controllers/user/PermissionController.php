<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['permission:permissions.index']);
    //     if(!$this->middleware('auth:sanctum')){
    //         return redirect('/login');
    //     }
  
    // } 

    public function index()
    {
        $permissions = Permission::get();
        return view('permission.index', compact('permissions'));
      
    }


    public function create()
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required'   
        ]);
        $permission = Permission::create([
            'name'      => $request->input('name'),  
        ]);
       
        if($permission){
            return redirect('/permission')->with('status','Data Berhasil Ditambah');}
            else{
                return redirect('/permission')->with('error','Gagal Ditambah');
            }
    }
}
