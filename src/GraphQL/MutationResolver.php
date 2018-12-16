<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\PersonRepository;

class MutationResolver extends DefaultResolver
{
    /** @var MeetupRepository */
    private $meetupRepository;

    /** @var PersonRepository */
    private $personRepository;

    /**
     * QueryResolver constructor.
     * @param ResolverRegistry $resolverRegistry
     * @param MeetupRepository $meetupRepository
     * @param PersonRepository $personRepository
     */
    public function __construct(
        ResolverRegistry $resolverRegistry,
        MeetupRepository $meetupRepository,
        PersonRepository $personRepository
    ) {
        parent::__construct($resolverRegistry);
        $this->meetupRepository = $meetupRepository;
        $this->personRepository = $personRepository;
    }

    public function resolveAttendMeetup($source, $args)
    {
        $meetup = $this->meetupRepository->findById($args['input']['meetupId']);
        $attendee = $this->personRepository->findById($args['input']['attendeeId']);

        $meetup->registerAttendee($attendee);

        $this->meetupRepository->update($meetup);

        return [
            'meetup' => $meetup,
            'attendee' => $attendee,
            'clientMutationId' => $args['input']['clientMutationId']
        ];
    }
}
