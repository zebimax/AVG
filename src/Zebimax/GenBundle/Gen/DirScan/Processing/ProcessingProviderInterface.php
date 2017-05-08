<?php

namespace Zebimax\GenBundle\Gen\DirScan\Processing;

use Monolog\Logger;

interface ProcessingProviderInterface
{
    /**
     * @param Logger       $logger
     * @param \SplFileInfo $item
     *
     * @param string       $srcDir
     * @param int          $intends
     *
     * @return bool
     */
    public static function processing(
        Logger $logger,
        \SplFileInfo $item,
        string $srcDir,
        int $intends = 0
    );
}
