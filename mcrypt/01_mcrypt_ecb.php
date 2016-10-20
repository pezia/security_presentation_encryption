<?php

$cipher   = MCRYPT_RIJNDAEL_256; // which is known as AES-256
$mode     = MCRYPT_MODE_ECB;
$key      = 'my too short Key';
$response = require __DIR__ . '/response_skeleton.php';

$response['data'] = [
    'foo' => 'bar',
    'baz' => true,
];

$data = json_encode($response);

echo 'Block size: ', mcrypt_get_block_size($cipher, $mode), PHP_EOL;
echo 'Plaintext length: ', strlen($data), PHP_EOL;

for ($i = 0; $i < 3; $i++) {
    echo mcrypt_encrypt($cipher, $key, $data, $mode), PHP_EOL;
}

