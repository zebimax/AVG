<?php

namespace Zebimax\GenBundle\Command;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Zebimax\GenBundle\Console\Output\SOutput;
use Zebimax\GenBundle\Gen\DirScan\Filter\InterfaceFilterProvider;
use Zebimax\GenBundle\Gen\DirScan\Processing\InterfaceProcessing;
use Zebimax\GenBundle\Gen\DirScan\Processing\NamePrinter;

class DirScan extends ContainerAwareCommand
implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const BATCH_SIZE = 1000;

    /** @var Logger */
    protected $logger;

    /** @var NamePrinter */
    protected $namePrinter;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('gen:dir-scan')
            ->setDescription('Scans Dirs')
            ->addArgument('dir', InputArgument::REQUIRED, 'Directory to scan')
            ->addOption(
                'srcdir',
                'sd',
                InputOption::VALUE_OPTIONAL,
                'Src Dir',
                '/home/ajax/www/dev/application/crm-enterprise/vendor/symfony/symfony/src/Symfony/Component/Console'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Forces operation to be executed.'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->namePrinter = new NamePrinter();
        if (!$output instanceof SOutput) return;

        $this->setUpLogger($output);
        $dir                   = $input->getArgument('dir');
        $srcDir = $input->getOption('srcdir');
        $recDirIterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);

        $this->iter($recDirIterator, $srcDir);

        $output->writelnf('Scanned %s dir', [$dir]);
    }

    protected function iter(RecursiveDirectoryIterator $it, string $srcDir)
    {
        foreach ($it as $item) {
            $intends = substr_count(str_replace($srcDir, '', $item->getPathname()), '/');
            if ($item->isDir()) {
                //$this->logFile($intends, $item, [$this->namePrinter, 'processing']);
                $this->iter(
                    new RecursiveDirectoryIterator($item->getPathname(), RecursiveDirectoryIterator::SKIP_DOTS),
                    $srcDir
                );
            } else {
                $this->logFile(
                    $intends,
                    $item,
                    $srcDir,
                    [InterfaceProcessing::class, 'processing'],
                    [InterfaceFilterProvider::class, 'getIsValid']
                );
            }
        }
    }

    /**
     * @param SOutput $output
     */
    protected function setUpLogger(SOutput $output)
    {
        $this->logger->pushProcessor(new PsrLogMessageProcessor());

        $formatter = new LineFormatter("%message%\n", "");

        $consoleHandler = new ConsoleHandler($output);
        $consoleHandler->setFormatter($formatter);
        $this->logger->pushHandler($consoleHandler);

        $streamHandler = new StreamHandler('/home/ajax/www/code/AVG/out/console');
        $streamHandler->setFormatter($formatter);
        $this->logger->pushHandler($streamHandler);
    }

    /**
     * @param int          $intends
     * @param \SplFileInfo $item
     * @param string       $srcDir
     * @param callable     $processCallback
     * @param callable     $checkCallback
     */
    protected function logFile(
        int $intends,
        \SplFileInfo $item,
        string $srcDir,
        callable $processCallback,
        callable $checkCallback = null
    )
    {
        if (null === $checkCallback || $checkCallback($item)) {
            $processCallback($this->logger, $item, $srcDir, $intends);
        }
    }
}
