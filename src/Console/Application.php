<?php

namespace Marble\Presentations\Security\Console;

use Marble\Presentations\Security\Command\Mac\HMac;
use Marble\Presentations\Security\Command\Mcrypt\CbcRandomIv;
use Marble\Presentations\Security\Command\Mcrypt\CbcStaticIv;
use Marble\Presentations\Security\Command\Mcrypt\Ecb;
use Marble\Presentations\Security\Command\Mcrypt\OfbRandomIv;
use Marble\Presentations\Security\Command\Mcrypt\OfbReusedIv;
use Marble\Presentations\Security\Command\Mcrypt\OfbStaticIv;
use Marble\Presentations\Security\Command\Mcrypt\ZeroPadding;
use Marble\Presentations\Security\Command\Passwords\Bcrypt;
use Marble\Presentations\Security\Command\Passwords\StringComparision;
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
        $this->add(new CbcStaticIv());
        $this->add(new CbcRandomIv());
        $this->add(new OfbStaticIv());
        $this->add(new OfbRandomIv());
        $this->add(new OfbReusedIv());
        $this->add(new Bcrypt());
        $this->add(new StringComparision());
    }
}