<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    protected $table = 'fixture';
    protected $dates = ['schedule'];

    protected $fillable = [
        'fixture_id', 
        'home_team_id', 
        'away_team_id', 
        'schedule', 
        'status'
    ];
    protected $homeId;
    protected $awayId;

    public function __construct()
    {
        //
    }

    public function setParams(int $homeId, int $awayId)
    {
        $this->homeId = $homeId;
        $this->awayId = $awayId;
    }

    public function getAvailableDateForFixture()
    {
        $date = self::orderBy('schedule', 'desc')->pluck('schedule')->first();
        if (!$date) {
            return null;
        }
        return $date->addDay();
    }

    public function getMatchDate()
    {
        $date = self::where('status', 'pending')->orderBy('schedule')->pluck('schedule')->first();
        if (!$date) {
            return null;
        }
        return $date;
    }

    public function getMatches()
    {
        $startDate = $this->getMatchDate() ?? Carbon::now();
        $endDate = $startDate->copy()->addWeek()->subDay();
        $fixtures = collect(self::where('status', 'pending')
            ->whereBetween('schedule', [$startDate, $endDate])
            ->select('home_team_id', 'away_team_id', 'schedule')
            ->get()
            ->toArray()
            )->groupBy('schedule');
        if (!$fixtures) {
            return false;
        }
        return $fixtures;
    }

    public function getRunningFixtureId()
    {
        return (int) self::where('home_team_id', $this->homeId)
            ->where('away_team_id', $this->awayId)
            ->where('status', 'current')
            ->pluck('id')
            ->first();
    }

    public function updateFixtureStatus(String $oldStatus, String $newStatus)
    {
        return self::where('home_team_id', $this->homeId)
            ->where('away_team_id', $this->awayId)
            ->where('status', $oldStatus)
            ->update(['status' => $newStatus]);
    }

    public function homeTeam()
    {
        return $this->belongsTo('\App\Models\Teams', 'home_team_id', 'id');
    }

    public function awayTeam()
    {
        return $this->belongsTo('\App\Models\Teams', 'away_team_id', 'id');
    }
}
