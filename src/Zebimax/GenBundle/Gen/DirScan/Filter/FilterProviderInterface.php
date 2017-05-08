<?php

namespace Zebimax\GenBundle\Gen\DirScan\Filter;

interface FilterProviderInterface
{
    /**
     * @param mixed $item
     *
     * @return bool
     */
    public static function getIsValid(\SplFileInfo $item);
}
