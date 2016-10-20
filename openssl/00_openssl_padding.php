<?php

require_once __DIR__ . '/../mcrypt/utils.php';

$cipher = 'aes-256-cbc';
$key    = 'my too short Key';

$data      = 'foo';
$blockSize = openssl_cipher_iv_length($cipher);
$isSecure  = false;
$iv        = openssl_random_pseudo_bytes($blockSize, $isSecure);

echo 'Block size: ', $blockSize, PHP_EOL;
echo 'Plaintext length: ', strlen($data), PHP_EOL;

for ($i = 0; $i < 3; $i++) {
    $cipherText = openssl_encrypt($data, $cipher, $key, 0, $iv);
    echo 'Cipher length: ', strlen($cipherText), PHP_EOL;

    echo echoChunked($cipherText, $blockSize), PHP_EOL;
    echo bin2hex(openssl_decrypt($cipherText, $cipher, $key, 0, $iv)), PHP_EOL;
}
