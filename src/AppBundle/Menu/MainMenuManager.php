<?php

namespace AppBundle\Menu;

class MainMenuManager
{
    /** @var MainMenuItemInterface[] */
    protected $menuItems = [];

    /**
     * @param MainMenuItemInterface[] $menuItems
     */
    public function __construct(array $menuItems = [])
    {
        $this->setMenuItems($menuItems);
    }

    /**
     * @return MainMenuItemInterface[]
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @param array $menuItems
     */
    protected function setMenuItems($menuItems)
    {
        foreach ($menuItems as $item) {
            if ($item instanceof MainMenuItemInterface) {
                $this->menuItems[] = $item;
            }
        }
    }
}
