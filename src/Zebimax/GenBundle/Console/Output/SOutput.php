<?php

namespace Zebimax\GenBundle\Console\Output;

use Symfony\Component\Console\Output\ConsoleOutput;

class SOutput extends ConsoleOutput
{
    public function writelnf(string $message, array $args)
    {
        $this->writeln(vsprintf($message, $args));
    }
}
