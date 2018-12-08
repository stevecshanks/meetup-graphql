<?php

namespace MeetupQL\Domain;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\en_GB\Address as FakerAddress;
use MeetupQL\GraphQL\GlobalId;

class DataGenerator
{
    /** @var Generator */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->faker->addProvider(new FakerAddress($this->faker));
    }

    public function collectionOf(int $numberOfEntries, callable $generatorFunction): array
    {
        $results = [];

        for ($i = 0; $i < $numberOfEntries; $i++) {
            $results[] = $generatorFunction();
        }

        return $results;
    }

    public function randomPerson(): Person
    {
        return new Person(
            $this->randomIdFor('Person'),
            $this->faker->name,
            random_int(0, 1) ? $this->faker->company : null,
            $this->collectionOf(random_int(0, 5), [$this, 'randomTopic'])
        );
    }

    /**
     * @param Person[] $people
     * @return Meetup
     */
    public function randomMeetup(array $people): Meetup
    {
        return new Meetup(
            $this->randomIdFor('Meetup'),
            $this->randomTopic(),
            $this->randomAddress(),
            $this->faker->dateTimeThisYear->format('Y-m-d\TH:30:00O'),
            $this->randomPersonFrom($people)->getId(),
            $this->randomPersonFrom($people)->getId(),
            $this->collectionOf(random_int(0, 5), function () use ($people) {
                return $this->randomPersonFrom($people)->getId();
            })
        );
    }

    private function randomIdFor(string $type): string
    {
        return GlobalId::encode($type, $this->faker->unique()->randomNumber());
    }

    private function randomTopic(): string
    {
        return ucwords($this->faker->catchPhrase);
    }

    private function randomAddress(): Address
    {
        return new Address(
            $this->faker->company,
            $this->faker->buildingNumber . ' ' . $this->faker->streetName,
            $this->faker->city,
            $this->faker->postcode
        );
    }

    private function randomPersonFrom(array $people): Person
    {
        return $people[array_rand($people)];
    }
}
