<?php

namespace MeetupQL\Tests;

use MeetupQL\Tests\GraphQL\GraphQLTestCase;

class MeetupQueryTest extends GraphQLTestCase
{
    public function testMeetupCanBeRetrieved()
    {
        $meetup = $this->meetup()->build();
        $this->meetupRepository->add($meetup);

        $query = <<<GRAPHQL
            query {
              meetups {
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

        $this->assertCount(1, $result['data']['meetups']['edges']);
        $meetupNode = $result['data']['meetups']['edges'][0]['node'];
        $this->assertSame($meetup->getId(), $meetupNode['id']);
        $this->assertSame($meetup->getName(), $meetupNode['name']);
    }

    public function testOrganiserCanBeRetrieved()
    {
        $person = $this->person()->build();
        $this->personRepository->add($person);

        $meetup = $this->meetup()->withOrganiser($person)->build();
        $this->meetupRepository->add($meetup);

        $query = <<<GRAPHQL
            query {
              meetups {
                edges {
                  node {
                    organiser {
                      id
                      name
                    }
                  }
                }
              }
            }
        GRAPHQL;

        $result = $this->query($query);

        $this->assertCount(1, $result['data']['meetups']['edges']);
        $meetupNode = $result['data']['meetups']['edges'][0]['node'];
        $this->assertSame($person->getId(), $meetupNode['organiser']['id']);
        $this->assertSame($person->getName(), $meetupNode['organiser']['name']);
    }

    public function testPresenterCanBeRetrieved()
    {
        $person = $this->person()->build();
        $this->personRepository->add($person);

        $meetup = $this->meetup()->withPresenter($person)->build();
        $this->meetupRepository->add($meetup);

        $query = <<<GRAPHQL
            query {
              meetups {
                edges {
                  node {
                    presenter {
                      id
                      name
                    }
                  }
                }
              }
            }
        GRAPHQL;

        $result = $this->query($query);

        $this->assertCount(1, $result['data']['meetups']['edges']);
        $meetupNode = $result['data']['meetups']['edges'][0]['node'];
        $this->assertSame($person->getId(), $meetupNode['presenter']['id']);
        $this->assertSame($person->getName(), $meetupNode['presenter']['name']);
    }

    public function testAttendeeCanBeRetrieved()
    {
        $person = $this->person()->build();
        $this->personRepository->add($person);

        $meetup = $this->meetup()->withAttendees([$person])->build();
        $this->meetupRepository->add($meetup);

        $query = <<<GRAPHQL
            query {
              meetups {
                edges {
                  node {
                    attendees {
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

        $this->assertCount(1, $result['data']['meetups']['edges']);
        $meetupNode = $result['data']['meetups']['edges'][0]['node'];
        $this->assertSame($person->getId(), $meetupNode['attendees']['edges'][0]['node']['id']);
        $this->assertSame($person->getName(), $meetupNode['attendees']['edges'][0]['node']['name']);
    }

    public function testMeetupsCanBePaginated()
    {
        $meetup1 = $this->meetup()->build();
        $meetup2 = $this->meetup()->build();
        $this->meetupRepository->add($meetup1);
        $this->meetupRepository->add($meetup2);

        $firstQuery = <<<GRAPHQL
            query {
              meetups (first: 1) {
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

        $this->assertCount(1, $result['data']['meetups']['edges']);
        $this->assertTrue($result['data']['meetups']['pageInfo']['hasNextPage']);
        $this->assertSame($meetup1->getId(), $result['data']['meetups']['edges'][0]['node']['id']);

        $cursor = $result['data']['meetups']['edges'][0]['cursor'];

        $secondQuery = <<<GRAPHQL
            query {
              meetups (first: 1, after: "{$cursor}") {
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

        $this->assertCount(1, $result['data']['meetups']['edges']);
        $this->assertFalse($result['data']['meetups']['pageInfo']['hasNextPage']);
        $this->assertSame($meetup2->getId(), $result['data']['meetups']['edges'][0]['node']['id']);
    }
}
