<?php

namespace MeetupQL\GraphQL;

use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;

class DefaultResolver implements Resolver
{
    /** @var ResolverRegistry */
    private $resolverRegistry;

    /**
     * FieldResolver constructor.
     * @param ResolverRegistry $resolverRegistry
     */
    public function __construct(ResolverRegistry $resolverRegistry)
    {
        $this->resolverRegistry = $resolverRegistry;
    }

    /**
     * @param $source
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed|null
     */
    public function __invoke($source, $args, $context, ResolveInfo $info)
    {
        $resolverForType = $this->resolverRegistry->get($info->parentType);
        if ($resolverForType) {
            return $resolverForType->resolve($source, $args, $context, $info);
        }

        return $this->resolve($source, $args, $context, $info);
    }

    /**
     * Wrap the default field resolver to allow use of getter methods on objects
     *
     * @param $source
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed|null
     */
    public function resolve($source, $args, $context, ResolveInfo $info)
    {
        $resolverMethod = 'resolve' . ucfirst($info->fieldName);
        if (method_exists($this, $resolverMethod)) {
            return $this->$resolverMethod($source, $args, $context, $info);
        }

        $getMethod = 'get' . ucfirst($info->fieldName);
        if (is_object($source) && method_exists($source, $getMethod)) {
            return $source->{$getMethod}();
        }

        return Executor::defaultFieldResolver($source, $args, $context, $info);
    }
}
