<?php

require_once __DIR__ . '/vendor/autoload.php';

use GraphQL\Utils\BuildSchema;
use Meetup\DataGenerator;
use GraphQL\GraphQL;

$dataGenerator = new DataGenerator();

$contents = file_get_contents(__DIR__ . '/schema.graphql');
$schema = BuildSchema::build($contents);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];

$rootValue = [
    'meetups' => $dataGenerator->collectionOf(10, 'randomMeetup'),
];

try {
    $result = GraphQL::executeQuery($schema, $query, $rootValue);
    $output = $result->toArray();
} catch (Exception $e) {
    $output = [
        'errors' => [
            'message' => $e->getMessage(),
        ],
    ];
}

header('Content-Type: application/json');
echo json_encode($output);
