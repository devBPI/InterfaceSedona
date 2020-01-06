<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/10/19
 * Time: 14:18
 */

namespace App\Command;


use App\Service\HistoryService;
use App\Service\ImageBuilderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;


class CleanerPicturesFileCommand extends Command
{


protected static $defaultName = 'folder:images:clean';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImageBuilderService
     */
    private $imageBuilderService;

    /**
     * CleanerPicturesFileCommand constructor.
     * @param ImageBuilderService $imageBuilderService
     */
    public function __construct(ImageBuilderService $imageBuilderService) {
        parent::__construct(self::$defaultName);

        $this->imageBuilderService = $imageBuilderService;
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

        $stw = new Stopwatch();
        $stw->start("send");
        $output->writeln('<info>start cleaning Folders at '.date("Y-m-d H:i:s").'</info>');

        $count = $this->imageBuilderService->clean();
        $output->writeln(sprintf('%s files has deleted with success ', $count));

        $event = $stw->stop("send");
        $output->writeln("End at ".date("Y-m-d H:i:s").sprintf(' ( %.2F MiB - %d s )', $event->getMemory() / 1024 / 1024, $event->getDuration() /1000 ));
        return 0;

    }
}
