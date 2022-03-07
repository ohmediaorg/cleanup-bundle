<?php

namespace OHMedia\CleanupBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends Command
{
    private $cleaners;

    public function __construct()
    {
        $cleaners = [];

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
        foreach ($this->cleaners as $cleaner) {
            // calls the magic function __invoke
            $cleaner();
        }

        return Command::SUCCESS;
    }

    public function addCleaner(mixed $cleaner): self
    {
        $this->cleaners[] = $cleaner;

        return $this;
    }
}
