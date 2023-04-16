<?php

namespace Core\Shared;

use Illuminate\Support\Str;

class Uuid
{
    public static function make(): string
    {
        return Str::uuid();
    }
}
