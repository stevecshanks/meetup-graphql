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
                id
                name
              }
            }
GRAPHQL;

        $result = $this->api->query($query);

        $this->assertCount(1, $result['data']['meetups']);
        $this->assertSame($meetup->getId(), $result['data']['meetups'][0]['id']);
        $this->assertSame($meetup->getName(), $result['data']['meetups'][0]['name']);
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
                organiser {
                  id
                  name
                }
              }
            }
GRAPHQL;

        $result = $this->api->query($query);

        $this->assertCount(1, $result['data']['meetups']);
        $this->assertSame($person->getId(), $result['data']['meetups'][0]['organiser']['id']);
        $this->assertSame($person->getName(), $result['data']['meetups'][0]['organiser']['name']);
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
                presenter {
                  id
                  name
                }
              }
            }
GRAPHQL;

        $result = $this->api->query($query);

        $this->assertCount(1, $result['data']['meetups']);
        $this->assertSame($person->getId(), $result['data']['meetups'][0]['presenter']['id']);
        $this->assertSame($person->getName(), $result['data']['meetups'][0]['presenter']['name']);
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
                attendees {
                  id
                  name
                }
              }
            }
GRAPHQL;

        $result = $this->api->query($query);

        $this->assertCount(1, $result['data']['meetups']);
        $this->assertSame($person->getId(), $result['data']['meetups'][0]['attendees'][0]['id']);
        $this->assertSame($person->getName(), $result['data']['meetups'][0]['attendees'][0]['name']);
    }
}
