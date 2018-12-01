<?php

namespace MeetupQL\Domain;

interface MeetupRepository
{
    /**
     * @return Meetup[]
     */
    public function findAll(): array;

    public function add(Meetup $meetup): void;

    /**
     * @param Person $person
     * @return Meetup[]
     */
    public function findByAttendee(Person $person): array;
}
