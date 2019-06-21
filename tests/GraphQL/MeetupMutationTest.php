<?php

namespace MeetupQL\Tests\GraphQL;

class MeetupMutationTest extends GraphQLTestCase
{
    public function testAttendeesCanRegisterForMeetups()
    {
        $meetup = $this->meetup()->build();
        $this->meetupRepository->add($meetup);

        $person = $this->person()->build();
        $this->personRepository->add($person);

        $query = <<<GRAPHQL
            mutation {
              attendMeetup (input: {
                meetupId: "{$meetup->getId()}",
                attendeeId: "{$person->getId()}",
                clientMutationId: "my mutation"
              }) {
                clientMutationId
                meetup {
                  id
                }
                attendee {
                  id
                }
              }
            }
        GRAPHQL;

        $result = $this->query($query);

        $this->assertSame($meetup->getId(), $result['data']['attendMeetup']['meetup']['id']);
        $this->assertSame($person->getId(), $result['data']['attendMeetup']['attendee']['id']);
        $this->assertSame('my mutation', $result['data']['attendMeetup']['clientMutationId']);

        $this->assertSame([$person->getId()], $meetup->getAttendeeIds());
    }
}
