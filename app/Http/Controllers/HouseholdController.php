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

    function store(Request $request) {
        $rules = [
            'head_name' => 'required',
            'address'   => 'required',
            'phone'     => 'required',
            'member_count' => 'required',
        ];

        $message = [
            'head_name.required' => 'Nama kepala keluarga wajib diisi.',
            'address.required'  => 'Alamat Wajib diisi.',
            'phone.required'    => 'Nomor telepon wajib diisi.',
            'member_count.required'      => 'Total Anggota keluarga wajib diisi.'
        ];
        $validator  = Validator::make($request->all(),$rules,$message);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()],422);
        }

        $save = new Household;
        $save->head_name = $request->head_name;
        $save->address = $request->address;
        $save->phone = $request->phone;
        $save->member_count =   $request->member_count;
        $save->save();

        return response()->json(['pesan' => 'Data Penduduk disimpan']);

    }

    function edit($id) {
        $data = Household::find($id);

        return response()->json(['data' => $data ]);
    }

    function update(Request $request) {
        $update = Household::find($request->id);
        $update->head_name = $request->head_name;
        $update->address = $request->address;
        $update->phone = $request->phone;
        $update->member_count =   $request->member_count;
        $update->update();
        return response()->json(['pesan' => 'Data diperbaharui','data' => $request->all()]);
    }
}
