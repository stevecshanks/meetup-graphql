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
    private MeetupRepository $meetupRepository;
    private PersonRepository $personRepository;
    private Schema $schema;
    private DefaultResolver $defaultResolver;
    private bool $debugMode = false;

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

    public function enableDebug()
    {
        $this->debugMode = true;
    }

    public function execute(?string $query): array
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
        return $result->toArray($this->debugOptions());
    }

    private function debugOptions()
    {
        if (!$this->debugMode) {
            return null;
        }

        return Debug::INCLUDE_DEBUG_MESSAGE | Debug::RETHROW_INTERNAL_EXCEPTIONS;
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
            'Mutation',
            new MutationResolver($resolverRegistry, $this->meetupRepository, $this->personRepository)
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
