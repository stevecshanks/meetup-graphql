<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\Utils\BuildSchema;
use GraphQL\GraphQL;
use GraphQL\Error\Debug;
use MeetupQL\GraphQL\FieldResolver;
use MeetupQL\Domain\FakeMeetupRepository;

$contents = file_get_contents(__DIR__ . '/schema.graphql');
$schema = BuildSchema::build($contents);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];

$meetupRepository = new FakeMeetupRepository();

$rootValue = [
    'meetups' => function () use ($meetupRepository) {
        return $meetupRepository->findAll();
    },
];

try {
    $result = GraphQL::executeQuery(
        $schema,
        $query,
        $rootValue,
        null,
        null,
        null,
        new FieldResolver()
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
