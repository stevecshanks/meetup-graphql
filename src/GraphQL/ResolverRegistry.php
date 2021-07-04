<?php

namespace MeetupQL\GraphQL;

class ResolverRegistry
{
    /** @var Resolver[] */
    private array $resolvers;

    public function __construct()
    {
        $this->resolvers = [];
    }

    public function add(string $type, Resolver $resolver): void
    {
        $this->resolvers[$type] = $resolver;
    }

    public function get(string $type): ?Resolver
    {
        if (!isset($this->resolvers[$type])) {
            return null;
        }

        return $this->resolvers[$type];
    }
}
