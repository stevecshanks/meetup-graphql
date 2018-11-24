<?php

namespace MeetupQL\Domain;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\en_GB\Address as FakerAddress;
use MeetupQL\GraphQL\IdService;

class DataGenerator
{
    /** @var Generator */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->faker->addProvider(new FakerAddress($this->faker));
    }

    public function collectionOf(int $numberOfEntries, string $generatorFunctionName): array
    {
        $results = [];

        for ($i = 0; $i < $numberOfEntries; $i++) {
            $results[] = $this->{$generatorFunctionName}();
        }

        return $results;
    }

    public function randomPerson(): Person
    {
        return new Person(
            $this->randomIdFor('Person'),
            $this->faker->name,
            random_int(0, 1) ? $this->faker->company : null
        );
    }

    public function randomAddress(): Address
    {
        return new Address(
            $this->faker->company,
            $this->faker->buildingNumber . ' ' . $this->faker->streetName,
            $this->faker->city,
            $this->faker->postcode
        );
    }

    public function randomMeetup(): Meetup
    {
        return new Meetup(
            $this->randomIdFor('Meetup'),
            ucwords($this->faker->catchPhrase),
            $this->randomAddress(),
            $this->faker->dateTimeThisYear->format('Y-m-d\TH:30:00O'),
            $this->randomPerson(),
            $this->randomPerson(),
            $this->collectionOf(random_int(0, 5), 'randomPerson')
        );
    }

    private function randomIdFor(string $type): string
    {
        return IdService::encode($type, $this->faker->unique()->randomNumber());
    }
}
