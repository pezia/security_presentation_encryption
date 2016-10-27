<?php

namespace Marble\Presentations\Security\Command\Passwords;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Sha2 extends Command
{
    const ARGUMENT_PASSWORD = 'password';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('password:sha2')
            ->addArgument(self::ARGUMENT_PASSWORD, InputArgument::OPTIONAL, 'The password to hash', 'mySecretPassword')
            ->setDescription('Password hashing with SHA2 family hash functions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $password = $input->getArgument(self::ARGUMENT_PASSWORD);

        $io->title('Hashing with SHA1');

        $io->table(
            ['Algo', 'Hash'],
            [
                ['SHA2-224', hash('sha224', $password)],
                ['SHA2-256', hash('sha256', $password)],
                ['SHA2-384', hash('sha384', $password)],
                ['SHA2-512', hash('sha512', $password)],
            ]
        );
    }
}
