<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Address;
use MeetupQL\Domain\Meetup;
use MeetupQL\Domain\Person;
use MeetupQL\GraphQL\GlobalId;

class MeetupBuilder
{
    /** @var string */
    private $organiserId;

    /** @var string */
    private $presenterId;

    /** @var string[] */
    private $attendeeIds;

    /**
     * MeetupBuilder constructor.
     */
    public function __construct()
    {
        $this->organiserId = $this->randomId();
        $this->presenterId = $this->randomId();
        $this->attendeeIds = [];
    }

    public function build(): Meetup
    {
        return new Meetup(
            $this->randomId(),
            'a meetup',
            new Address(
                'a company',
                'a street',
                'a city',
                'a postcode'
            ),
            '2019-01-01T09:00:00+0000',
            $this->organiserId,
            $this->presenterId,
            $this->attendeeIds
        );
    }

    public function withOrganiser(Person $organiser): MeetupBuilder
    {
        $this->organiserId = $organiser->getId();

        return $this;
    }

    public function withPresenter(Person $presenter): MeetupBuilder
    {
        $this->presenterId = $presenter->getId();

        return $this;
    }

    public function withAttendees(array $attendees): MeetupBuilder
    {
        $this->attendeeIds = array_map(
            fn(Person $attendee) => $attendee->getId(),
            $attendees
        );

        return $this;
    }

    private function randomId()
    {
        return GlobalId::encode('Meetup', uniqid('', true));
    }
}
