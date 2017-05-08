<?php
declare(strict_types = 1);
namespace Zebimax\Code\Memory\Processor;

use Zebimax\Code\Run\Runner;

class MultiChecker implements Runner
{
    public static function run()
    {
        $data = [];
        $sizeMax = 10000000;
        $size = 0;
        //$spanData = range(0, 10);
        //var_dump($spanData);
        while ($size < $sizeMax) {
            $data[] = [0,1,2,3,4,5,6,7,8,9];
            usleep(10);
            $size++;
        }
        echo sprintf('sizeof data %s%s', sizeof($data), PHP_EOL);
    }
}
