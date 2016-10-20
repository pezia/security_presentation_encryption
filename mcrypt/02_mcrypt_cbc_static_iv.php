<?php

require_once __DIR__ . '/utils.php';

$cipher = MCRYPT_RIJNDAEL_256; // which is known as AES-256
$mode   = MCRYPT_MODE_CBC;
$key    = 'my too short Key';
$iv     = 'it is my 32 bytes long static IV';

$response = require __DIR__ . '/response_skeleton.php';

$response['data'] = [
    'foo' => 'bar',
    'baz' => true,
];


$blockSize = mcrypt_get_block_size($cipher, $mode);

echo 'Block size: ', $blockSize, PHP_EOL;

for ($i = 0; $i < 3; $i++) {
    $response['data']['counter'] = $i;

    $data = json_encode($response);

    echo 'Plaintext length: ', strlen($data), PHP_EOL;

    $cipherText = bin2hex(mcrypt_encrypt($cipher, $key, $data, $mode, $iv));

    echo echoChunked($cipherText, $blockSize), PHP_EOL;
}
