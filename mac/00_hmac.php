<?php

$message = 'This is my message to authenticate';
$key     = 'ourCommonSecretKey';

echo hash_hmac('sha512', $message, $key), PHP_EOL;

$message[0] = 't';

echo hash_hmac('sha512', $message, $key), PHP_EOL;

$key[0] = 'O';

echo hash_hmac('sha512', $message, $key), PHP_EOL;
echo hash_hmac('sha1', $message, $key), PHP_EOL;
echo hash_hmac('md5', $message, $key), PHP_EOL;
