<?php

namespace core\Command;

use core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class spider extends Command
{

    public function __construct(Main $plugin)
    {
        parent::__construct("spider", "Permet de grimper au murs.", "/spider");
        $this->setPermission("spider.command");
        $plugin->getServer()->getCommandMap()->register("spider", $this);
    }

    private $timer = [];

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Please run the command in-game.");
            return true;
        }

        $name = $sender->getName();

        // Vérifier si le timer est activé pour le joueur
        if(isset($this->timer[$name])) {
            $expiry = $this->timer[$name];
            if(time() < $expiry) {
                $remaining = $expiry - time();
                $sender->sendMessage("§7§l>> §r§7Veuillez attendre §c{$remaining} secondes §7avant d'utiliser à nouveau cette commande !");
                return false;
            }
        }

        // Activer l'effet
        $this->timer[$name] = time() + Main::getInstance()->getConfig()->get("time-utilisation"); // 15 secondes d'attente avant de pouvoir réutiliser la commande
        $sender->setCanClimbWalls(true);
        $sender->sendMessage(Main::getInstance()->getConfig()->get("message-power"));

        // Désactiver l'effet après 5 secondes
        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use($sender): void {
            if($sender->isOnline()) { // Check if the player is still online
                $sender->setCanClimbWalls(false);
                $sender->sendMessage(Main::getInstance()->getConfig()->get("message-nopower"));
            }
        }), 20 * 5); // 5 seconds

        return true;
    }
}