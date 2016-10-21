<?php

namespace Marble\Presentations\Security\Command\Mcrypt;

use Symfony\Component\Console\Command\Command;

class McryptCommandAbstract extends Command
{
    const OPTION_KEY = 'key';
    const OPTION_MODE = 'mode';
    const OPTION_CIPHER = 'cipher';
    const ARGUMENT_MESSAGE = 'message';

    /**
     * @param string $cipher
     * @param string $mode
     *
     * @return int
     *
     * @see http://php.net/manual/en/function.mcrypt-get-block-size.php
     */
    protected function getBlockSize($cipher, $mode)
    {
        $reflection = new \ReflectionFunction('mcrypt_get_block_size');

        if ($reflection->getNumberOfParameters() == 2) {
            $blockSize = mcrypt_get_block_size($cipher, $mode);
        } else {
            $blockSize = mcrypt_get_block_size($cipher);
        }

        return $blockSize;
    }

    /**
     * @param string $text
     * @param int    $size
     *
     * @return string
     */
    protected function chunkText($text, $size)
    {
        $chunks = str_split($text, $size);

        return implode(' - ', $chunks);
    }


    /**
     * @param string $cipher
     * @param string $mode
     *
     * @return string
     */
    protected function generateIv($cipher, $mode)
    {
        return mcrypt_create_iv(mcrypt_get_iv_size($cipher, $mode));
    }

    /**
     * @param int $counter
     *
     * @return string
     */
    protected function getMessage($counter)
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
