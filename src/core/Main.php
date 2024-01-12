<?php

namespace core;



use core\Command\spider;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    public array $commands = [];

    private static $instance;
    public function onEnable(): void
    {
        self::$instance = $this;
        $this->getLogger()->notice("Le plugin à bien démarrer !");
        $this->getLogger()->notice("By HyrPikk ");

        $this->getServer()->getCommandMap()->register("spider", $this->commands[] = new spider($this));
    }

    public function onDisable(): void
    {
        self::$instance = $this;
        $this->getLogger()->notice("Le plugin est éteint !");
        $this->getLogger()->notice("By HyrPikk ");
    }

    public static function getInstance(): Main{
        return self::$instance;
    }
}