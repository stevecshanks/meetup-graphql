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
                edges {
                  node {
                    id
                    name
                  }
                }
              }
            }
GRAPHQL;

        $result = $this->query($query);

        $this->assertCount(1, $result['data']['people']['edges']);
        $personNode = $result['data']['people']['edges'][0]['node'];
        $this->assertSame($person->getId(), $personNode['id']);
        $this->assertSame($person->getName(), $personNode['name']);
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
                edges {
                  node {
                    meetupsAttending {
                      edges {
                        node {
                          id
                          name
                        }
                      }
                    }
                  }
                }
              }
            }
GRAPHQL;

        $result = $this->query($query);

        $this->assertCount(1, $result['data']['people']['edges']);
        $personNode = $result['data']['people']['edges'][0]['node'];
        $this->assertSame($meetup->getId(), $personNode['meetupsAttending']['edges'][0]['node']['id']);
        $this->assertSame($meetup->getName(), $personNode['meetupsAttending']['edges'][0]['node']['name']);
    }

    public function testPeopleCanBePaginated()
    {
        $person1 = $this->person()->build();
        $person2 = $this->person()->build();
        $this->personRepository->add($person1);
        $this->personRepository->add($person2);

        $firstQuery = <<<GRAPHQL
            query {
              people (first: 1) {
                pageInfo {
                  hasNextPage
                }
                edges {
                  cursor
                  node {
                    id
                  }
                }
              }
            }
GRAPHQL;

        $result = $this->query($firstQuery);

        $this->assertCount(1, $result['data']['people']['edges']);
        $this->assertTrue($result['data']['people']['pageInfo']['hasNextPage']);
        $this->assertSame($person1->getId(), $result['data']['people']['edges'][0]['node']['id']);

        $cursor = $result['data']['people']['edges'][0]['cursor'];

        $secondQuery = <<<GRAPHQL
            query {
              people (first: 1, after: "{$cursor}") {
                pageInfo {
                  hasNextPage
                }
                edges {
                  node {
                    id
                  }
                }
              }
            }
GRAPHQL;

        $result = $this->query($secondQuery);

        $this->assertCount(1, $result['data']['people']['edges']);
        $this->assertFalse($result['data']['people']['pageInfo']['hasNextPage']);
        $this->assertSame($person2->getId(), $result['data']['people']['edges'][0]['node']['id']);
    }
}
