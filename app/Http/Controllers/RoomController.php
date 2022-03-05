<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use App\Models\Room;
use Illuminate\Contracts\Database\Eloquent\Builder;
use PhpParser\Node\Expr\Cast\Array_;

class RoomController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'floor' => 'required',
            'description' => 'required'
        ]);
        return Room::create([
            'floor' => $request['floor'],
            'description' => $request['description']
        ]);
    }

    public function show($date, $id)
    {
        return Room::find($id)->reservations()->where('date',$date)->get();
    }

    public function getFreeRoomsPerDate($date)
    {
        $rooms = array();

        foreach(Room::all() as $room)
        {
            if($room->isFree($date))
            {
                array_push($rooms,$room);
            }
        }
        
        return $rooms;
    }

    public function getByPeriod(Date $start, Date $end)
    {
        $rooms = Room::all();

        foreach($rooms as $room)
        {
            $room->reservations()
                ->where(function (Builder $query){
                    return $query->where('date', '>=', $start)
                                ->orWhere('date','<=', $end);
            })
            ->get();
        }

        return $rooms;
    }
}
