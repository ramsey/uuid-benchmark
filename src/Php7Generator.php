<?php

namespace Ramsey\Uuid\Benchmark;

use Ramsey\Uuid\Generator\RandomGeneratorInterface;

class Php7Generator implements RandomGeneratorInterface
{
    public function generate($length)
    {
        return random_bytes($length);
    }
}
