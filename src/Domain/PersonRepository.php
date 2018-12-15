<?php

namespace MeetupQL\Domain;

interface PersonRepository
{
    /**
     * @return Person[]
     */
    public function findAll(): array;

    public function add(Person $person): void;

    public function findById(string $id): Person;

    /**
     * @param string $interest
     * @return Person[]
     */
    public function findByInterest(string $interest): array;
}
