<?php

namespace Marble\Presentations\Security\Command\Passwords;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Md5 extends Command
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
            ->setName('password:md5')
            ->addArgument(self::ARGUMENT_PASSWORD, InputArgument::OPTIONAL, 'The password to hash', 'mySecretPassword')
            ->addArgument(self::ARGUMENT_SALT, InputArgument::OPTIONAL, 'The salt', 'mySalt')
            ->setDescription('Password hashing with MD5');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $password = $input->getArgument(self::ARGUMENT_PASSWORD);
        $salt     = $input->getArgument(self::ARGUMENT_SALT);

        $io->title('Hashing with MD5');

        $io->table(
            ['Type', 'Password', 'Hash'],
            [
                ['Unsalted', $password, md5($password)],
                ['Salted', $salt . $password, md5($salt . $password)],
                ['Using MD5 multiple times adds nothing to the security', $password, md5(md5(md5($password)))],
            ]
        );
    }
}
