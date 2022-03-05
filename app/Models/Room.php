<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Room extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'floor',
        'description',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function isFree(string $date)
    {
        return $this->getReservationByDate($date);
    }

    public function getReservationByDate(string $date)
    {
        return $this->reservations()->where('date',$date)->get();
    }
}
