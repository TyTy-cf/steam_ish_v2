<?php

namespace App\Command;

use App\Repository\GameRepository;
use App\Service\TextService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlugCommand extends Command
{

    private TextService $textService;
    private GameRepository $gameRepository;
    private EntityManagerInterface $em;

    /**
     * @param TextService $textService
     * @param GameRepository $gameRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TextService $textService,
        GameRepository $gameRepository,
        EntityManagerInterface $em
    )
    {
        parent::__construct();
        $this->textService = $textService;
        $this->gameRepository = $gameRepository;
        $this->em = $em;
    }

    public function configure()
    {
        $this->setName('app:generate-slug')
            ->setDescription('Update game slug based on their name')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starting...');
        $games = $this->gameRepository->findAll();
        $progressBar = new ProgressBar($output, count($games));
        $progressBar->start();
        foreach($games as $game) {
            $game->setSlug($this->textService->slugify($game->getName()));
            $this->em->persist($game);
            $progressBar->advance();
        }
        $progressBar->finish();
        // indiquer à doctrine qu'il doit tirer la chasse
        $this->em->flush();
        $output->writeln('');
        $output->writeln('Command finished !');
        return 0;
    }
}