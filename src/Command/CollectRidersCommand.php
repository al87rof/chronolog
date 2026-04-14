<?php

namespace App\Command;

use App\Service\RiderProvider\RiderCollector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'collect:riders',
    description: 'Collect Riders from all races',
)]
class CollectRidersCommand extends Command
{

    public function __construct(private readonly RiderCollector $riderCollector)
    {
        parent::__construct();
    }

//    protected function configure(): void
//    {
//        $this
//            ->addArgument('name', InputArgument::OPTIONAL, '', '')
//            ->addOption('yell', null, InputOption::VALUE_NONE, '')
//        ;
//    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
       $this->riderCollector->collectAllRiders();
       return Command::SUCCESS;
    }

}
