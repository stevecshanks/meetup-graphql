<?php

namespace MeetupQL\GraphQL;

class IdService
{
    public static function encode(string $type, $id): string
    {
        return base64_encode("{$type}:{$id}");
    }
}
