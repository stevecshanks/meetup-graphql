<?php

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

$client = new Client();

$query = <<<GRAPHQL
query {
  meetups {
    name
    start
    location {
      companyName
      address
      city
      postcode
    }
    organiser {
      name
    }
    attendees {
      id
    }
  }
}
GRAPHQL;

$response = $client->post('http://graphql:8080', [
    'json' => [
        'query' => $query
    ]
]);

$json = json_decode($response->getBody()->getContents());

echo $twig->render('index.html.twig', ['meetups' => $json->data->meetups]);
