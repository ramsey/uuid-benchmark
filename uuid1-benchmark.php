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

if (class_exists('\Rhumsaa\Uuid\Uuid')) {
    $watch->start('rhumsaa');

    for ($i = 0; $i < ITERATIONS; ++$i) {
        $x = (string) \Rhumsaa\Uuid\Uuid::uuid1();
    }

    $results['rhumsaa'] = $watch->stop('rhumsaa');
}


/**
 * Using Ramsey\Uuid at 3.0.0-alpha1
 */

if (class_exists('\Ramsey\Uuid\Uuid')) {
    $factory = new \Ramsey\Uuid\UuidFactory();
    if (!method_exists($factory, 'getTimeGenerator')) {
        $watch->start('ramsey-3.0.0-alpha1');

        for ($i = 0; $i < ITERATIONS; ++$i) {
            $x = (string) \Ramsey\Uuid\Uuid::uuid1();
        }

        $results['ramsey-3.0.0-alpha1'] = $watch->stop('ramsey-3.0.0-alpha1');
    }
}


/**
 * Using Ramsey\Uuid with the default time generator
 */

if (class_exists('\Ramsey\Uuid\Generator\DefaultTimeGenerator')) {
    $watch->start('ramsey-default-generator');

    for ($i = 0; $i < ITERATIONS; ++$i) {
        $x = (string) \Ramsey\Uuid\Uuid::uuid1();
    }

    $results['ramsey-default-generator'] = $watch->stop('ramsey-default-generator');
}


/**
 * Using Ramsey\Uuid with a pecl-uuid time generator
 */

if (class_exists('\Ramsey\Uuid\Generator\PeclUuidTimeGenerator')) {
    $watch->start('ramsey-pecl-generator');

    $uuidFactory = new \Ramsey\Uuid\UuidFactory();
    $uuidFactory->setTimeGenerator(new \Ramsey\Uuid\Generator\PeclUuidTimeGenerator());
    \Ramsey\Uuid\Uuid::setFactory($uuidFactory);

    for ($i = 0; $i < ITERATIONS; ++$i) {
        $x = (string) \Ramsey\Uuid\Uuid::uuid1();
    }

    $results['ramsey-pecl-generator'] = $watch->stop('ramsey-pecl-generator');
}


foreach ($results as $name => $result) {
    printf('% 24s | %.04f sec/%d | %.07f sec/one' . PHP_EOL,
        strtoupper($name),
        $result->getDuration() / 1000,
        ITERATIONS,
        $result->getDuration() / 1000 / ITERATIONS);
}
