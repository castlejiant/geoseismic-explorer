<?php

namespace App\Http\Controllers;

use App\seismicEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use carbon\carbon;


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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //passing earthquake database to view
        $eqdb = $this->eventUpdate();
        return view('home',compact('eqdb'));
    }

    public function getEvent(Request $request)
    {
        if ($request->ajax()) {
            $listener = $this->notification();
            $this->eventUpdate();
            return $listener;
        }

    }

    public function eventUpdate()
    {
        $url="https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_hour.geojson";
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);
        // Will dump a beauty json :3
            $eqdb = json_decode($result, true);
            $eqdb = $eqdb['features'];
            
            try {
                DB::beginTransaction();
               
                foreach ($eqdb as $eqdbs) {
 
                        $events = $eqdbs['properties']; 
                        $type = $eqdbs['geometry'];
                        $point = $type['coordinates'];
                        //converting UTC into IST using carbon instance
                        $time = Carbon::createFromTimestampMs($events['time'], 'Asia/Kolkata')->format('Y-m-d\TH:i:s.uP T');
                        $event_id = seismicEvent::where('event_id', '=', $eqdbs['id'])->first();
                        if ($event_id === null) {
                            //event doesn't exist then save events
                            DB::table('seismic_events')->insert(
                                     array(
                                            'event_id'=>   $eqdbs['id'],
                                            'mag'     =>   $events['mag'], 
                                            'place'   =>   $events['place'],
                                            'time'    =>   $time,
                                            'updated' =>   $events['updated'],
                                            'url'     =>   $events['url'],
                                            'detail'  =>   $events['detail'],
                                            'longitude' =>  $point[0],
                                            'latitude'  =>  $point[1],
                                            'depth'     =>  $point[2]
                                     )
                                );
                        }          
                }
                DB::commit();
            } catch(\Exception $e){
                echo $e;
                DB::rollBack();
            }

            $eqdb = DB::table('seismic_events')->orderBy('id', 'desc')->paginate(4);
            return $eqdb;
        
    }

    public function notification() 
    {
        $url="https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/all_hour.geojson";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result=curl_exec($ch);
        curl_close($ch);

            $eqdb = json_decode($result, true);
            $eqdb = $eqdb['features'];
        foreach ($eqdb as $eqdbs) {
 
            $events = $eqdbs['properties']; 
            $type = $eqdbs['geometry'];
            $point = $type['coordinates'];
            $mag = $events['mag'];
            $event_id = seismicEvent::where('event_id', '=', $eqdbs['id'])->first();
            if ($event_id === null) {
            if ($mag>=1) {
                return '1';
            }
            else{
                return '0';
            }
        } else {
            return '0';
        }
    }
}
}
