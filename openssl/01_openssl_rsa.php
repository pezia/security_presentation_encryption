<?php

require_once __DIR__ . '/../mcrypt/utils.php';

$cryptCertFileName = __DIR__ . '/cert_crypt.pem';
$signCertFileName  = __DIR__ . '/cert_sign.pem';

$data = 'foo';

echo 'Plaintext length: ', strlen($data), PHP_EOL;

if (is_readable($cryptCertFileName)) {
    $cryptKeyResource = openssl_get_privatekey('file://' . $cryptCertFileName);

    if (false === $cryptKeyResource) {
        echo 'Could not open encrypt private key: ', $cryptCertFileName, PHP_EOL;
        die();
    }
} else {
    $cryptKeyResource = openssl_pkey_new([
        'digest_alg'       => 'sha512',
        'private_key_bits' => 4096,
        'private_key_type' => OPENSSL_KEYTYPE_EC,
    ]);

    if (false === $cryptKeyResource) {
        echo 'Could not generate crypt private key. Make sure ext-openssl is installed properly (includeing the openssl.cnf).', PHP_EOL;
        die();
    }

    openssl_pkey_export_to_file($cryptKeyResource, $cryptCertFileName);
}


if (is_readable($signCertFileName)) {
    $signKeyResource = openssl_get_privatekey('file://' . $signCertFileName);

    if (false === $signKeyResource) {
        echo 'Could not open signature private key: ', $signCertFileName, PHP_EOL;
        die();
    }
} else {
    $signKeyResource = openssl_pkey_new([
        'digest_alg'       => 'sha256',
        'private_key_bits' => 1024,
        'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);

    if (false === $signKeyResource) {
        echo 'Could not generate signature private key. Make sure ext-openssl is installed properly (includeing the openssl.cnf).', PHP_EOL;
        die();
    }

    openssl_pkey_export_to_file($cryptKeyResource, $cryptCertFileName);
}

$cryptPublicKeyInfo = openssl_pkey_get_details($cryptKeyResource);
$signPublicKeyInfo  = openssl_pkey_get_details($signKeyResource);

$cryptPublicKey = $cryptPublicKeyInfo['key'];
$signPublicKey  = $signPublicKeyInfo['key'];

echo 'Crypt key bits: ', $cryptPublicKeyInfo['bits'], PHP_EOL;
echo 'Crypt public key: ', PHP_EOL, $cryptPublicKey, PHP_EOL;

echo 'Sign key bits: ', $signPublicKeyInfo['bits'], PHP_EOL;
echo 'Sign public key: ', PHP_EOL, $signPublicKey, PHP_EOL;

$cryptPublicKeys = [
    $cryptPublicKey,
];

$signPublicKeys = [
    $signPublicKey,
];

openssl_seal($data, $sealedData, $envKeys, $cryptPublicKeys);
openssl_sign($sealedData, $signature, $signKeyResource);

echo 'Cipher length: ', strlen($sealedData), PHP_EOL;
echo 'Signature length: ', strlen($signature), PHP_EOL;

echo bin2hex($sealedData), PHP_EOL, '------------------', PHP_EOL, bin2hex($signature), PHP_EOL, PHP_EOL;

echo 'Verify', PHP_EOL;
var_dump(openssl_verify($sealedData, $signature, $signPublicKey));

$envKey = '';
openssl_open($sealedData, $output, $envKeys[0], $cryptKeyResource);
echo 'Decrypted data: ', $output, PHP_EOL;
