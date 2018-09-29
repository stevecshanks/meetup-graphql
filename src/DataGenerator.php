<?php

namespace Meetup;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\en_GB\Address;

class DataGenerator
{
    /** @var Generator */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->faker->addProvider(new Address($this->faker));
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

    public function randomAddress(): array
    {
        return [
            'companyName' => $this->faker->company,
            'address' => $this->faker->buildingNumber . ' ' . $this->faker->streetName,
            'city' => $this->faker->city,
            'postcode' => $this->faker->postcode,
        ];
    }

    public function randomMeetup(): array
    {
        return [
            'id' => $this->faker->unique()->randomNumber(),
            'name' => ucwords($this->faker->catchPhrase),
            'location' => $this->randomAddress(),
            'start' => $this->faker->dateTimeThisYear->format('Y-m-d\TH:30:00O'),
            'organiser' => $this->randomPerson(),
            'presenter' => $this->randomPerson(),
            'attendees' => $this->collectionOf(random_int(0, 5), 'randomPerson'),
        ];
    }
}
