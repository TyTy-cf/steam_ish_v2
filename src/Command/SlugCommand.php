<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlugCommand extends Command
{
    public function configure()
    {
        parent::configure(); // TODO: Change the autogenerated stub
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output); // TODO: Change the autogenerated stub
        $output->writeln('Command starting...');
        $progressBar = new ProgressBar($output, 50);
        $progressBar->start();
            // Traitement pour générer les slugs puis les modifier en BDD
            // A chaque fin de traitement, on avance la progressbar
            $progressBar->advance();
        $progressBar->finish();
        $output->writeln('Command finished !');
    }
}