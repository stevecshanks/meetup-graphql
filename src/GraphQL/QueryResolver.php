<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\PersonRepository;

class QueryResolver extends DefaultResolver
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

    protected function resolveNode($source, $args)
    {
        switch (GlobalId::typeOf($args['id'])) {
            case 'Meetup':
                return $this->meetupRepository->findById($args['id']);
            case 'Person':
                return $this->personRepository->findById($args['id']);
            default:
                return null;
        }
    }

    protected function resolveMeetups($source, $args)
    {
        return $this->connectionTo($this->meetupRepository->findAll(), $args);
    }

    protected function resolvePeople()
    {
        return $this->connectionTo($this->personRepository->findAll());
    }
}
