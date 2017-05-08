<?php

namespace Zebimax\Code;

use Zebimax\Code\Run\Runner;

class Tester
{
    protected $runner;

    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
    }

    public final function start()
    {
        /** @Todo should be init check dynamically on generation */
        $this->init();
        $this->runner->run();
    }

    protected function init()
    {
    }
}
