<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{

    protected $fillable = [
        'name',
        'wins',
        'losses'
    ];

    public function __construct()
    {
        //
    }

    public static function updateWins(int $teamId)
    {
        self::find($teamId)->increment('wins');
    }

    public static function updateLosses(int $teamId)
    {
        self::find($teamId)->increment('losses');
    }
}
