<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailEvent;
use App\Models\event;
use DB;
use Carbon\Carbon;

class ShowPaymentHousehold extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $event = event::all();
        return view('info_payments',compact('event'));
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
    public function show(Request $request)
    {
        $eventIds = $request->event_id;
        $event = Event::find($eventIds);
        // $detailEvent = DetailEvent::where('event_id',$request->event_id)->get();
        $results = DB::table('detail_events')
            ->select('minggu_ke', DB::raw('SUM(jumlah_bayar) as total_bayar'))
            ->where('event_id', $eventIds)
            ->groupBy('minggu_ke')
            ->get();
        $subtotal = number_format($event->total_pendapatan,0,',','.');
        $biaya_perkk = number_format($event->biaya_perkk,0,',','.');
        $lastDate = DB::table('detail_events')
            ->where('event_id', $eventIds)
            ->orderBy('created_at', 'desc')
            ->value('created_at');

        $formatId  = Carbon::parse($lastDate)->translatedFormat('l, d F Y');
        return response()->json(['data' => $results,'subtotal' => $subtotal,'biaya_perkk' => $biaya_perkk,'lastDate' => $formatId]);
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
}
