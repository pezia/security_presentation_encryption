<?php

namespace Marble\Presentations\Security\Command\Mcrypt;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OfbStaticIv extends McryptCommandAbstract
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('mcrypt:ofb-static-iv')
            ->addOption(self::OPTION_CIPHER, 'c', InputOption::VALUE_OPTIONAL, 'Block cipher used for encryption', MCRYPT_RIJNDAEL_256)
            ->addOption(self::OPTION_KEY, self::OPTION_KEY, InputOption::VALUE_OPTIONAL, 'The secret key', 'my too short Key')
            ->setDescription('Improper usage of OFB mode with a static IV')
            ->setHelp('With OFB mode you will get the same ciphertext for the same plaintext if you use the same IV.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $cipher    = $input->getOption(self::OPTION_CIPHER);
        $key       = $input->getOption(self::OPTION_KEY);
        $mode      = MCRYPT_MODE_OFB;
        $blockSize = $this->getBlockSize($cipher, $mode);
        $iv        = $this->generateIv($cipher, $mode);

        $io->title('OFB Mode with Static IV');

        $io->writeln('Cipher:            ' . $cipher);
        $io->writeln('Mode:              ' . $mode);
        $io->writeln('Block size:        ' . $blockSize);

        $io->warning('Since the key stream is always the same, due to the XOR only at the counter is the ciphertext different (in the middle of the 4th block)');

        for ($i = 0; $i < 3; $i++) {
            $message = $this->getMessage($i);

            $cipherText = mcrypt_encrypt($cipher, $key, $message, $mode, $iv);

            $io->writeln($this->chunkText(bin2hex($cipherText), $blockSize));
        }

        return 0;
    }
}
