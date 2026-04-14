<?php

namespace App\Command;

use App\Service\Parser\Parser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



#[AsCommand(
    name: 'parser:scripts',
    description: 'Parse result from file',
)]
class ParserCommand extends Command
{

    public function __construct(private readonly Parser $parser)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('script_name', InputArgument::OPTIONAL, '', '')
//            ->addOption('yell', null, InputOption::VALUE_NONE, '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        $scriptName = $input->getArgument('script_name');

        switch ($scriptName){
            case 'convert':
                // приводим csv в нужний формат
                $this->parser->convert();
                break;
            case 'webscorer':
                // приводим csv в нужний формат
                $this->parser->convertWebScorer();
                break;
            case 'exel':
                // приводим csv в нужний формат
                $this->parser->exel();
                break;
            case 'exel-ms':
                // приводим csv в нужний формат
                $this->parser->exelWithMs();
                break;
        }
       return Command::SUCCESS;
    }

}
