<?php

namespace MeetupQL\GraphQL;

class IdService
{
    public static function encode(string $type, int $numericId): string
    {
        return base64_encode("{$type}:{$numericId}");
    }
}
