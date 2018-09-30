<?php

namespace MeetupQL\Domain;

interface MeetupRepository
{
    /**
     * @return Meetup[]
     */
    public function findAll(): array;
}
