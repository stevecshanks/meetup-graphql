<?php

namespace MeetupQL\Domain;

class Meetup
{
    private string $id;
    private string $name;
    private Address $location;
    private string $start;
    private string $organiserId;
    private string $presenterId;

    /** @var string[] */
    private array $attendeeIds;

    /**
     * Meetup constructor.
     * @param string $id
     * @param string $name
     * @param Address $location
     * @param string $start
     * @param string $organiserId
     * @param string $presenterId
     * @param string[] $attendeeIds
     */
    public function __construct(
        string $id,
        string $name,
        Address $location,
        string $start,
        string $organiserId,
        string $presenterId,
        array $attendeeIds
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->start = $start;
        $this->organiserId = $organiserId;
        $this->presenterId = $presenterId;
        $this->attendeeIds = $attendeeIds;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Address
     */
    public function getLocation(): Address
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getOrganiserId(): string
    {
        return $this->organiserId;
    }

    /**
     * @return string
     */
    public function getPresenterId(): string
    {
        return $this->presenterId;
    }

    /**
     * @return string[]
     */
    public function getAttendeeIds(): array
    {
        return $this->attendeeIds;
    }

    public function registerAttendee(Person $attendee)
    {
        $this->attendeeIds[] = $attendee->getId();
    }
}
