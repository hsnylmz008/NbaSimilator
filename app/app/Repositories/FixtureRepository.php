<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Teams;
use App\Models\Fixture;
use App\Services\Fixture\MatchFixture;


class FixtureRepository
{

    protected $model;

    public function __construct(Fixture $fixture)
    {
        $this->model = $fixture;
    }

    public function scheduleMatches()
    {
        $teams = Teams::pluck('id')->toArray();
        $fixture = new MatchFixture($teams);
        $schedule = $fixture->getSchedule();
        $today = $this->model->getAvailableDateForFixture() ?? Carbon::now();
        foreach ($schedule as $key => $value) {
            foreach ($value as $match) {
                $fixture = new Fixture();
                $fixture->home_team_id =  $match[0];
                $fixture->away_team_id =  $match[1];
                $fixture->schedule =  $today;
                $fixture->status =  'pending';
                $fixture->save();
            }
            $today->addDay();
        }
    }

}