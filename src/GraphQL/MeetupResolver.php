<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\Meetup;
use MeetupQL\Domain\PersonRepository;

class MeetupResolver extends DefaultResolver
{
    private PersonRepository $personRepository;

    /**
     * MeetupResolver constructor.
     * @param ResolverRegistry $resolverRegistry
     * @param PersonRepository $personRepository
     */
    public function __construct(ResolverRegistry $resolverRegistry, PersonRepository $personRepository)
    {
        parent::__construct($resolverRegistry);
        $this->personRepository = $personRepository;
    }

    protected function resolveOrganiser(Meetup $meetup)
    {
        return $this->personRepository->findById($meetup->getOrganiserId());
    }

    protected function resolvePresenter(Meetup $meetup)
    {
        return $this->personRepository->findById($meetup->getPresenterId());
    }

    protected function resolveAttendees(Meetup $meetup, array $args)
    {
        return $this->connectionTo(
            array_map(
                fn(string $id) => $this->personRepository->findById($id),
                $meetup->getAttendeeIds()
            ),
            $args
        );
    }
}
