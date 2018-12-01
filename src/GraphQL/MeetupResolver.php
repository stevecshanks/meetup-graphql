<?php

namespace MeetupQL\GraphQL;

use MeetupQL\Domain\Meetup;
use MeetupQL\Domain\PersonRepository;

class MeetupResolver extends DefaultResolver
{
    /** @var PersonRepository */
    private $personRepository;

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

    protected function resolveAttendees(Meetup $meetup)
    {
        return array_map(
            function (string $id) {
                return $this->personRepository->findById($id);
            },
            $meetup->getAttendeeIds()
        );
    }
}
