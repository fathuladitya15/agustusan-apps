<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\event;
use App\Models\DetailEvent;
use Carbon\Carbon;
use App\Models\Household;


use DataTables;
use Validator;
use DB;

class PaymentController extends Controller
{

    function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $cekYears = $this->handleEvent();
        return view('layouts.event.index');
    }

    function getEvent(Request $request) {
        $data = event::orderBy('tahun_acara','ASC')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $update = '<a href="'.route('detail.event',['id' => $row->id]).'" class="btn btn-primary btn-sm edit-event" id="btn_edit'.$row->id.'" data-id="'.$row->id.'"><i class="nav-icon fas fa-solid fa-server"></i></a>';
                // $delete = '<button class="btn btn-danger btn-sm delete-event" id="btn_'.$row->id.'" data-id="'.$row->id.'" data-url="'.route('delete.event',['id' => $row->id]).'"><i class="nav-icon fas fa-trash"></i></button>';
                return $update;
            })
            ->addColumn('total_pendapatan',function($row) {
                return "Rp. ". number_format($row->total_pendapatan,0,',','.');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function detailEvent($id) {
        $data = event::find($id);

        $totalAllPayments = DetailEvent::where('event_id',$id)->sum('jumlah_bayar');

        $formatAllPayments = "Rp. ". number_format($totalAllPayments,0,',','.');
        return view('layouts.event.detail_event',compact('data','formatAllPayments'));

    }

    function detailEventSearchHousehold(Request $request,$id) {
        $searchTerm = $request->input('q');

        if ($searchTerm) {
            $households = Household::where('head_name', 'like', "%$searchTerm%")->get(['id', 'head_name']);
        } else {
            $households = Household::all(['id', 'head_name']);
        }

        return response()->json($households);
    }

    function getDetailEvent(Request $request,$event_id) {

        $latestEvents = DB::table('detail_events')
        ->select('detail_events.*')
        ->join(DB::raw('(SELECT household_id, MAX(created_at) AS latest_created_at
                        FROM detail_events
                        GROUP BY household_id) as latest_events'), function ($join) {
            $join->on('detail_events.household_id', '=', 'latest_events.household_id')
                ->on('detail_events.created_at', '=', 'latest_events.latest_created_at');
        })
        ->where('event_id',$event_id)
        ->get();

        $data = $latestEvents;


        $totalAllPayments = DetailEvent::where('event_id',$event_id)->sum('jumlah_bayar');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_kk', function($row) {
                $nama = Household::find($row->household_id);

                return $nama->head_name;
            })
            ->addColumn('terkumpul', function($row) {
                $totalPendapatan  = DetailEvent::where('household_id', $row->household_id)->sum('jumlah_bayar');
                return "Rp. ". number_format($totalPendapatan,0,'.',',') ;
            })
            ->addColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->translatedFormat('l, d F Y');
            })
            ->addColumn('action', function($row) use ($event_id) {
                $households = Household::find($row->household_id);
                $route = route('edit.detail.event.id',['event_id' => $event_id,'name' => $households->head_name,'user_id' => $households->id]);
                $update = '<button class="btn btn-primary btn-sm edit-event" id="btn_edit'.$row->id.'" data-id="'.$row->id.'"><i class="nav-icon fas fa-solid fa-pen"></i></button>';
                $delete = '<button class="btn btn-danger btn-sm delete-event" id="btn_'.$row->id.'" data-id="'.$row->id.'" data-url="'.route('delete.detail.event.id',['id' => $row->id,'event_id' => $event_id]).'"><i class="nav-icon fas fa-trash"></i></button>';
                // $detail = '<button class="btn btn-success btn-sm detail-event" id="btn_detail'.$row->id.'" data-event_id="'.$event_id.'" data-household_id="'.$row->household_id.'"><i class="nav-icon fas fa-solid fa-list"></i></button>';
                $detail = '<a class="btn btn-success btn-sm detail-event" href="'.$route.'"><i class="nav-icon fas fa-solid fa-list"></i></a>';;

                return $update.'&nbsp;'.$delete.'&nbsp;'.$detail;
            })
            ->addColumn('jumlah_bayar',function($row) {
                return "Rp. ". number_format($row->jumlah_bayar,0,'.',',');
            })
            ->with('totalAllPayments' ,$totalAllPayments)
            ->rawColumns(['action','terkumpul','created_at'])
            ->make(true);
    }

    function handleEvent() {
        $currentYear = Carbon::now()->year;

        // Check if an event for the current year already exists
        $eventExists = event::where('tahun_acara', $currentYear)->exists();

        if (!$eventExists) {
            // Create the event for the new year
            event::create([
                'total_kepala_keluarga' => 0, // Initial value, can be updated later
                'tahun_acara' => $currentYear,
                'total_pendapatan' => 0.00, // Initial value, can be updated later
            ]);

            return TRUE;
            // $this->info("Event for year $currentYear created successfully.");
        } else {
            // $this->info("Event for year $currentYear already exists.");
            return FALSE;
        }
    }

    function createDetailEvent(Request $request) {
        $cekPembayaran = $this->cekPembayaran($request->household_id,$request->minggu_ke);

        if($cekPembayaran == FALSE) {
            return response()->json(['status' => FALSE ,'pesan' => 'Minggu ke '.$request->minggu_ke.' telah terbayar']);
        }
        $insertDetailEvent = [
            'household_id' => $request->household_id,
            'event_id' => $request->event_id,
            'minggu_ke' => $request->minggu_ke,
            'jumlah_bayar' => $request->nominal,
            'status_bayar' => true];

        DetailEvent::create($insertDetailEvent);
        $countHouseholds  = DetailEvent::where('event_id',$request->event_id)->distinct()->count('household_id');
        $totalPendapatan  = DetailEvent::where('event_id', $request->event_id)->sum('jumlah_bayar');

        Event::where('id', $request->event_id)->update(['total_kepala_keluarga' => $countHouseholds,'total_pendapatan' => $totalPendapatan ]);
        // dd($dataMember);
        return response()->json(['pesan' => 'Pembayaran berhasil','status' => TRUE,]);
    }

    function cekPembayaran($household_id,$minggu_ke) {
        $cek = DetailEvent::where('household_id',$household_id)->where('minggu_ke',$minggu_ke)->count();

        if($cek >= 1) {
            return FALSE;
        }

        return TRUE;
    }

    function getDetailEventPerId($id) {
        $data = DetailEvent::find($id);
        $nama_kk = Household::find($data->household_id);
        $result = [
            'id'    => $data->id,
            'nama_kk' => $nama_kk->head_name,
            'minggu_ke' => $data->minggu_ke,
            'nominal' => $data->jumlah_bayar
        ];

        return response()->json(['data' => $result]);
    }

    function updateDetailEventPerId(Request $request) {
        $data = DetailEvent::find($request->detail_event_id);
        $data->minggu_ke = $request->minggu_ke;
        $data->jumlah_bayar = $request->nominal;

        $data->update();

        $countHouseholds  = DetailEvent::where('event_id',$request->event_id)->distinct()->count('household_id');
        $totalPendapatan  = DetailEvent::where('event_id', $request->event_id)->sum('jumlah_bayar');

        Event::where('id', $request->event_id)->update(['total_kepala_keluarga' => $countHouseholds,'total_pendapatan' => $totalPendapatan ]);
        return response()->json(['pesan' => 'Data diperbarui']);
    }

    function editDetailEventPerId($event_id,$name,$household_id,Request $request) {
        $households = Household::find($household_id);
        $totalAllPayments = DetailEvent::where('event_id',$event_id)->where('household_id',$household_id)->sum('jumlah_bayar');
        if($request->ajax()) {
            return $this->dataEditDetailEventPerId($event_id,$household_id);
        }

        $formatAllPayments = "Rp. ". number_format($totalAllPayments,0,',','.');

        return view('layouts.event.detail_houshold_payment',compact('households','event_id','formatAllPayments'));
        // dd($getData);
    }

    function dataEditDetailEventPerId($event_id,$household_id) {
        $getData = DetailEvent::where('event_id',$event_id)->where('household_id',$household_id)->get();
        $totalAllPayments = DetailEvent::where('event_id',$event_id)->where('household_id',$household_id)->sum('jumlah_bayar');
        return DataTables::of($getData)
            ->addIndexColumn()
            ->addColumn('nama_kk', function($row) {
                $nama = Household::find($row->household_id);

                return $nama->head_name;
            })
            ->addColumn('terkumpul', function($row) {
                $totalPendapatan  = DetailEvent::where('household_id', $row->household_id)->sum('jumlah_bayar');
                return "Rp. ". number_format($totalPendapatan,0,'.',',') ;
            })
            ->addColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->translatedFormat('l, d F Y');
            })
            ->addColumn('action', function($row) use ($event_id) {
                $update = '<button class="btn btn-primary btn-sm edit-event" id="btn_edit'.$row->id.'" data-id="'.$row->id.'"><i class="nav-icon fas fa-solid fa-pen"></i></button>';
                $delete = '<button class="btn btn-danger btn-sm delete-event" id="btn_'.$row->id.'" data-id="'.$row->id.'" data-url="'.route('delete.detail.event.id',['id' => $row->id,'event_id' => $event_id]).'"><i class="nav-icon fas fa-trash"></i></button>';

                return $update.'&nbsp;'.$delete;
            })
            ->addColumn('jumlah_bayar',function($row) {
                return "Rp. ". number_format($row->jumlah_bayar,0,'.',',');
            })
            ->with('totalAllPayments' ,$totalAllPayments)
            ->rawColumns(['action','terkumpul','created_at'])
            ->make(true);
    }

    function deleteDetailEventPerId($id,$event_id) {
        $get = DetailEvent::findOrFail($id);
        $get->delete();

        $countHouseholds  = DetailEvent::where('event_id',$event_id)->distinct()->count('household_id');
        $totalPendapatan  = DetailEvent::where('event_id', $event_id)->sum('jumlah_bayar');

        Event::where('id', $event_id)->update(['total_kepala_keluarga' => $countHouseholds,'total_pendapatan' => $totalPendapatan ]);
        return response()->json(['pesan' => 'Data dihapus.']);


    }
}
