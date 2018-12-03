<?php

namespace MeetupQL\Tests;

use MeetupQL\Domain\Person;
use MeetupQL\GraphQL\IdService;

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
        return IdService::encode('Person', uniqid('', true));
    }
}
