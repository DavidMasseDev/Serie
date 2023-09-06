<?php

namespace App\Command;

use App\Utils\UpdateSeries;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-serie',
    description: 'Update a serie by running this command',
)]
class UpdateSerieCommand extends Command
{
    public function __construct(private UpdateSeries $updateSeries, string $name = null)
    {
        parent::__construct($name); // Constructeur de base des commandes. Il faut laisser le $name.
    }

    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }

//        $io->text('Salut ca va ?');
//        $io->ask('Nom de la série ?');
//        $io->confirm("Etes-vous sûr de vouloir la supprimer ?", false);
//        $response = $io->choice('Quelle saison ?', ['#1','#2','#3']);
//        $io->error('Oula ! Probleme !');
//        $io->writeln('C bon tkt !');
        try{
            $number = $this->updateSeries->removeOldSerie();
            $io->success('Success. '.$number.' series deleted');
        }catch(\Exception $exception){
            $io->error("Task failed : ".$exception->getMessage());
        }


        return Command::SUCCESS;
    }
}
