<?php

$password = 'mySecretPassword';

echo 'SHA2-224: ', hash('sha224', $password), PHP_EOL;
echo 'SHA2-256: ', hash('sha256', $password), PHP_EOL;
echo 'SHA2-384: ', hash('sha384', $password), PHP_EOL;
echo 'SHA2-512: ', hash('sha512', $password), PHP_EOL;
