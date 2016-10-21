<?php

$password = 'mySecretPassword';
$salt     = 'staticSalt';

echo 'Unsalted: ', sha1($password), ' = ', hash('sha1', $password), PHP_EOL;
echo 'With static salt: ', sha1($salt . $password), PHP_EOL;
