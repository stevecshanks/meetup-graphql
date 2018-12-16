<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\Utils\BuildSchema;
use GraphQL\GraphQL;
use GraphQL\Error\Debug;
use MeetupQL\Database\MongoDbMeetupRepository;
use MeetupQL\Database\MongoDbPersonRepository;
use MeetupQL\GraphQL\Api;
use MeetupQL\GraphQL\DefaultResolver;
use MeetupQL\GraphQL\MeetupResolver;
use MeetupQL\GraphQL\PersonResolver;
use MeetupQL\GraphQL\QueryResolver;
use MeetupQL\GraphQL\ResolverRegistry;
use MongoDB\Client;

ini_set('display_errors', false);
ini_set('log_errors', true);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];

$mongoDbClient = new Client('mongodb://mongodb');
$meetupRepository = new MongoDbMeetupRepository($mongoDbClient);
$personRepository = new MongoDbPersonRepository($mongoDbClient);

$api = new Api($meetupRepository, $personRepository);

try {
    $output = $api->execute($query);
} catch (Exception $e) {
    $output = [
        'errors' => [
            'message' => $e->getMessage(),
        ],
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
