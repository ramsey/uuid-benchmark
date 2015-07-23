<?php

namespace Ramsey\Uuid\Benchmark;

use Ramsey\Uuid\Generator\RandomGeneratorInterface;

class PeclRandomGenerator implements RandomGeneratorInterface
{
    public function generate($length)
    {
        $uuid = uuid_create(UUID_TYPE_RANDOM);
        return uuid_parse($uuid);
    }
}
