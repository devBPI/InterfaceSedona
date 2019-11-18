<?php

namespace App\Command;


use App\Service\HistoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleanUserHistoryCommand
 * @package App\Command
 */
class CleanUserHistoryCommand extends Command
{
    protected static $defaultName = 'user:history:clean';

    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * CleanUserHistoryCommand constructor.
     *
     * @param HistoryService $historyService
     */
    public function __construct(
        HistoryService $historyService
    ) {
        parent::__construct(self::$defaultName);

        $this->historyService = $historyService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(
                'Clean user history older than x months'
            )
            ->addOption(
                'count-month',
                'nb',
                InputOption::VALUE_OPTIONAL,
                'Number of months since deleting user histories',
                4
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Begin clean history command');

        $date = new \DateTime('-'.$input->getOption('count-month').' month');
        $output->writeln('Clean histories older than '.$date->format('d/m/Y'));
        try{
            $deleted = $this->historyService->deleteHistoriesOlderThanDate($date);
        }catch(\Exception $e){
            $output->writeln(sprintf('the deleted is resulted with an error : %s.', $e->getMessage()));
        }

        $output->writeln('the histories is deleted.');
    }

}
