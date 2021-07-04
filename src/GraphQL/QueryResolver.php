<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\PersonRepository;

class QueryResolver extends DefaultResolver
{
    private MeetupRepository $meetupRepository;
    private PersonRepository $personRepository;

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

    protected function resolveMeetups($source, array $args)
    {
        return $this->connectionTo($this->meetupRepository->findAll(), $args);
    }

    protected function resolvePeople($source, array $args)
    {
        if (isset($args['interestedIn'])) {
            return $this->connectionTo($this->personRepository->findByInterest($args['interestedIn']), $args);
        }
        return $this->connectionTo($this->personRepository->findAll(), $args);
    }
}
