<?php

namespace MeetupQL\Tests\GraphQL;

class NodeQueryTest extends GraphQLTestCase
{
    public function testMeetupCanBeRetrievedById()
    {
        $meetup = $this->meetup()->build();
        $this->meetupRepository->add($meetup);

        $query = <<<GRAPHQL
            query {
              node (id: "{$meetup->getId()}") {
                id
                ... on Meetup {
                  name
                }
              }
            }
        GRAPHQL;

        $result = $this->query($query);

        $this->assertSame($meetup->getId(), $result['data']['node']['id']);
        $this->assertSame($meetup->getName(), $result['data']['node']['name']);
    }

    public function testPersonCanBeRetrievedById()
    {
        $person = $this->person()->build();
        $this->personRepository->add($person);

        $query = <<<GRAPHQL
            query {
              node (id: "{$person->getId()}") {
                id
                ... on Person {
                  name
                }
              }
            }
        GRAPHQL;

        $result = $this->query($query);

        $this->assertSame($person->getId(), $result['data']['node']['id']);
        $this->assertSame($person->getName(), $result['data']['node']['name']);
    }
}
