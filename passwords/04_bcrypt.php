<?php

// if you have an old PHP version (between 5.3.7 and 5.5), you can use ircmaxell/password-compat

$password = 'mySecretPassword';

echo 'Processing times', PHP_EOL;
echo '--------------------------------------------------------------------------------------', PHP_EOL;

for ($cost = 10; $cost <= 15; $cost++) {
    $start     = microtime(true);
    $hash      = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    $timeTaken = (microtime(true) - $start) * 1000.0;
    printf('%s %10.2fms with cost %d' . PHP_EOL, $hash, $timeTaken, $cost);
}

echo PHP_EOL, PHP_EOL, PHP_EOL;

echo 'Verification', PHP_EOL;
echo '--------------------------------------------------------------------------------------', PHP_EOL;

$hash = password_hash($password, PASSWORD_BCRYPT);
var_dump(password_verify($password, $hash));
var_dump(password_verify($password . 'foo', $hash));

echo PHP_EOL, PHP_EOL, PHP_EOL;

echo 'Rehashing', PHP_EOL;
echo '--------------------------------------------------------------------------------------', PHP_EOL;

$originalHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

var_dump(password_needs_rehash($originalHash, PASSWORD_BCRYPT, ['cost' => 10]));
var_dump(password_needs_rehash($originalHash, PASSWORD_DEFAULT, ['cost' => 10]));
var_dump(password_needs_rehash($originalHash, PASSWORD_BCRYPT, ['cost' => 11]));

$newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
echo $originalHash, ' -> ', $newHash;

echo 'Info', PHP_EOL;
echo '--------------------------------------------------------------------------------------', PHP_EOL;

var_dump(password_get_info($originalHash));
var_dump(password_get_info($newHash));
