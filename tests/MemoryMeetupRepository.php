<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Meetup;
use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\Person;

class MemoryMeetupRepository extends Collection implements MeetupRepository
{
    public function findAll(): array
    {
        return $this->allItemsInCollection();
    }

    public function add(Meetup $meetup): void
    {
        $this->addToCollection($meetup);
    }

    public function update(Meetup $meetup): void
    {
        $this->updateInCollection($meetup);
    }

    public function findByAttendee(Person $person): array
    {
        return $this->filterCollection(
            fn(Meetup $meetup) => in_array($person->getId(), $meetup->getAttendeeIds())
        );
    }

    public function findById(string $id): Meetup
    {
        return $this->itemWithId($id);
    }
}
