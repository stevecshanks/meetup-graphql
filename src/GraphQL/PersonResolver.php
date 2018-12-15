<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\Person;

class PersonResolver extends DefaultResolver
{
    /** @var MeetupRepository */
    private $meetupRepository;

    /**
     * PersonResolver constructor.
     * @param ResolverRegistry $resolverRegistry
     * @param MeetupRepository $meetupRepository
     */
    public function __construct(ResolverRegistry $resolverRegistry, MeetupRepository $meetupRepository)
    {
        parent::__construct($resolverRegistry);
        $this->meetupRepository = $meetupRepository;
    }

    protected function resolveMeetupsAttending(Person $person, $args)
    {
        return $this->connectionTo($this->meetupRepository->findByAttendee($person), $args);
    }
}
