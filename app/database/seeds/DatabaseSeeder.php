<?php

use App\Models\Teams;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            'Boston Celtics',
            'Philadelphia 76ers',
            'New York Knicks',
            'L.A. Lakers',
            'Detroit Pistons',
            'Chicago Bulls',
            'Toronto Raptors',
            'Golden State Warriors'
        ];

        foreach ($teams as $item) {
            $team = new Teams();
            $team->name = $item;
            $team->save();
        }
        // $this->call(UsersTableSeeder::class);
    }
}
