# ramsey/uuid Benchmark Tests

Tip of the hat to [@jaymecd](https://github.com/jaymecd) for creating the
[initial script and benchmark tests](https://gist.github.com/jaymecd/7dbac8908bacf54b6db6)
to bring attention to performance drops between ramsey/uuid releases.

Also, refer to [ramsey/uuid#56](https://github.com/ramsey/uuid/issues/56).

## Get set up for the benchmarks

Some useful setup (using [phpbrew](https://github.com/phpbrew/phpbrew)):

```
phpbrew install next as php-7.0.0-dev
phpbrew use php-7.0.0-dev
wget http://pecl.php.net/get/uuid-1.0.4.tgz && tar zxf uuid-1.0.4.tgz && cd uuid-1.0.4/
phpize && ./configure && make && make install
phpbrew config # Add the line "extension=uuid.so" to the config and save
```

Now get the benchmark scripts and install dependencies with Composer:

```
git clone https://github.com/ramsey/uuid-benchmark.git
cd uuid-benchmark/
composer install
```

## Run the benchmarks

```
cd uuid-benchmark/
phpbrew use php-7.0.0-dev
php uuid-benchmark.php
```

You'll see output that looks like this:

```
            PECL | 0.0280 sec/10000 | 0.0000028 sec/one
         RHUMSAA | 0.0400 sec/10000 | 0.0000040 sec/one
     RAMSEY-PECL | 0.1630 sec/10000 | 0.0000163 sec/one
   RAMSEY-NOPECL | 0.0960 sec/10000 | 0.0000096 sec/one
RAMSEY-RANDOMLIB | 100.5020 sec/10000 | 0.0100502 sec/one
     RAMSEY-PHP7 | 0.1040 sec/10000 | 0.0000104 sec/one
```

## Now what?

We need to figure out what this means for the ramsey/uuid library. Different
projects have different needs, and some need to generate a lot of UUIDs quickly,
which means ramsey/uuid might not provide the best solution, but are there ways
we can improve performance without sacrificing the flexibility provided by the
library? What are they?
