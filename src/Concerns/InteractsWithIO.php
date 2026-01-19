<?php

declare(strict_types=1);

namespace Luxid\Installer\Concerns;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait InteractsWithIO
{
    protected SymfonyStyle $io;

    protected function initIO(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function info(string $message): void
    {
        $this->io->text("<info>{$message}</info>");
    }

    protected function success(string $message): void
    {
        $this->io->success($message);
    }

    protected function error(string $message): void
    {
        $this->io->error($message);
    }

    protected function warning(string $message): void
    {
        $this->io->warning($message);
    }
}
