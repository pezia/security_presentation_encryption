<?php

namespace Marble\Presentations\Security\Command\Mac;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HMac extends Command
{
    const ARGUMENT_MESSAGE = 'message';
    const ARGUMENT_KEY = 'key';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('mac:hmac')
            ->addArgument(self::ARGUMENT_MESSAGE, InputArgument::OPTIONAL, 'The message of which the HMAC will be generated.', 'This is my message to authenticate')
            ->addArgument(self::ARGUMENT_KEY, InputArgument::OPTIONAL, 'The key used for HMAC.', 'Common Secret Key')
            ->setDescription('Authentication with HMAC')
            ->setHelp('Generates and then verifies an HMAC on the given message with the given key.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io             = new SymfonyStyle($input, $output);
        $message        = $input->getArgument(self::ARGUMENT_MESSAGE);
        $key            = $input->getArgument(self::ARGUMENT_KEY);
        $alteredMessage = $message . 'foo';
        $anotherKey     = 'foo' . $key;

        $algos = array_intersect(['md5', 'sha1', 'sha512'], hash_algos());

        if (count($algos) !== 3) {
            $io->error('Missing hash algo. Make sure you run a decent PHP version!');
            return -1;
        }

        $io->title('Message Authentication with HMAC');

        $io->section('Base data');

        $io->writeln('Message: ' . $message);
        $io->writeln('Key: ' . $key);

        $io->newLine();

        $io->section('HMAC-MD5');

        $io->writeln('Original message: ' . hash_hmac('md5', $message, $key));
        $io->writeln('Altered message:  ' . hash_hmac('md5', $alteredMessage, $key));
        $io->writeln('Another key:      ' . hash_hmac('md5', $message, $anotherKey));

        $io->newLine();

        $io->section('HMAC-SHA1');

        $io->writeln('Original message: ' . hash_hmac('sha1', $message, $key));
        $io->writeln('Altered message:  ' . hash_hmac('sha1', $alteredMessage, $key));
        $io->writeln('Another key:      ' . hash_hmac('sha1', $message, $anotherKey));

        $io->newLine();

        $io->section('HMAC-SHA512');

        $io->writeln('Original message: ' . hash_hmac('sha512', $message, $key));
        $io->writeln('Altered message:  ' . hash_hmac('sha512', $alteredMessage, $key));
        $io->writeln('Another key:      ' . hash_hmac('sha512', $message, $anotherKey));

        return 0;
    }
}
