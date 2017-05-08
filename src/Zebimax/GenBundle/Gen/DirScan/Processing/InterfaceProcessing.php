<?php

namespace Zebimax\GenBundle\Gen\DirScan\Processing;

use Monolog\Logger;

class InterfaceProcessing extends NamePrinter
{
    public static function processing(
        Logger $logger,
        \SplFileInfo $item,
        string $srcDir,
        int $intends = 0
    ) {
        parent::processing($logger, $item, $srcDir, $intends);
        //static::innerProcess($logger, $item, $intends);

    }

    protected static function innerProcess(Logger $logger, \SplFileInfo $item, int $intends = 0)
    {
        $f = fopen($item->getPathname(), "r");
        while ($line = fgets($f)) {
            if (strpos($line, ' function ') !== false) {
                $logger->info($line);
            }
        };
    }
}
