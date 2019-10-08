<?php

namespace App\Command;


use App\Service\HistoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
                'Clean user history older than '
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Begin clean history...');
    }

}
