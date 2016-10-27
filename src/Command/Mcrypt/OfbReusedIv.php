<?php

namespace Marble\Presentations\Security\Command\Mcrypt;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OfbReusedIv extends McryptCommandAbstract
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('mcrypt:ofb-reused-iv')
            ->addOption(self::OPTION_CIPHER, 'c', InputOption::VALUE_OPTIONAL, 'Block cipher used for encryption', MCRYPT_RIJNDAEL_256)
            ->addOption(self::OPTION_KEY, self::OPTION_KEY, InputOption::VALUE_OPTIONAL, 'The secret key', 'my too short Key')
            ->setDescription('Improper usage of OFB with repeating IVs')
            ->setHelp('With OFB mode you can get the difference of the plaintexts if you reuse the same IV with the same key.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $cipher    = $input->getOption(self::OPTION_CIPHER);
        $key       = $input->getOption(self::OPTION_KEY);
        $mode      = MCRYPT_MODE_OFB;
        $blockSize = $this->getBlockSize($cipher, $mode);
        $message1  = 'This is the first message';
        $message2  = 'And this is the second';

        $io->title('OFB Mode with Reused IV');

        $io->writeln('Cipher:            ' . $cipher);
        $io->writeln('Mode:              ' . $mode);
        $io->writeln('Block size:        ' . $blockSize);

        $iv    = $this->generateIv($cipher, $mode);
        $newIv = $this->generateIv($cipher, $mode);

        $cipherText1          = mcrypt_encrypt($cipher, $key, $message1, $mode, $iv);
        $cipherText2          = mcrypt_encrypt($cipher, $key, $message2, $mode, $iv);
        $cipherText2WithNewIv = mcrypt_encrypt($cipher, $key, $message2, $mode, $newIv);

        $io->newLine();
        $io->writeln('IV: ' . bin2hex($iv));

        $io->newLine();

        $io->writeln('Message 1:    ' . $message1);
        $io->writeln('Message 2:    ' . $message2);
        $io->writeln('m1 XOR m2:    ' . bin2hex($message1 ^ $message2));

        $io->newLine();

        $io->writeln('Ciphertext 1: ' . bin2hex($cipherText1));
        $io->writeln('Ciphertext 2: ' . bin2hex($cipherText2));
        $io->writeln('c1 XOR c2:    ' . bin2hex($cipherText1 ^ $cipherText2));

        $io->newLine();

        $io->writeln('c2\' (new IV): ' . bin2hex($cipherText2WithNewIv));
        $io->writeln('c1 XOR c2\':   ' . bin2hex($cipherText1 ^ $cipherText2WithNewIv));

        return 0;
    }
}
