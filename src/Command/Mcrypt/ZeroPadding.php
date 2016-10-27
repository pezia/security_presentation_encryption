<?php

namespace Marble\Presentations\Security\Command\Mcrypt;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ZeroPadding extends McryptCommandAbstract
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('mcrypt:zero-padding')
            ->addOption(self::OPTION_CIPHER, 'c', InputOption::VALUE_OPTIONAL, 'Block cipher used for encryption', MCRYPT_RIJNDAEL_256)
            ->addOption(self::OPTION_MODE, 'm', InputOption::VALUE_OPTIONAL, 'Block cipher mode used for encryption', MCRYPT_MODE_ECB)
            ->addOption(self::OPTION_KEY, self::OPTION_KEY, InputOption::VALUE_OPTIONAL, 'The secret key', 'my too short Key')
            ->addArgument(self::ARGUMENT_MESSAGE, InputArgument::OPTIONAL, 'The message to encrypt', 'Foo')
            ->setDescription('An example that shows the padding scheme of MCrypt.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $cipher    = $input->getOption(self::OPTION_CIPHER);
        $mode      = $input->getOption(self::OPTION_MODE);
        $key       = $input->getOption(self::OPTION_KEY);
        $message   = $input->getArgument(self::ARGUMENT_MESSAGE);
        $blockSize = $this->getBlockSize($cipher, $mode);
        $iv        = $this->generateIv($cipher, $mode);

        $io->title('MCrypt Zero Padding');

        $io->writeln('Cipher:            ' . $cipher);
        $io->writeln('Mode:              ' . $mode);
        $io->writeln('Block size:        ' . $blockSize);
        $io->writeln('Plaintext length:  ' . strlen($message));

        $cipherText = mcrypt_encrypt($cipher, $key, $message, $mode, $iv);

        $io->writeln('Cipher length:     ' . strlen($cipherText));
        $io->writeln('Cipher text:       ' . bin2hex($cipherText));

        $decoded = mcrypt_decrypt($cipher, $key, $cipherText, $mode);

        $io->writeln('Decoded:           ' . bin2hex($decoded));
        $io->writeln('Trimmed:           ' . bin2hex(trim($decoded)));
        $io->writeln('Decoded Plaintext: ' . trim($decoded));


        return 0;
    }
}
