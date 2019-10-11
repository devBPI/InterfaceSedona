<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/10/19
 * Time: 14:18
 */

namespace App\Command;


use App\Service\CleanerFiles;
use App\Service\HistoryService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CleanerPicturesFileCommand extends Command
{


protected static $defaultName = 'folder:images:clean';

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var CleanerFiles
     */
    private $cleanerFiles;

    /**
     * CleanerPicturesFileCommand constructor.
     * @param CleanerFiles $cleanerFiles
     */
    public function __construct(CleanerFiles $cleanerFiles) {
        parent::__construct(self::$defaultName);

        $this->cleanerFiles = $cleanerFiles;
    }

    protected function configure(): void
    {
        $this
            ->setDescription("Clean  picture's folder")
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progress = new ProgressBar($output);
        $progress->advance();
        $output->writeln('<info>start cleaning Folders</info>');

        $output->writeln('start cleaning history command');

        $count = $this->cleanerFiles->clean();

        $output->writeln(sprintf('%s files has deleted with success ', $count));

    }
}
