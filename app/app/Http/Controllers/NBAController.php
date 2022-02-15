<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use App\Models\Match;
use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NBAController extends Controller
{
    public function __construct()
    {
        //
    }

    public function leaderboard()
    {
        $teams = Teams::select('name', 'wins', 'losses')->orderBy('wins', 'desc')->get()->toArray();
        return response()
            ->json(['status' => true, 'data' => $teams]);
    }

    public function live()
    {
        $matches = Match::select()->whereHas('fixture',  function ($query) {
            $query->where('status', 'current');
        })->with(['fixture', 'fixture.homeTeam', 'fixture.awayTeam'])->get()->toArray();
        $data = [];
        $i = 0;
        foreach ($matches as $row) {
            $data[$i]['home_team'] = $row['fixture']['home_team']['name'];
            $data[$i]['away_team'] = $row['fixture']['away_team']['name'];
            $data[$i]['home_team_points'] = $row['home_points'];
            $data[$i]['away_team_points'] = $row['away_points'];
            $data[$i]['home_team_attacks'] = $row['home_attacks'];
            $data[$i]['away_team_attacks'] = $row['away_attacks'];
            $data[$i]['start_time'] = date("F j, g:i a", strtotime($row['start_at']));
            $i++;
        }
        return response()
            ->json(['status' => true, 'data' => $data]);
    }

    public function fixture()
    {
        $fixtures = Fixture::select()->with(['homeTeam', 'awayTeam'])->where('status', '!=', 'done')->get()->toArray();
        $data = [];
        $i = 0;
        foreach ($fixtures as $row) {
            $data[$i]['home_team'] = $row['home_team']['name'];
            $data[$i]['away_team'] = $row['away_team']['name'];
            $data[$i]['status'] = $row['status'];
            $data[$i]['schedule'] = date("F j, g:i a", strtotime($row['schedule']));
            $i++;
        }
        return response()
            ->json(['status' => true, 'data' => $data]);
    }
}
