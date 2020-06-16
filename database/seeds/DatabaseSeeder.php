<?php

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
        //$this->call(UserSeeder::class);
        $this->call(SubmitterSeeder::class);
        $this->call(ProcessorSeeder::class);
        $this->call(JobListSeeder::class);
    }
}
