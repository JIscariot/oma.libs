<?php

declare(strict_types=1);

namespace Libs\Console;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: Implement execute() method.
    }
}