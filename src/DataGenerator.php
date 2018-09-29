<?php

namespace Meetup;

use Faker\Factory;
use Faker\Generator;

class DataGenerator
{
    /** @var Generator */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function collectionOf(int $numberOfEntries, string $generatorFunctionName): array
    {
        $results = [];

        for ($i = 0; $i < $numberOfEntries; $i++) {
            $results[] = $this->{$generatorFunctionName}();
        }

        return $results;
    }

    public function randomPerson(): array
    {
        return [
            'id' => $this->faker->unique()->randomNumber(),
            'name' => $this->faker->name,
            'companyName' => random_int(0, 1) ? $this->faker->company : null,
        ];
    }

    public function randomMeetup(): array
    {
        return [
            'id' => $this->faker->unique()->randomNumber(),
            'name' => ucwords($this->faker->catchPhrase),
            'organiser' => $this->randomPerson(),
            'presenter' => $this->randomPerson(),
            'attendees' => $this->collectionOf(random_int(0, 5), 'randomPerson'),
        ];
    }
}
