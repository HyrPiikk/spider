<?php

use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    protected function onEnable(): void
    {
        $this->getLogger()->notice("Le plugin à bien démarrer ");
    }

    protected function onDisable(): void
    {

    }
}