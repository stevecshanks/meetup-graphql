<?php

namespace MeetupQL\Domain;

interface PersonRepository
{
    /**
     * @return Person[]
     */
    public function findAll(): array;

    public function add(Person $person): void;

    /**
     * @return Person
     */
    public function findById(string $id): Person;
}
