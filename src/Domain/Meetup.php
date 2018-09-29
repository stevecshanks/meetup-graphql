<?php

namespace MeetupQL\Domain;

class Meetup
{
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var Address */
    private $location;

    /** @var string */
    private $start;

    /** @var Person */
    private $organiser;

    /** @var Person */
    private $presenter;

    /** @var Person[] */
    private $attendees;

    /**
     * Meetup constructor.
     * @param int $id
     * @param string $name
     * @param Address $location
     * @param string $start
     * @param Person $organiser
     * @param Person $presenter
     * @param Person[] $attendees
     */
    public function __construct(
        int $id,
        string $name,
        Address $location,
        string $start,
        Person $organiser,
        Person $presenter,
        array $attendees
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->start = $start;
        $this->organiser = $organiser;
        $this->presenter = $presenter;
        $this->attendees = $attendees;
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @return Person
     */
    public function getOrganiser(): Person
    {
        return $this->organiser;
    }

    /**
     * @return Person
     */
    public function getPresenter(): Person
    {
        return $this->presenter;
    }

    /**
     * @return Person[]
     */
    public function getAttendees(): array
    {
        return $this->attendees;
    }
}
