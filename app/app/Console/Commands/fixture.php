<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\FixtureRepository;

class fixture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixture:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create NBA match fixture for a week';

    protected $fixture;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FixtureRepository $fixtureRepository)
    {
        $this->fixture = $fixtureRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Schedule for NBA matches created');
        $this->fixture->scheduleMatches();
    }
}
