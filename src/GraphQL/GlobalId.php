<?php

namespace MeetupQL\GraphQL;

class GlobalId
{
    public static function encode(string $type, $id): string
    {
        return base64_encode("{$type}:{$id}");
    }

    public static function typeOf(string $globalId): string
    {
        $decoded = base64_decode($globalId);
        if (strpos($decoded, ':') !== false) {
            return explode(':', $decoded)[0];
        }

        throw new \InvalidArgumentException("Invalid global ID {$globalId}");
    }
}
