<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\PersonRepository;

class QueryResolver extends FieldResolver
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

    protected function resolveMeetups()
    {
        return $this->meetupRepository->findAll();
    }

    protected function resolvePeople()
    {
        return $this->personRepository->findAll();
    }
}
