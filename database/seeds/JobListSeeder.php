<?php

use Illuminate\Database\Seeder;

class JobListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\JobList::class, 10)->create();
    }
}
