<?php
declare(strict_types=1);

namespace TheNewHEROBRINE\AutoCreative;

use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    /** @var Config $config */
    private $config;

    public function onEnable(){
        if (!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, ["Creative world" => "Creative"]);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        #$this->getServer()->getCommandMap()->getCommand("survival")->setExecutor(new Commands($this->creativeWorld));
        #$this->getServer()->getCommandMap()->getCommand("creative")->setExecutor(new Commands($this->creativeWorld));
    }

    /**
     * @param EntityLevelChangeEvent $event
     */
    public function onLevelChange(EntityLevelChangeEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player){
            $this->setGamemode($player, false, $event->getTarget()->getFolderName());
        }
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event) {
        $this->setGamemode($event->getPlayer());
    }

    /**
     * @param Player $player
     * @param bool $force
     * @param null|string $level
     */
    public function setGamemode(Player $player, bool $force = false, ?string $level = null): void {
        if ($force or !$player->hasPermission("autocreative.exempt")){
            $player->setGamemode($level ?? $player->getLevel() === $this->getCreativeWorld() ? Player::CREATIVE : Player::SURVIVAL);
        }
    }

    /**
     * @return string
     */
    public function getCreativeWorld(): string {
        return (string)$this->getConfig()->get("Creative world", "Creative");
    }
}
