<?php

namespace MeetupQL\Tests\GraphQL;

use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\PersonRepository;
use MeetupQL\GraphQL\Api;
use MeetupQL\Tests\MeetupBuilder;
use MeetupQL\Tests\MemoryMeetupRepository;
use MeetupQL\Tests\MemoryPersonRepository;
use MeetupQL\Tests\PersonBuilder;
use PHPUnit\Framework\TestCase;

abstract class GraphQLTestCase extends TestCase
{
    /** @var MeetupRepository */
    protected $meetupRepository;

    /** @var PersonRepository */
    protected $personRepository;

    /** @var Api */
    protected $api;

    protected function setUp()
    {
        $this->meetupRepository = new MemoryMeetupRepository();
        $this->personRepository = new MemoryPersonRepository();

        $this->api = new Api($this->meetupRepository, $this->personRepository);
        $this->api->enableDebug();
    }

    protected function query(string $query)
    {
        $result = $this->api->execute($query);

        if (isset($result['errors']) && !empty($result['errors'])) {
            $this->fail($result['errors'][0]['message']);
        }

        return $result;
    }

    protected function meetup(): MeetupBuilder
    {
        return new MeetupBuilder();
    }

    protected function person(): PersonBuilder
    {
        return new PersonBuilder();
    }
}
