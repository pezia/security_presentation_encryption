<?php

namespace Marble\Presentations\Security\Console;

use Marble\Presentations\Security\Command\Mac\HMac;
use Marble\Presentations\Security\Command\Mcrypt\Ecb;
use Marble\Presentations\Security\Command\Mcrypt\ZeroPadding;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    /**
     * @var bool
     */
    private $commandsRegistered = false;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (!$this->commandsRegistered) {
            $this->registerCommands();

            $this->commandsRegistered = true;
        }

        return parent::doRun($input, $output);
    }

    protected function registerCommands()
    {
        $this->add(new HMac());
        $this->add(new ZeroPadding());
        $this->add(new Ecb());
    }
}