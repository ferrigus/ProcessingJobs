<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\JobList;
use App\Submitter;
use App\Processor;
use Faker\Generator as Faker;

$factory->define(JobList::class, function (Faker $faker) {

	$submitters = Submitter::select('id')->get();
	$processors = Processor::select('id')->doesntHave('job_list')->get();

	$object=array();
	foreach ($submitters as $item) {
	    $object[]=$item->id;
	}

	foreach ($processors as $item) {
	    $object2[]=$item->id;
	}

	$submitter_id = array_rand($object, 1);
	$processor_id = array_rand($object2, 1);

    return [
        'queue' => 'high',
        'submitter_id' => $submitter_id,
        'processor_id' => $processor_id,
        'processing_time' => $faker->time('00:00:s'),
    ];
});
