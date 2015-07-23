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
    $x = uuid_create(UUID_TYPE_RANDOM);
}

$results['pecl'] = $watch->stop('pecl');


/**
 * Using the older Rhumsaa\Uuid version of the library
 */

$watch->start('rhumsaa');

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Rhumsaa\Uuid\Uuid::uuid4();
}

$results['rhumsaa'] = $watch->stop('rhumsaa');


/**
 * Using Ramsey\Uuid with pecl-uuid
 */

$watch->start('ramsey-pecl');

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-pecl'] = $watch->stop('ramsey-pecl');


/**
 * Using Ramsey\Uuid without pecl-uuid
 */

$watch->start('ramsey-nopecl');

\Ramsey\Uuid\Uuid::setFactory(new \Ramsey\Uuid\UuidFactory());

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-nopecl'] = $watch->stop('ramsey-nopecl');


/**
 * Using Ramsey\Uuid with ircmaxell/random-lib
 */

$watch->start('ramsey-randomlib');

// Use medium-strength generator
$randomLibFactory = new \RandomLib\Factory();
$randomLibGenerator = $randomLibFactory->getMediumStrengthGenerator();

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setRandomGenerator(
    new \Ramsey\Uuid\Generator\RandomLibAdapter($randomLibGenerator)
);
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-randomlib'] = $watch->stop('ramsey-randomlib');


/**
 * Using Ramsey\Uuid with PHP 7 random_bytes()
 */

if (PHP_MAJOR_VERSION >= 7) {
    $watch->start('ramsey-php7');

    $uuidFactory = new \Ramsey\Uuid\UuidFactory();
    $uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Benchmark\Php7Generator());
    \Ramsey\Uuid\Uuid::setFactory($uuidFactory);

    for ($i = 0; $i < ITERATIONS; ++$i) {
        $x = (string) \Ramsey\Uuid\Uuid::uuid4();
    }

    $results['ramsey-php7'] = $watch->stop('ramsey-php7');
}


foreach ($results as $name => $result) {
    printf('% 16s | %.04f sec/%d | %.07f sec/one' . PHP_EOL,
        strtoupper($name),
        $result->getDuration() / 1000,
        ITERATIONS,
        $result->getDuration() / 1000 / ITERATIONS);
}
