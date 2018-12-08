<?php

namespace MeetupQL\GraphQL;

use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;
use GraphQL\Utils\BuildSchema;
use MeetupQL\Domain\MeetupRepository;
use MeetupQL\Domain\PersonRepository;

class Api
{
    /** @var MeetupRepository */
    private $meetupRepository;

    /** @var PersonRepository */
    private $personRepository;

    /** @var Schema */
    private $schema;

    /** @var DefaultResolver */
    private $defaultResolver;

    /**
     * Api constructor.
     * @param MeetupRepository $meetupRepository
     * @param PersonRepository $personRepository
     */
    public function __construct(MeetupRepository $meetupRepository, PersonRepository $personRepository)
    {
        $this->meetupRepository = $meetupRepository;
        $this->personRepository = $personRepository;

        $this->buildSchema();

        $this->registerResolvers();
    }

    public function query(string $query): array
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            $query,
            null,
            null,
            null,
            null,
            $this->defaultResolver
        );
        return $result->toArray(Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE);
    }

    private function buildSchema()
    {
        $schemaFile = file_get_contents(__DIR__ . '/../../schema.graphql');

        $typeConfigDecorator = function ($typeConfig) {
            if ($typeConfig['name'] === 'Node') {
                $typeConfig['resolveType'] = function ($value) {
                    return GlobalId::typeOf($value->getId());
                };
            }
            return $typeConfig;
        };

        $this->schema = BuildSchema::build($schemaFile, $typeConfigDecorator);
    }

    private function registerResolvers()
    {
        $resolverRegistry = new ResolverRegistry();
        $resolverRegistry->add(
            'Query',
            new QueryResolver($resolverRegistry, $this->meetupRepository, $this->personRepository)
        );
        $resolverRegistry->add(
            'Meetup',
            new MeetupResolver($resolverRegistry, $this->personRepository)
        );
        $resolverRegistry->add(
            'Person',
            new PersonResolver($resolverRegistry, $this->meetupRepository)
        );

        $this->defaultResolver = new DefaultResolver($resolverRegistry);
    }
}
