<?php

use MeetupQL\Database\MongoDbMeetupRepository;
use MeetupQL\Database\MongoDbPersonRepository;
use MeetupQL\Domain\DataGenerator;
use MongoDB\Client;

require_once __DIR__ . '/vendor/autoload.php';

$mongoDbClient = new Client('mongodb://mongodb');

$personRepository = new MongoDbPersonRepository($mongoDbClient);
$meetupRepository = new MongoDbMeetupRepository($mongoDbClient);

$generator = new DataGenerator();

foreach ($generator->collectionOf(50, [$generator, 'randomPerson']) as $person) {
    $personRepository->add($person);
}

$people = $personRepository->findAll();

$randomMeetup = function () use ($generator, $people) {
    return $generator->randomMeetup($people);
};

foreach ($generator->collectionOf(20, $randomMeetup) as $meetup) {
    $meetupRepository->add($meetup);
}
