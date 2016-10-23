<?php

namespace Marble\Presentations\Security\Command\OpelSsl;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Padding extends Command
{
    const OPTION_KEY = 'key';
    const OPTION_CIPHER = 'cipher';
    const ARGUMENT_MESSAGE = 'message';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('openssl:padding')
            ->addOption(self::OPTION_CIPHER, 'c', InputOption::VALUE_OPTIONAL, 'Block cipher used for encryption', 'aes-256-cbc')
            ->addOption(self::OPTION_KEY, self::OPTION_KEY, InputOption::VALUE_OPTIONAL, 'The secret key', 'my too short Key')
            ->addArgument(self::ARGUMENT_MESSAGE, InputArgument::OPTIONAL, 'The message to encrypt', 'Foo')
            ->setDescription('An example that shows the padding scheme of the OpenSSL extension.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $cipher   = $input->getOption(self::OPTION_CIPHER);
        $key      = $input->getOption(self::OPTION_KEY);
        $message  = $input->getArgument(self::ARGUMENT_MESSAGE);
        $isSecure = false;
        $iv       = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher), $isSecure);

        $io->writeln('IV is secure: ' . var_export($isSecure, true));
        $io->writeln('Plaintext length: ' . strlen($message));

        $cipherTextDefaultPadding = openssl_encrypt($message, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        $io->table(
            ['Padding scheme', 'Ciphertext', 'Decrypted'],
            [
                ['PKCS#7 padding', bin2hex($cipherTextDefaultPadding), bin2hex(openssl_decrypt($cipherTextDefaultPadding, $cipher, $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv))],
            ]
        );
    }
}
