<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Household;
use App\Models\User;
use App\Models\event;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countHousehold = Household::count();
        $getEventLast   = event::latest()->first();
        $nameEvent      = "Event 17san ".$getEventLast->tahun_acara;
        $getPayment     = "Rp. ".number_format($getEventLast->total_pendapatan,0,',','.');
        $getCountUsers  = User::count();
        return view('newhome',compact('countHousehold','nameEvent','getPayment','getCountUsers'));
    }
}
