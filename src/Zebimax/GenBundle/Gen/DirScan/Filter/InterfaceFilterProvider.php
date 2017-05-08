<?php

namespace Zebimax\GenBundle\Gen\DirScan\Filter;

class InterfaceFilterProvider implements FilterProviderInterface
{
    /**
     * @param mixed $item
     *
     * @return bool
     */
    public static function getIsValid(\SplFileInfo $item)
    {
        return strpos($item->getFilename(), 'Interface.php') !== false;
    }
}
