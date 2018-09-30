<?php

use MeetupQL\Database\MongoDbMeetupRepository;
use MeetupQL\Domain\DataGenerator;
use MongoDB\Client;

require_once __DIR__ . '/vendor/autoload.php';

$mongoDbClient = new Client('mongodb://mongodb');

$meetupRepository = new MongoDbMeetupRepository($mongoDbClient);

$generator = new DataGenerator();

foreach ($generator->collectionOf(50, 'randomMeetup') as $meetup) {
    $meetupRepository->add($meetup);
}
