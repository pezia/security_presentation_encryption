<?php

$password = 'mySecretPassword';
$salt     = 'staticSalt';

echo 'Unsalted: ', md5($password), ' = ', hash('md5', $password), PHP_EOL;
echo 'With static salt: ', md5($salt . $password), PHP_EOL;
echo 'Using MD5 multiple times adds nothing to the security: ', md5(md5(md5($password))), PHP_EOL;
