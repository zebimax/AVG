<?php

namespace Zebimax\GenBundle\Gen\DirScan\Processing;

use Monolog\Logger;

class NamePrinter implements ProcessingProviderInterface
{
    public static function processing(
        Logger $logger,
        \SplFileInfo $item,
        string $srcDir,
        int $intends = 0
    ) {
        $logger->info('{intend}{path}',
            [
                'intend'   => str_repeat("---", $intends),
                'path' => str_replace($srcDir, '', $item->getPath())
            ]
        );
        $logger->info('{intend}{filename}',
            [
                'intend'   => str_repeat("---", $intends + 1),
                'filename' => $item->getFilename()
            ]
        );
        $logger->info('');
        $logger->info('');
    }
}
