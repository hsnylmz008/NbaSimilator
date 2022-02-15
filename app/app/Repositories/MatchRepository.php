<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Teams;
use App\Models\Match;
use App\Models\Fixture;
use App\Services\Matches;

use Graze\ParallelProcess\Pool;
use Symfony\Component\Process\Process;

class MatchRepository
{
    protected $model;

    public function __construct(Match $match)
    {
        $this->model = $match;
    }

    public function simulateMatches()
    {
        $start = microtime(true);
        $fixture = new Fixture();
        // echo date('Y-m-d H:i:s') . PHP_EOL;
        if ($fixtures = $fixture->getMatches()) {
            foreach ($fixtures as $key => $value) {
                $pool = new Pool();
                $iterationStart = microtime(true);
                foreach ($value as $row) {
                    // echo 'php artisan play:match ' . $row['home_team_id'] . ' ' . $row['away_team_id'] . PHP_EOL;
                    $pool->add(new Process('php artisan play:match ' . $row['home_team_id'] . ' ' . $row['away_team_id']));
                }
                $pool->run();
                $iteration = microtime(true) - $iterationStart;
                // echo "Iteration time: " . $iteration . PHP_EOL;
            }
            // $end = microtime(true) - $start;
            // echo "Total time: " . $end . PHP_EOL;
            return null;
        }
        return 'Populate fixtures using php artisan fixture:create';
    }

    private function _attack()
    {
        $attack = mt_rand(1, 100);
        $defend = mt_rand(1, 100);
        return (mt_rand(1, $attack) > $defend);
    }

    public function playMatch(int $homeTeam, int $awayTeam) {
        try {
            $fixture = new Fixture();
            $fixture->setParams($homeTeam, $awayTeam);
            $fixture->updateFixtureStatus('pending', 'current');
            $score = [];
            $time = $miss = $basket = $points = 0;
            $score[$homeTeam]['basket'] = $score[$homeTeam]['points'] = $score[$homeTeam]['misses'] = 0;
            $score[$awayTeam]['basket'] = $score[$awayTeam]['points'] = $score[$awayTeam]['misses'] = 0;
            $attackingTeam = $homeTeam;
            $fixtureId = $fixture->getRunningFixtureId();
            $match = new Match();
            $match->setFixtureId($fixtureId);
            $this->_startMatch($fixtureId);
            while ($time <= 238) {
                sleep(2);
                if ($this->_attack()) {
                    $basket++;
                    $points += mt_rand(1,3);
                    $score[$attackingTeam]['basket'] = $basket;
                    $score[$attackingTeam]['points'] = $points;
                } else {
                    $miss++;
                    $score[$attackingTeam]['misses'] = $miss;
                    if ($attackingTeam == $homeTeam) {
                        $attackingTeam = $awayTeam;
                    } else {
                        $attackingTeam = $homeTeam;
                    }
                }
                if ($time % 5 == 0) {
                    $this->_updateScores($score, $match, $homeTeam, $awayTeam);
                }
                $time += 2;
            }
            $this->_updateScores($score, $match, $homeTeam, $awayTeam);
            if ($score[$homeTeam]['points'] > $score[$awayTeam]['points']) {
                $match->endMatch($homeTeam, $awayTeam);
            } else {
                $match->endMatch($awayTeam, $homeTeam);
            }
            $fixture->updateFixtureStatus('current', 'done');
        } catch (\Exception $e) {
            return $e;
        }
    }

    private function _updateScores(array $score, Match $match, $homeTeam, $awayTeam)
    {
        $homeAttacks = $score[$homeTeam]['basket'] + $score[$homeTeam]['misses'];
        $awayAttacks = $score[$awayTeam]['basket'] + $score[$awayTeam]['misses'];
        $match->updateMatch($score[$homeTeam]['points'], $score[$awayTeam]['points'], $homeAttacks, $awayAttacks);
    }

    private function _startMatch($fixtureId)
    {
        $this->model->fill([
            'fixture_id' => $fixtureId,
            'home_points' => 0,
            'away_points' => 0,
            'home_attacks' => 0,
            'away_attacks' => 0,
            'start_at' => Carbon::now()
        ]);
        $this->model->save();
    }

}