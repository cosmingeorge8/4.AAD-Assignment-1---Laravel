<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use DateTime;

use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Psr7\Response;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Reservation::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'room' => 'required',
            'date' => 'required'
        ]);

        $room = Room::find($request['room']);

        if(!$room)
        {
            return response('Room $room not found', 404);
        }
        if($room->isFree($request['date']))
        {
            return response('Room is booked in this date');
        }
        
        return Reservation::create([
            'user_id' => $user['id'],
            'room_id' => $request['room'],
            'date' => $request['date']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Reservation::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        $reservation->update($request->all());
        return $reservation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       return Reservation::destroy($id);
    }

    /**
     * Get a reservation by given date
     */
    public function getByDate($date)
    {
        return Reservation::where('date', $date)->get();
    }
}
