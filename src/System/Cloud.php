<?php

namespace System;

//Base
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
//Utils
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Config;
//EventListener
use pocketmine\event\Listener;
//PlayerEvents
use pocketmine\Player;
use pocketmine\event\player\PlayerHungerChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
//ItemUndBlock
use pocketmine\block\Block;
use pocketmine\item\Item;
//BlockEvents
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
//EntityEvents
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Effect;
//Level
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
//Sounds
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\GhastShootSound;
//Commands
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
//Tile
use pocketmine\tile\Sign;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
//Nbt
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
//Inventar
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\Inventory;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

class Cloud extends PluginBase implements Listener {
	
	public $prefix = Color::WHITE . "[" . Color::BLUE . "Cloud" . Color::WHITE . "]";
	
	public function onEnable() {
		
		$this->getLogger()->info($this->prefix . Color::GREEN . "Plugin erfolgreich gestartet");
		
		if (is_dir($this->getDataFolder()) !== true) {
        	
            mkdir($this->getDataFolder());
            
        }
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		if (!is_file("/home/Cloud/Daten.yml")) {
			
			$this->getLogger()->info($this->prefix . Color::GREEN . "erstellen der 1 DatenBank...!");
			
			if (is_dir("/home/Cloud") !== true) {
			
                mkdir("/home/Cloud");
            
            }
            
            if (is_dir("/home/Cloud/players") !== true) {
			
                mkdir("/home/Cloud/players");
            
            }
            
            if (is_dir("/home/Cloud/Coins") !== true) {
			
                mkdir("/home/Cloud/Coins");
            
            }
            
            if (is_dir("/home/Cloud/Groups") !== true) {
			
                mkdir("/home/Cloud/Groups");
            
            }
            
            if (is_dir("/home/Cloud/Servers") !== true) {
			
                mkdir("/home/Cloud/Servers");
            
            }
			
			$clouddata = new Config("/home/Cloud/Daten.yml", Config::YAML);
			$server = new Config("/home/Cloud/Servers/Main.yml", Config::YAML);
			
			$clouddata->set("Servers", 1);
			$clouddata->set("Main", true);
			$clouddata->set("MainC", 0);
			$clouddata->save();
			
			$this->saveDefaultConfig();
            $this->reloadConfig();

            $config = $this->getConfig();
        
            $config->set("Server", "Main");
            $config->save();
			
			$this->getLogger()->info($this->prefix . Color::GREEN . "die Cloud wurde erfolgreich erstellt!");
			
        } else {
        	
        	$this->getLogger()->info($this->prefix . Color::GREEN . "Verbindung zur Cloud erfolgreich hergestellt!");
        	
        }
        
        $this->getLogger()->info($this->prefix . Color::GREEN . "die Cloud wurde erfolgreich gestartet!");
        $this->getLogger()->info($this->prefix . Color::AQUA . "Programmiert von" . Color::RED . " EasyToRush");
		
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
    	
    	switch ($command->getName()) {
    	
    	   case "Server":
           if (isset($args[0])) {
           	
           	if (strtolower($args[0]) === "on") {
           	
           	    if ($sender->isOp()) {
           	
               	    if (isset($args[1])) {
           	
              	        $clouddata = new Config("/home/Cloud/Daten.yml", Config::YAML);
                          $clouddata->set($args[1], true);
                          $clouddata->save();
                          $sender->sendMessage($this->prefix . "Der Server: " . Color::GOLD . $args[1] . Color::RED . " wurde hochgefahren!");
                          
           	        }
           
                   }
           
               } else if (strtolower($args[0]) === "off") {
           	
           	    if ($sender->isOp()) {
           	
           	        if (isset($args[1])) {
           	
              	        $clouddata = new Config("/home/Cloud/Daten.yml", Config::YAML);
                          $clouddata->set($args[1], false);
                          $clouddata->save();
                          $sender->sendMessage($this->prefix . "Der Server: " . Color::GOLD . $args[1] . Color::RED . " wurde heruntergefahren!");
                          
           	        }
           	        
                   }
           
               } else if (strtolower($args[0]) === "add") {
               	
               	if ($sender->isOp()) {
               	
               	    if (isset($args[1])) {
               	
               	        $clouddata = new Config("/home/Cloud/Daten.yml", Config::YAML);
                           $cloudserver = new Config("/home/Cloud/Servers/" . $args[1] . ".yml", Config::YAML);
                           $clouddata->set($args[1], false);
                           $clouddata->set($args[1] . "C", 0);
                           $clouddata->save();
                           
                       }
               
                   }
               	
               }
           	
           }
           
           case "Ban":
           if (isset($args[0])) {
           	
           	if ($sender->isOp()) {
           	
                   if (file_exists("/home/Cloud/players/" . $args[0] . ".yml")) {
                   	
                   	if (isset($args[1])) {
                   	
                   	    if (isset($args[2])) {
                   	
                   	        if ($args[2] < 1) {
                   	
                   	            $sender->sendMessage(Color::RED . "Diese Zeit ist ungültig!");
                   
                               } else {
                               	
                               	$cloudplayer = new Config("/home/Cloud/players/" . $args[0] . ".yml", Config::YAML);
                                   $cloudplayer->set("Ban", true);
                                   $cloudplayer->set("BanGrund", $args[1]);
                                   $cloudplayer->set("BanTime", $args[2]);
                                   $cloudplayer->save();
                                   $sender->sendMessage(Color::RED . "Der Spieler wurde erfolgreich vom Netzwerk gebannt!");
                                   $player = $this->getServer()->getPlayerExact($args[0]);
                                   $player->kick(
                                   Color::RED . "Du wurdest Gebannt\n" .
                                   Color::GREEN . "Grund: " . Color::RED . $cloudplayer->get("BanGrund") . "\n" .
                                   Color::GREEN . "Dauer: " . Color::RED . $cloudplayer->get("BanTime") . " Tag/e\n", false
                                   );
                               	
                               }
                               
                           } else {
                           	
                           	$sender->sendMessage(Color::RED . "Du benötigst eine Ban Zeit!");
                           	
                           }
                           
                       } else {
                       	
                       	$sender->sendMessage(Color::RED . "Du benötigst einen Ban Grund!");
                       	
                       }
                   	
                   } else {
                   	
                   	$sender->sendMessage(Color::RED . "Diesen Spieler gibt es auf dem Netzwerk nicht!");
                   	
                   }
           	    
               }
           	
           } else {
           	
           	$sender->sendMessage(Color::YELLOW . "/ban <Name> <Grund> <Dauer>");
           	
           }
           
           case "Unban":
           if (isset($args[0])) {
           	
           	if ($sender->isOp()) {
           	
           	    if (file_exists("/home/Cloud/players/" . $args[0] . ".yml")) {
           	
           	        $cloudplayer = new Config("/home/EnderCloud/players/" . $args[0] . ".yml", Config::YAML);
                       $cloudplayer->set("Ban", false);
                       $cloudplayer->save();
                       $sender->sendMessage(Color::GREEN . "Dieser Spieler wurde nun erfolgreich entbannt!");
                       
                   } else {
                   	
                   	$sender->sendMessage(Color::GREEN . "Diesen Spieler gibt es auf dem Netzwerk nicht!");
                   	
                   }
           
               }
           	
           } else {
           	
           	$sender->sendMessage(Color::YELLOW . "/unban <Name>");
           	
           }
           
        }
        
        return true;
    	
    }
    
    public function onLogin(PlayerLoginEvent $event) {
    	
    	$player = $event->getPlayer();
        $config = $this->getConfig();
        $clouddata = new Config("/home/Cloud/Daten.yml", Config::YAML);
        if (!is_file("/home/Cloud/players/" . $player->getName() . ".yml")) {
        	
        	$playerfile = new Config("/home/Cloud/players/" . $player->getName() . ".yml", Config::YAML);
            $playerfile->set("VIP", false);
            $playerfile->set("portal", false);
            $playerfile->set("smoke", false);
            $playerfile->set("lava", false);
            $playerfile->set("heart", false);
            $playerfile->set("flame", false);
            $playerfile->set("build", false);
            $playerfile->set("russi1", false);
            $playerfile->set("bombe", false);
            $playerfile->set("portal1", false);
            $playerfile->set("smoke1", false);
            $playerfile->set("lava1", false);
            $playerfile->set("heart1", false);
            $playerfile->set("flame1", false);
            $playerfile->set("fly", false);
            $playerfile->set("Jump", false);
            $playerfile->set("Speed", false);
            $playerfile->set("JumpA", false);
            $playerfile->set("SpeedA", false);
            $playerfile->set("open", false);
            $playerfile->set("time", 5);
            $playerfile->set("join", 10);
            $playerfile->set("ke", 0);
            $playerfile->set("Ban", false);
            $playerfile->set("BanTime", 0);
            $playerfile->set("BanGrund", "");
            $playerfile->save();
        	
        }
        
        if ($clouddata->get($config->get("Server")) === false) {
        	
        	if ($player->isOp()) {
        	
            } else {
            	
            	if ($config->get("Server") === "Main") {
            	    
                    $player->kick(
                    Color::RED . "Das Netzwerk ist gerade in Wartungsarbeiten\n" .
                    Color::AQUA . "Twitter: " . Color::RED . "@SyntoxienMC\n" .
                    Color::AQUA . "Discord: " . Color::RED . "Soon", false
                    );
            	    
                } else {
                	
                	$player->transfer("", 19132);
                	
                }
            	
            }
        	
        } else {
        	
        	$playerfile = new Config("/home/Cloud/players/" . $player->getName() . ".yml", Config::YAML);
            if ($playerfile->get("Ban") === true) {
            	
            	$player->kick(
                Color::RED . "Du wurdest Gebannt\n" .
                Color::GREEN . "Grund: " . Color::RED . $playerfile->get("BanGrund") . "\n" .
                Color::GREEN . "Dauer: " . Color::RED . $playerfile->get("BanTime") . " Tag/e\n", false
                );
            	
            }
        	
        }
        
    }
	
}
