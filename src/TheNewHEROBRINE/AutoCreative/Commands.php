<?php

namespace TheNewHEROBRINE\AutoCreative;


use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Commands implements CommandExecutor{

    private $creativeWorld;

    public function __construct($creativeWorld) {
        $this->creativeWorld = $creativeWorld;
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Puoi \n usare questo comando solo in-game");
            return true;
        }

        if ($sender->getLevel()->getName() == $this->creativeWorld ||
            $sender->hasPermission("autocreative.exempt")) {
            switch ($command->getName()) {
                case 'survival':
                    $sender->setGamemode(0);
                    $sender->sendMessage(new TranslationContainer("commands.gamemode.success.self", [Server::getGamemodeString(0)]));
                    break;
                case 'creative':
                    $sender->setGamemode(1);
                    $sender->sendMessage(new TranslationContainer("commands.gamemode.success.self", [Server::getGamemodeString(1)]));
                    break;
            }
        } else{
            $sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));
        }
    }
}