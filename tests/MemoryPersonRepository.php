<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Person;
use MeetupQL\Domain\PersonRepository;

class MemoryPersonRepository extends Collection implements PersonRepository
{
    public function findAll(): array
    {
        return $this->allItemsInCollection();
    }

    public function add(Person $person): void
    {
        $this->addToCollection($person);
    }

    public function findById(string $id): Person
    {
        return $this->itemWithId($id);
    }

    public function findByInterest(string $interest): array
    {
        return $this->filterCollection(function (Person $person) use ($interest) {
            return in_array($interest, $person->getInterests());
        });
    }
}
