<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-30 13:02:47
 */

namespace Yeskn\Support\EventListener;

use Symfony\Component\Console\Event\ConsoleCommandEvent;

class ConsoleCommandListener
{
    private $varDir;

    public function __construct($projectDir)
    {
        $this->varDir = rtrim($projectDir, '/') . '/var';
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        switch ($event->getCommand()->getName()) {
            case 'assetic:dump':
                $this->onAsseticDump($event);
                break;
            default:
                return ;
        }
    }

    protected function onAsseticDump(ConsoleCommandEvent $event)
    {
        $output = $event->getOutput();
        $hash = substr(uniqid(), 0, 8);

        $output->writeln("<comment>generated new assets version: {$hash}</comment>");

        file_put_contents($this->varDir . '/assets_version', $hash);
    }
}
