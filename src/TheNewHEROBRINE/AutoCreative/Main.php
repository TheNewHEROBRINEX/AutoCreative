<?php

namespace TheNewHEROBRINE\AutoCreative;

use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
    private $config;
    public $creativeWorld;
    
    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, ["Creative world" => "Creative"]);
        $this->creativeWorld = $this->getConfig()->get("Creative world");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->getCommand("survival")->setExecutor(new Commands($this->creativeWorld));
        $this->getServer()->getCommandMap()->getCommand("creative")->setExecutor(new Commands($this->creativeWorld));
    }

    public function onLevelChange(EntityLevelChangeEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player && !$player->hasPermission("autocreative.exempt")) {
            if ($event->getTarget()->getName() == $this->creativeWorld)
                $player->setGamemode(1);
            else
                $player->setGamemode(0);
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if ($player->getLevel()->getName() != $this->creativeWorld && !$player->hasPermission("autocreative.exempt")){
            $player->setGamemode(0);
        }
    }
}
