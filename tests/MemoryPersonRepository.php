<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Person;
use MeetupQL\Domain\PersonRepository;

class MemoryPersonRepository implements PersonRepository
{
    /** @var Person[] */
    private $people;

    /**
     * MemoryPersonRepository constructor.
     */
    public function __construct()
    {
        $this->people = [];
    }

    public function findAll(): array
    {
        return $this->people;
    }

    public function add(Person $person): void
    {
        $this->people[] = $person;
    }

    public function findById(string $id): Person
    {
        foreach ($this->people as $person) {
            if ($person->getId() === $id) {
                return $person;
            }
        }

        throw new \InvalidArgumentException("No person found with ID {$id}");
    }
}
