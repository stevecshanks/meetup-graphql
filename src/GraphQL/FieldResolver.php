<?php

namespace MeetupQL\GraphQL;

use GraphQL\Executor\Executor;
use GraphQL\Type\Definition\ResolveInfo;

class FieldResolver
{
    /**
     * Wrap the default field resolver to allow use of getter methods on objects
     *
     * @param $source
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed|null
     */
    public function __invoke($source, $args, $context, ResolveInfo $info)
    {
        $method = 'get' . ucfirst($info->fieldName);
        if (is_object($source) && method_exists($source, $method)) {
            return $source->{$method}();
        }

        return Executor::defaultFieldResolver($source, $args, $context, $info);
    }

}
