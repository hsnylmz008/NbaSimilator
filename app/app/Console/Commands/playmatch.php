<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Matches;
use App\Repositories\MatchRepository;

class playmatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play:match
                            {homeId : The Id of the home team} 
                            {awayId : The Id of the away team}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play NBA matches by passing team Id\'s';

    protected $match;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MatchRepository $repo)
    {
        $this->match = $repo;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $homeId = (int) $this->argument('homeId');
            $awayId = (int) $this->argument('awayId');
            $this->info('Simulating NBA match');
            $this->match->playMatch($homeId, $awayId);
            $this->info('Simulation over');
        } catch (\Exception $e) {
            $this->error($e);
        }
    }
}
