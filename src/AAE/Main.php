<?php

namespace AAE;

use pocketmine\item\Item;

use pocketmine\Server;

use pocketmine\event\Listener;

use pocketmine\plugin\PluginBase;

use pocketmine\event\player\PlayerItemHeldEvent;

use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		@mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML,array("max-level" => 5));
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getLogger()->info(TF::GREEN."[AAE]".TF::GOLD."Enabled!");
		$this->getLogger()->info(TF::GREEN."[AAE]".TF::GOLD."The enchantment max level is changeable in the config.yml!(" . $this->getServer()->getDataPath() . "/plugins/AntiAbusiveEnchants/config.yml)");
		$this->getLogger()->info(TF::GREEN."[AAE]".TF::GOLD."The current max enchant level is ".$this->config->get("max-level"));
	}
	
	

	public function onDisable(){
		$this->getLogger()->info(TF::GREEN."[AAE]".TF::RED."Disabled!");
	}

	public function getMax(){
		return $this->config->get("max-level");
	}

	public function onItemHeld(PlayerItemHeldEvent $ev){
		$p = $ev->getPlayer();
		$max = $this->getMax();
		$contents = $p->getInventory()->getContents();
		$i = $p->getInventory()->getItemInHand();
		if($i instanceof Item){
			if($i->hasEnchantments()){
				foreach($i->getEnchantments() as $e){
					if($e->getLevel() >= $max){
						$levelofenchant = $e->getLevel();
						$p->getInventory()->removeItem($i);
						$p->sendMessage(TF::GREEN."[AAE]".TF::BLUE.$i->getName()." has been removed from your inventory for being above or equal to the max enchantment level!");
					}
				}
			}
		}
	}
}
