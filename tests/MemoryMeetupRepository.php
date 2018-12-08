<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Meetup;
use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\Person;

class MemoryMeetupRepository implements MeetupRepository
{
    /** @var Meetup[] */
    private $meetups;

    /**
     * MemoryMeetupRepository constructor.
     */
    public function __construct()
    {
        $this->meetups = [];
    }

    public function findAll(): array
    {
        return $this->meetups;
    }

    public function add(Meetup $meetup): void
    {
        $this->meetups[] = $meetup;
    }

    public function findByAttendee(Person $person): array
    {
        return array_filter(
            $this->meetups,
            function (Meetup $meetup) use ($person) {
                return in_array($person->getId(), $meetup->getAttendeeIds());
            }
        );
    }

    public function findById(string $id): Meetup
    {
        foreach ($this->meetups as $meetup) {
            if ($meetup->getId() === $id) {
                return $meetup;
            }
        }

        throw new \InvalidArgumentException("No meetup found with ID {$id}");
    }
}
