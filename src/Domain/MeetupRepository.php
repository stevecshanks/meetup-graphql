<?php

namespace MeetupQL\Domain;

interface MeetupRepository
{
    /**
     * @return Meetup[]
     */
    public function findAll(): array;

    public function add(Meetup $meetup): void;
}
