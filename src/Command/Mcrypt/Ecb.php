<?php

namespace Marble\Presentations\Security\Command\Mcrypt;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Ecb extends McryptCommandAbstract
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('mcrypt:ecb')
            ->addOption(self::OPTION_CIPHER, 'c', InputOption::VALUE_OPTIONAL, 'Block cipher used for encryption', MCRYPT_RIJNDAEL_256)
            ->addOption(self::OPTION_KEY, self::OPTION_KEY, InputOption::VALUE_OPTIONAL, 'The secret key', 'my too short Key')
            ->setDescription('This example shows that with ECB mode you will get the same ciphertext block for the same plaintext block.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $cipher    = $input->getOption(self::OPTION_CIPHER);
        $key       = $input->getOption(self::OPTION_KEY);
        $mode      = MCRYPT_MODE_ECB;
        $blockSize = $this->getBlockSize($cipher, $mode);

        $io->writeln('Cipher:            ' . $cipher);
        $io->writeln('Mode:              ' . $mode);
        $io->writeln('Block size:        ' . $blockSize);

        $io->warning('Be aware of the fact, that only the block that contains the counter has a different ciphertext');

        for ($i = 0; $i < 3; $i++) {
            $message    = $this->getMessage($i);
            $cipherText = mcrypt_encrypt($cipher, $key, $message, $mode);
            $io->writeln($this->chunkText(bin2hex($cipherText), $blockSize));
        }

        return 0;
    }

    /**
     * @param int $counter
     *
     * @return string
     */
    private function getMessage($counter)
    {
        $messageData = [
            'status' => 'ok',
            'data'   => [
                'counter' => $counter,
            ],
            'debug'  => [
                'no' => 'debug :)',
            ],
        ];

        return json_encode($messageData, JSON_PRETTY_PRINT);
    }
}
