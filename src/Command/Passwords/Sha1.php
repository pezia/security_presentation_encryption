<?php

namespace Marble\Presentations\Security\Command\Passwords;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Sha1 extends Command
{
    const ARGUMENT_PASSWORD = 'password';
    const ARGUMENT_SALT = 'salt';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('password:sha1')
            ->addArgument(self::ARGUMENT_PASSWORD, InputArgument::OPTIONAL, 'The password to hash', 'mySecretPassword')
            ->addArgument(self::ARGUMENT_SALT, InputArgument::OPTIONAL, 'The salt', 'mySalt')
            ->setDescription('Password hashing with SHA1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $password = $input->getArgument(self::ARGUMENT_PASSWORD);
        $salt     = $input->getArgument(self::ARGUMENT_SALT);

        $io->title('Hashing with SHA1');

        $io->table(
            ['Type', 'Password', 'Hash'],
            [
                ['Unsalted with sha1 function', $password, sha1($password)],
                ['Unsalted with hash function', $password, hash('sha1', $password)],
                ['Salted with sha1 function', $salt . $password, sha1($salt . $password)],
                ['Salted with hash function', $salt . $password, hash('sha1', $salt . $password)],
            ]
        );
    }
}
