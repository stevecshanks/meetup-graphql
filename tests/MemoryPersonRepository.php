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
}
