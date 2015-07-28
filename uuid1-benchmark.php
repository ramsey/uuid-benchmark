<?php

require_once __DIR__ . '/vendor/autoload.php';

$watch = new \Symfony\Component\Stopwatch\Stopwatch();

define('ITERATIONS', 10000);

$results = [];


/**
 * Using pecl-uuid by itself
 */

$watch->start('pecl');

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = uuid_create(UUID_TYPE_TIME);
}

$results['pecl'] = $watch->stop('pecl');


/**
 * Using the older Rhumsaa\Uuid version of the library
 */

$watch->start('rhumsaa');

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Rhumsaa\Uuid\Uuid::uuid1();
}

$results['rhumsaa'] = $watch->stop('rhumsaa');


/**
 * Using Ramsey\Uuid with default time generator
 */

$watch->start('ramsey-default');

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid1();
}

$results['ramsey-default'] = $watch->stop('ramsey-default');


/**
 * Using Ramsey\Uuid with a pecl-uuid time generator
 */

$watch->start('ramsey-pecl');

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setTimeGenerator(new \Ramsey\Uuid\Generator\PeclUuidTimeGenerator());
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid1();
}

$results['ramsey-pecl'] = $watch->stop('ramsey-pecl');


foreach ($results as $name => $result) {
    printf('% 24s | %.04f sec/%d | %.07f sec/one' . PHP_EOL,
        strtoupper($name),
        $result->getDuration() / 1000,
        ITERATIONS,
        $result->getDuration() / 1000 / ITERATIONS);
}
