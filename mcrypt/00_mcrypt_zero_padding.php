<?php

$cipher   = MCRYPT_RIJNDAEL_256; // which is known as AES-256
$mode     = MCRYPT_MODE_ECB;
$key      = 'my too short Key';

$data = 'foo';

echo 'Block size: ', mcrypt_get_block_size($cipher, $mode), PHP_EOL;
echo 'Plaintext length: ', strlen($data), PHP_EOL;

for ($i = 0; $i < 3; $i++) {
    $cipherText = mcrypt_encrypt($cipher, $key, $data, $mode);
    echo 'Cipher length: ', strlen($cipherText), PHP_EOL;
    echo bin2hex(mcrypt_decrypt($cipher, $key, $cipherText, $mode)), PHP_EOL;
}
