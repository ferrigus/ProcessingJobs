<?php

use Illuminate\Database\Seeder;

class SubmitterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Submitter::class, 20)->create();
    }
}
