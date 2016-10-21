<?php

namespace Marble\Presentations\Security\Command\Mcrypt;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CbcRandomIv extends McryptCommandAbstract
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('mcrypt:cbc-random-iv')
            ->addOption(self::OPTION_CIPHER, 'c', InputOption::VALUE_OPTIONAL, 'Block cipher used for encryption', MCRYPT_RIJNDAEL_256)
            ->addOption(self::OPTION_KEY, self::OPTION_KEY, InputOption::VALUE_OPTIONAL, 'The secret key', 'my too short Key')
            ->setDescription('This example shows that with CBC mode you will get the same ciphertext for the same plaintext until the first difference if you use the same IV.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $cipher    = $input->getOption(self::OPTION_CIPHER);
        $key       = $input->getOption(self::OPTION_KEY);
        $mode      = MCRYPT_MODE_CBC;
        $blockSize = $this->getBlockSize($cipher, $mode);
        $message   = 'Foo bar';

        $io->writeln('Cipher:            ' . $cipher);
        $io->writeln('Mode:              ' . $mode);
        $io->writeln('Block size:        ' . $blockSize);

        for ($i = 0; $i < 3; $i++) {
            $iv = $this->generateIv($cipher, $mode);

            $cipherText = mcrypt_encrypt($cipher, $key, $message, $mode, $iv);

            $io->newLine();
            $io->writeln('IV: ' . bin2hex($iv));
            $io->writeln($this->chunkText(bin2hex($cipherText), $blockSize));
        }

        return 0;
    }
}
