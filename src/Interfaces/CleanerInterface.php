<?php

namespace OHMedia\CleanupBundle\Interfaces;

use Symfony\Component\Console\Output\OutputInterface;

interface CleanerInterface
{
    public function __invoke(OutputInterface $output): void;
}
