<?php

require_once __DIR__ . '/vendor/autoload.php';

use Meetup\DataGenerator;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

$dataGenerator = new DataGenerator();

$personType = new ObjectType([
    'name' => 'Person',
    'fields' => [
        'id' => Type::id(),
        'name' => Type::string(),
        'companyName' => Type::string(),
    ]
]);

$addressType = new ObjectType([
    'name' => 'Address',
    'fields' => [
        'companyName' => Type::string(),
        'address' => Type::string(),
        'city' => Type::string(),
        'postcode' => Type::string(),
    ]
]);

$meetupType = new ObjectType([
    'name' => 'Meetup',
    'fields' => [
        'id' => Type::id(),
        'name' => Type::string(),
        'location' => $addressType,
        'start' => Type::string(),
        'organiser' => $personType,
        'presenter' => $personType,
        'attendees' => Type::listOf($personType),
    ],
]);

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'meetups' => [
            'type' => Type::listOf($meetupType),
            'resolve' => function () use ($dataGenerator) {
                return $dataGenerator->collectionOf(10, 'randomMeetup');
            }
        ]
    ],
]);

$schema = new Schema([
    'query' => $queryType,
]);

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
$query = $input['query'];
$variableValues = $input['variables'] ?? null;

try {
    $result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);
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
