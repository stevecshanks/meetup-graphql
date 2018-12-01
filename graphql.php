<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\Utils\BuildSchema;
use GraphQL\GraphQL;
use GraphQL\Error\Debug;
use MeetupQL\Database\MongoDbMeetupRepository;
use MeetupQL\Database\MongoDbPersonRepository;
use MeetupQL\GraphQL\FieldResolver;
use MeetupQL\GraphQL\MeetupResolver;
use MeetupQL\GraphQL\QueryResolver;
use MeetupQL\GraphQL\ResolverRegistry;
use MongoDB\Client;

$contents = file_get_contents(__DIR__ . '/schema.graphql');
$schema = BuildSchema::build($contents);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];

$mongoDbClient = new Client('mongodb://mongodb');
$meetupRepository = new MongoDbMeetupRepository($mongoDbClient);
$personRepository = new MongoDbPersonRepository($mongoDbClient);

$resolverRegistry = new ResolverRegistry();
$resolverRegistry->add('Query', new QueryResolver($resolverRegistry, $meetupRepository, $personRepository));
$resolverRegistry->add('Meetup', new MeetupResolver($resolverRegistry, $personRepository));

try {
    $result = GraphQL::executeQuery(
        $schema,
        $query,
        null,
        null,
        null,
        null,
        new FieldResolver($resolverRegistry)
    );
    $output = $result->toArray(Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE);
} catch (Exception $e) {
    $output = [
        'errors' => [
            'message' => $e->getMessage(),
        ],
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
