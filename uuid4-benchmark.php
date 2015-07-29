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

$watch->start('rhumsaa-openssl');

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Rhumsaa\Uuid\Uuid::uuid4();
}

$results['rhumsaa-openssl'] = $watch->stop('rhumsaa-openssl');

/**
 * Using the older Rhumsaa\Uuid version of the library without OpenSSL
 */
$watch->start('rhumsaa-mtrand');

\Rhumsaa\Uuid\Uuid::$forceNoOpensslRandomPseudoBytes = true;

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Rhumsaa\Uuid\Uuid::uuid4();
}

$results['rhumsaa-mtrand'] = $watch->stop('rhumsaa-mtrand');


/**
 * Using Ramsey\Uuid with PHP 7 random_bytes()
 */

if (PHP_MAJOR_VERSION >= 7) {
    $watch->start('ramsey-php7');

    $uuidFactory = new \Ramsey\Uuid\UuidFactory();
    $uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Generator\RandomBytesGenerator());
    \Ramsey\Uuid\Uuid::setFactory($uuidFactory);

    for ($i = 0; $i < ITERATIONS; ++$i) {
        $x = (string) \Ramsey\Uuid\Uuid::uuid4();
    }

    $results['ramsey-php7'] = $watch->stop('ramsey-php7');
}


/**
 * Using Ramsey\Uuid with OpenSSL
 */

$watch->start('ramsey-openssl');

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Generator\OpenSslGenerator());
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-openssl'] = $watch->stop('ramsey-openssl');


/**
 * Using Ramsey\Uuid with MtRand
 */

$watch->start('ramsey-mtrand');

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Generator\MtRandGenerator());
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-mtrand'] = $watch->stop('ramsey-mtrand');


/**
 * Using Ramsey\Uuid with pecl-uuid
 */

$watch->start('ramsey-pecl');

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Generator\PeclUuidRandomGenerator());
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-pecl'] = $watch->stop('ramsey-pecl');


/**
 * Using Ramsey\Uuid with ircmaxell/random-lib
 */

$watch->start('ramsey-randomlib');

$uuidFactory = new \Ramsey\Uuid\UuidFactory();
$uuidFactory->setRandomGenerator(new \Ramsey\Uuid\Generator\RandomLibAdapter());
\Ramsey\Uuid\Uuid::setFactory($uuidFactory);

for ($i = 0; $i < ITERATIONS; ++$i) {
    $x = (string) \Ramsey\Uuid\Uuid::uuid4();
}

$results['ramsey-randomlib'] = $watch->stop('ramsey-randomlib');


foreach ($results as $name => $result) {
    printf('% 21s | %.04f sec/%d | %.07f sec/one' . PHP_EOL,
        strtoupper($name),
        $result->getDuration() / 1000,
        ITERATIONS,
        $result->getDuration() / 1000 / ITERATIONS);
}
