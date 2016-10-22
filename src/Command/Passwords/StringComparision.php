<?php

namespace Marble\Presentations\Security\Command\Passwords;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StringComparision extends Command
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('password:string-comparision')
            ->setDescription('An example that shows how constant-time string comparision prevents timing attacks');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        ini_set('memory_limit', '512m');

        $stringA = openssl_random_pseudo_bytes(100 * 1024 * 1024); // 100 MiB
        $stringB = $stringA;

        $io->section('The two strings are the same');

        $sameStringsStat = $this->doStat($stringA, $stringB, $io);

        $io->section('The two strings are different at the first byte');

        $stringB[0]                  = $stringB[0] + 1;
        $differentFirstCharacterStat = $this->doStat($stringA, $stringB, $io);

        $io->section('The two strings are not the same length');

        $stringB              = 'foo';
        $differentLengthsStat = $this->doStat($stringA, $stringB, $io);

        $io->table(
            [
                'Test',
                'hash_equals',
                'equals',
            ],
            [
                ['Same string', $this->formatAverageRuntime($sameStringsStat['hash_equals']), $this->formatAverageRuntime($sameStringsStat['equals'])],
                ['Different first character', $this->formatAverageRuntime($differentFirstCharacterStat['hash_equals']), $this->formatAverageRuntime($differentFirstCharacterStat['equals'])],
                ['Different lengths', $this->formatAverageRuntime($differentLengthsStat['hash_equals']), $this->formatAverageRuntime($differentLengthsStat['equals'])],
            ]
        );
    }


    /**
     * @param string         $stringA
     * @param string         $stringB
     * @param StyleInterface $output
     *
     * @return array
     */
    private function doStat($stringA, $stringB, StyleInterface $output)
    {
        $hashEqualsRuntimes = [];
        $equalsRuntimes     = [];

        $output->progressStart(100);

        for ($i = 0; $i < 100; $i++) {
            $start                = microtime(true);
            $equals               = hash_equals($stringA, $stringB);
            $hashEqualsRuntimes[] = microtime(true) - $start;

            // to make sure it has a side-effect
            if ($equals) {
                echo '.';
            } else {
                echo '*';
            }

            $output->progressAdvance();
        }

        $output->progressFinish();

        $output->progressStart(100);

        for ($i = 0; $i < 100; $i++) {
            $start            = microtime(true);
            $equals           = $stringA === $stringB;
            $equalsRuntimes[] = microtime(true) - $start;

            // to make sure it has a side-effect
            if ($equals) {
                echo '.';
            } else {
                echo '*';
            }

            $output->progressAdvance();
        }

        $output->progressFinish();

        return [
            'hash_equals' => $hashEqualsRuntimes,
            'equals'      => $equalsRuntimes,
        ];
    }

    /**
     * @param float[] $runtimes Runtime statistics in seconds.
     *
     * @return string
     */
    private function formatAverageRuntime(array $runtimes)
    {
        return sprintf('%.6f ms', (array_sum($runtimes) / count($runtimes)) * 1000.0);
    }
}
