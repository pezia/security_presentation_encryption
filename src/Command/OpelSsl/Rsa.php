<?php

namespace Marble\Presentations\Security\Command\OpelSsl;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Rsa extends Command
{
    const ARGUMENT_MESSAGE = 'message';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('openssl:rsa')
            ->addArgument(self::ARGUMENT_MESSAGE, InputArgument::OPTIONAL, 'The message to encrypt', 'My secret message')
            ->setDescription('An example that shows the padding scheme of the OpenSSL extension.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $message = $input->getArgument(self::ARGUMENT_MESSAGE);

        $cryptCertFileName = __DIR__ . '/crypt.key';
        $signCertFileName  = __DIR__ . '/sign.key';

        $io->text('Plaintext length: ' . strlen($message));

        $io->section('Key generation / opening');

        if (is_readable($cryptCertFileName)) {
            $io->text('Opening existing private key for encryption: ' . realpath($cryptCertFileName));
            $cryptKeyResource = openssl_get_privatekey('file://' . $cryptCertFileName);

            if (false === $cryptKeyResource) {
                $io->error('Could not open encrypt private key.');
                return 1;
            }
        } else {
            $io->text('Generating private key for encryption...');

            $cryptKeyResource = openssl_pkey_new([
                'digest_alg'       => 'sha512',
                'private_key_bits' => 4096,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);

            if (false === $cryptKeyResource) {
                $io->error('Could not generate crypt private key. Make sure ext-openssl is installed properly (including the openssl.cnf).');
                return 1;
            }

            openssl_pkey_export_to_file($cryptKeyResource, $cryptCertFileName);
            $io->text('New crypt private key was saved to ' . realpath($cryptCertFileName));
        }


        if (is_readable($signCertFileName)) {
            $io->text('Opening existing private key for digital signature: ' . realpath($signCertFileName));
            $signKeyResource = openssl_get_privatekey('file://' . $signCertFileName);

            if (false === $signKeyResource) {
                $io->error('Could not open signature private key: ' . $signCertFileName);
                return 1;
            }
        } else {
            $io->text('Generating private key for digital signature...');

            $signKeyResource = openssl_pkey_new([
                'digest_alg'       => 'sha256',
                'private_key_bits' => 1024,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);

            if (false === $signKeyResource) {
                $io->error('Could not generate signature private key. Make sure ext-openssl is installed properly (includeing the openssl.cnf).');
                return 1;
            }

            openssl_pkey_export_to_file($signKeyResource, $signCertFileName);
            $io->text('New sign private key was saved to ' . realpath($signCertFileName));
        }

        $io->section('Encryption and digital signature');

        $cryptPublicKeyInfo = openssl_pkey_get_details($cryptKeyResource);
        $signPublicKeyInfo  = openssl_pkey_get_details($signKeyResource);

        $io->table(
            ['Key', 'Length'],
            [
                ['Encryption key', $cryptPublicKeyInfo['bits']],
                ['Signature key', $signPublicKeyInfo['bits']],
            ]
        );

        $cryptPublicKey = $cryptPublicKeyInfo['key'];
        $signPublicKey  = $signPublicKeyInfo['key'];

        $cryptPublicKeys = [
            $cryptPublicKey,
        ];

        openssl_seal($message, $sealedData, $envKeys, $cryptPublicKeys);
        openssl_sign($sealedData, $signature, $signKeyResource);

        $io->text('Cipher length: ' . strlen($sealedData));
        $io->text('Ciphertext: ' . bin2hex($sealedData));

        $io->newLine();

        $io->text('Signature length: ' . strlen($signature));
        $io->text('Signature: ' . bin2hex($signature));

        $io->section('Verification and decryption');
        $io->text('Signature valid: ' . openssl_verify($sealedData, $signature, $signPublicKey));

        $output = '';
        openssl_open($sealedData, $output, $envKeys[0], $cryptKeyResource);
        $io->text('Decrypted data: ' . $output);
    }
}
