<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Household;

use DataTables;
use Validator;
use DB;

class HouseholdController extends Controller
{

    function __conscturct() {
        $this->middleware('auth');
    }

    function index() {
        return view('layouts.penduduk.index');
    }

    function getPenduduk(Request $request) {
        $data = Household::orderBy('head_name','ASC')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $update = '<button class="btn btn-primary btn-sm edit-household" id="btn_edit'.$row->id.'" data-id="'.$row->id.'"><i class="nav-icon fas fa-pen"></i></button>';
                $delete = '<button class="btn btn-danger btn-sm delete-household" id="btn_'.$row->id.'" data-id="'.$row->id.'" data-url="'.route('delete.penduduk',['id' => $row->id]).'"><i class="nav-icon fas fa-trash"></i></button>';
                return $update.'&nbsp;'.$delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function deletePenduduk($id) {
        $Household = Household::findOrFail($id);
        $Household->delete();

        return response()->json(['pesan' => 'Penduduk dihapus']);
    }
}
