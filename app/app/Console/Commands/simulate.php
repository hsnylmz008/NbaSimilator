<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\MatchRepository;

class simulate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate NBA matches';

    protected $simulate;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MatchRepository $match)
    {
        $this->simulate = $match;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $msg = $this->simulate->simulateMatches() ?? 'Simulating NBA matches for a week\'s duration';
        $this->info($msg);
    }
}
