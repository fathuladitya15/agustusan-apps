<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function dataUsers(Request $request) {
        $data = User::all();
        // $data = User::find(1);
        // dd($data->roles->first()->name);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $update = '<button class="btn btn-primary btn-sm edit-event" id="btn_edit'.$row->id.'" data-id="'.$row->id.'"><i class="nav-icon fas fa-solid fa-pen"></i></button>';
                $delete = '<button class="btn btn-danger btn-sm delete-event" id="btn_'.$row->id.'" data-id="'.$row->id.'"><i class="nav-icon fas fa-trash"></i></button>';


                return $update.'&nbsp;'.$delete;
            })
            ->addColumn('roles',function($row) {
                $roles      = $row->roles->first()->name;
                $replace    = str_replace('_',' ',$roles);
                $toTitle    = Str::title($replace);
                return $toTitle;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function getRoles(Request $request) {
        $roles = Role::all();

        $searchTerm = $request->input('q');

        if ($searchTerm) {
            $data = Role::where('name', 'like', "%$searchTerm%")->get(['id', 'head_name']);
        } else {
            $data = Role::all(['id', 'name']);
        }

        return response()->json($data);
    }
}
