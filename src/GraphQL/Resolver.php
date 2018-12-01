<?php

namespace MeetupQL\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;

interface Resolver
{
    /**
     * @param $source
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed|null
     */
    public function resolve($source, $args, $context, ResolveInfo $info);
}
