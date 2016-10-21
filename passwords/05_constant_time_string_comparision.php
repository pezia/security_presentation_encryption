<?php

ini_set('memory_limit', '512m');

$stringA = openssl_random_pseudo_bytes(100 * 1024 * 1024); // 100 MiB
$stringB = $stringA;

if (!function_exists('hash_equals')) {
    echo 'Use PHP >= 7.0 or a polyfill.', PHP_EOL;
    die();
}

echo 'The two strings are the same', PHP_EOL;
echo '------------------------------------------', PHP_EOL;

doStat($stringA, $stringB);

echo PHP_EOL, 'The two strings are different at the first byte', PHP_EOL;
echo '------------------------------------------', PHP_EOL;

$stringB[0] = $stringB[0] + 1;
doStat($stringA, $stringB);

echo PHP_EOL, 'The two strings are not the same length', PHP_EOL;
echo '------------------------------------------', PHP_EOL;

$stringB = 'foo';
doStat($stringA, $stringB);

function doStat($stringA, $stringB)
{
    $hashEqualsRuntimes = [];
    $equalsRuntimes     = [];

    for ($i = 0; $i < 100; $i++) {
        $start                = microtime(true);
        $equals               = hash_equals($stringA, $stringB);
        $hashEqualsRuntimes[] = microtime(true) - $start;

        if ($equals) {
            echo '.';
        } else {
            echo '*';
        }
    }

    echo PHP_EOL;

    for ($i = 0; $i < 100; $i++) {
        $start            = microtime(true);
        $equals           = $stringA === $stringB;
        $equalsRuntimes[] = microtime(true) - $start;

        if ($equals) {
            echo '.';
        } else {
            echo '*';
        }
    }

    echo PHP_EOL;

    printf('hash_equals average runtime:  %10.5f ms' . PHP_EOL, (array_sum($hashEqualsRuntimes) / count($hashEqualsRuntimes)) * 1000.0);
    printf('equals average runtime: %10.5f ms' . PHP_EOL, (array_sum($equalsRuntimes) / count($equalsRuntimes)) * 1000.0);
}
