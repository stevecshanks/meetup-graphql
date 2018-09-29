<?php

namespace MeetupQL\Domain;

class MeetupRepository
{
    /**
     * @return Meetup[]
     */
    public function findAll(): array
    {
        $generator = new DataGenerator();
        return $generator->collectionOf(10, 'randomMeetup');
    }
}
