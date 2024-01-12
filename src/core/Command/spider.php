<?php

namespace core\Command;

use core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class spider extends Command
{

    public function __construct(Main $plugin)
    {
        parent::__construct("spider", "Permet de grimper au murs.", "/spider");
        $this->setPermission("spider.command");
        $plugin->getServer()->getCommandMap()->register("spider", $this);
    }

    private $timer = [];

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            $sender->sendMessage("Please run the command in-game.");
            return true;
        }

        if($sender->hasPermission("spider.command")){

            if(!isset($this->timer[$sender->getName()])) {
                // the timer for this player is not set, so this is the first time the command is run
                $this->timer[$sender->getName()]["end"] = time() + Main::getInstance()->getConfig()->get("time-utilisation");
                $this->timer[$sender->getName()]["cooldown"] = time() + Main::getInstance()->getConfig()->get("time-sort") + Main::getInstance()->getConfig()->get("time-utilisation");

                $sender->setCanClimbWalls(true);
                $sender->sendMessage(Main::getInstance()->getConfig()->get("message-power"));
                return true;
            }

            if(time() > $this->timer[$sender->getName()]["cooldown"]){
                // the cooldown is over, so the player can run the command again
                $this->timer[$sender->getName()]["end"] = time() + Main::getInstance()->getConfig()->get("time-utilisation");
                $this->timer[$sender->getName()]["cooldown"] = time() + Main::getInstance()->getConfig()->get("time-sort") + Main::getInstance()->getConfig()->get("time-utilisation");

                $sender->setCanClimbWalls(true);
                $sender->sendMessage(Main::getInstance()->getConfig()->get("message-power"));
            } else if(time() > $this->timer[$sender->getName()]["end"]){
                // the utility time is over but the cooldown is not, so the player can not run the command
                $sender->setCanClimbWalls(false);
                $sender->sendMessage("§l§e»§r§f Time's up!");
            } else {
                // the utility time is not over and the cooldown is not either, so the player can not run the command
                $time = $this->timer[$sender->getName()]["cooldown"] - time();
                $sender->sendMessage("§l§e»§r§f Please wait §e{$time} seconds §fbefore using this command again!");
            }
        }
        return true;
    }

}