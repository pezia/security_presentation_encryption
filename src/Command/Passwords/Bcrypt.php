<?php

namespace Marble\Presentations\Security\Command\Passwords;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Bcrypt extends Command
{
    const ARGUMENT_PASSWORD = 'password';
    const OPTION_MIN_COST = 'minCost';
    const OPTION_MAX_COST = 'maxCost';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('password:bcrypt')
            ->addArgument(self::ARGUMENT_PASSWORD, InputArgument::OPTIONAL, 'The password to hash', 'mySecretPassword')
            ->addOption(self::OPTION_MIN_COST, 'min', InputOption::VALUE_OPTIONAL, 'The minimum cost for timing tests', 10)
            ->addOption(self::OPTION_MAX_COST, 'max', InputOption::VALUE_OPTIONAL, 'The maximum cost for timing tests', 15)
            ->setDescription('Password hashing with BCrypt and password_* functions')
            ->setHelp('An example that shows how to use BCrypt with the password functions and how cost changes the hashing time');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $password = $input->getArgument(self::ARGUMENT_PASSWORD);
        $algo     = PASSWORD_BCRYPT;

        $io->title('Hashing with BCrypt');

        $io->section('Processing times');

        $tableRows = [];

        $minCost = $input->getOption(self::OPTION_MIN_COST);
        $maxCost = $input->getOption(self::OPTION_MAX_COST);

        $io->progressStart($maxCost - $minCost + 1);

        for ($cost = $minCost; $cost <= $maxCost; $cost++) {
            $start       = microtime(true);
            $hash        = password_hash($password, $algo, ['cost' => $cost]);
            $timeTaken   = (microtime(true) - $start) * 1000.0;
            $tableRows[] = [
                $cost,
                sprintf('%.2f ms', $timeTaken),
                $hash,
            ];

            $io->progressAdvance();
        }

        $io->progressFinish();

        $io->table(['Cost', 'Time taken', 'Hash'], $tableRows);

        $io->section('Verification');

        $hash = password_hash($password, $algo);
        $io->writeln('Password: ' . $password);
        $io->writeln('Hash: ' . $hash);
        $io->writeln('Verify the correct password: ' . password_verify($password, $hash));
        $io->writeln('Verify the incorrect password: ' . password_verify($password . 'foo', $hash));


        $io->section('Rehashing check');

        $originalHash = password_hash($password, $algo, ['cost' => 10]);;
        $newHash = password_hash($password, $algo, ['cost' => 11]);

        $io->writeln('Original hash: ' . $originalHash);
        $io->writeln('Bcrypt algo, same cost: ' . password_needs_rehash($originalHash, $algo, ['cost' => 10]));
        $io->writeln('Default algo, same cost: ' . password_needs_rehash($originalHash, PASSWORD_DEFAULT, ['cost' => 10]));
        $io->writeln('Bcrypt algo, changed cost: ' . password_needs_rehash($originalHash, $algo, ['cost' => 11]));
        $io->writeln('Hash after rehashing: ' . $newHash);

        $io->section('Hash info');

        $io->table(
            [
                'Hash',
                'Info',
            ],
            [
                [$originalHash, var_export(password_get_info($originalHash), true)],
                [$newHash, var_export(password_get_info($newHash), true)],
            ]
        );
    }
}
