<?php

namespace OHMedia\CleanupBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('ohmedia:cleanup')
            ->setDescription('Command to run daily for consistent data cleanup')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: loop over tagged services

        return Command::SUCCESS;
    }
}
