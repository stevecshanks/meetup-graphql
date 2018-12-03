<?php

namespace MeetupQL\Tests;

use MeetupQL\Tests\GraphQL\GraphQLTestCase;

class PeopleQueryTest extends GraphQLTestCase
{
    public function testPersonCanBeRetrieved()
    {
        $person = $this->person()->build();
        $this->personRepository->add($person);

        $query = <<<GRAPHQL
            query {
              people {
                id
                name
              }
            }
GRAPHQL;

        $result = $this->api->query($query);

        $this->assertCount(1, $result['data']['people']);
        $this->assertSame($person->getId(), $result['data']['people'][0]['id']);
        $this->assertSame($person->getName(), $result['data']['people'][0]['name']);
    }

    public function testMeetupCanBeRetrieved()
    {
        $person = $this->person()->build();
        $this->personRepository->add($person);

        $meetup = $this->meetup()->withAttendees([$person])->build();
        $this->meetupRepository->add($meetup);

        $query = <<<GRAPHQL
            query {
              people {
                meetupsAttending {
                  id
                  name
                }
              }
            }
GRAPHQL;

        $result = $this->api->query($query);

        $this->assertCount(1, $result['data']['people']);
        $this->assertSame($meetup->getId(), $result['data']['people'][0]['meetupsAttending'][0]['id']);
        $this->assertSame($meetup->getName(), $result['data']['people'][0]['meetupsAttending'][0]['name']);
    }
}
