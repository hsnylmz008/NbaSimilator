<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Teams;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $table = 'match';
    protected $fillable = [
        'fixture_id', 
        'home_points', 
        'away_points', 
        'home_attacks', 
        'away_attacks',
        'winner',
        'start_at',
        'end_at'
    ];

    protected $fixtureId;

    public function __construct()
    {
        //
    }

    public function setFixtureId(int $fixture)
    {
        $this->fixtureId = $fixture;
    }

    public function startMatch()
    {
        return self::create([
            'fixture_id' => $this->fixtureId,
            'home_points' => 0,
            'away_points' => 0,
            'home_attacks' => 0,
            'away_attacks' => 0,
            'start_at' => Carbon::now()
        ]);
    }

    public function updateMatch(int $homePoints, int $awayPoints, int $homeAttacks, int $awayAttacks)
    {
        self::where('fixture_id', $this->fixtureId)
            ->update([
                'home_points'  => $homePoints,
                'away_points'  => $awayPoints,
                'home_attacks' => $homeAttacks,
                'away_attacks' => $awayAttacks,
            ]);
    }

    public function endMatch(int $winnerId, int $looserId)
    {
        self::where('fixture_id', $this->fixtureId)
            ->update([
                'winner' => $winnerId,
                'end_at' => Carbon::now()
            ]);
        Teams::updateWins($winnerId);
        Teams::updateLosses($looserId);
    }

    public function fixture()
    {
        return $this->belongsTo('\App\Models\Fixture', 'fixture_id', 'id');
    }
}
