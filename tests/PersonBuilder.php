<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Person;
use MeetupQL\GraphQL\GlobalId;

class PersonBuilder
{
    /** @var string[] */
    private $interests;

    /**
     * PersonBuilder constructor.
     */
    public function __construct()
    {
        $this->interests = [];
    }

    public function build(): Person
    {
        return new Person(
            $this->randomId(),
            'a person',
            'a company',
            $this->interests
        );
    }

    public function interestedIn(string $interest): PersonBuilder
    {
        $this->interests[] = $interest;

        return $this;
    }

    private function randomId()
    {
        return GlobalId::encode('Person', uniqid('', true));
    }
}
