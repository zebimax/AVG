<?php
declare(strict_types = 1);
namespace Zebimax\CodeTests\Memory\Processor;

use Zebimax\Code\Memory\Processor\MultiChecker;
use Zebimax\Code\Tester;

class MultiCheckerTester extends Tester
{
    public function __construct()
    {
        parent::__construct(new MultiChecker());
    }
}
