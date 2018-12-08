<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Person;
use MeetupQL\GraphQL\GlobalId;

class PersonBuilder
{
    public function build(): Person
    {
        return new Person(
            $this->randomId(),
            'a person',
            'a company',
            []
        );
    }

    private function randomId()
    {
        return GlobalId::encode('Person', uniqid('', true));
    }
}
