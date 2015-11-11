<?php

namespace thebigsmileXD\SkyBlock\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\block\Chest;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\String;
use pocketmine\nbt\tag\Int;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\block\Block;

class ClaimSubCommand extends SubCommand{

	public function canUse(CommandSender $sender){
		return ($sender instanceof Player) and $sender->hasPermission("skyblock.command.claim");
	}

	public function getUsage(){
		return "[name]";
	}

	public function getName(){
		return "claim";
	}

	public function getDescription(){
		return "Claim the island you're standing on";
	}

	public function getAliases(){
		return [];
	}

	public function execute(CommandSender $sender, array $args){
		if(count($args) > 1){
			return false;
		}
		$name = "";
		if(isset($args[0])){
			$name = $args[0];
		}
		$player = $sender->getServer()->getPlayer($sender->getName());
		$plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
		if($plot === null){
			$sender->sendMessage(TextFormat::RED . "You are not standing on an island");
			return true;
		}
		if($plot->owner != ""){
			if($plot->owner === $sender->getName()){
				$sender->sendMessage(TextFormat::RED . "You already own this island");
			}
			else{
				$sender->sendMessage(TextFormat::RED . "This island is already claimed by " . $plot->owner);
			}
			return true;
		}
		$plotLevel = $this->getPlugin()->getLevelSettings($plot->levelName);
		$maxPlotsInLevel = $plotLevel->maxPlotsPerPlayer;
		$maxPlots = $this->getPlugin()->getConfig()->get("MaxPlotsPerPlayer");
		$plotsOfPlayer = $this->getPlugin()->getProvider()->getPlotsByOwner($player->getName());
		if($maxPlotsInLevel >= 0 and count($plotsOfPlayer) >= $maxPlotsInLevel){
			$sender->sendMessage(TextFormat::RED . "You reached the limit of $maxPlotsInLevel islands per player in this world");
			return true;
		}
		elseif($maxPlots >= 0 and count($plotsOfPlayer) >= $maxPlots){
			$sender->sendMessage(TextFormat::RED . "You reached the limit of $maxPlots islands per player");
			return true;
		}
		
		$economy = $this->getPlugin()->getEconomyProvider();
		if($economy !== null and !$economy->reduceMoney($player, $plotLevel->claimPrice)){
			$sender->sendMessage(TextFormat::RED . "You don't have enough money to claim this island");
			return true;
		}
		
		$plot->owner = $sender->getName();
		$plot->name = $name;
		if($this->getPlugin()->getProvider()->savePlot($plot)){
			if($sender instanceof Player){
				$sender->sendMessage(TextFormat::GREEN . "You are now the owner of " . TextFormat::WHITE . $plot);
				// chest
				$position = $this->getPlugin()->getPlotPosition($plot);
				// debug
				$position = $player->getPosition();
				$position instanceof Position;
				$chest = $position->getLevel()->getBlock(new Vector3(floor($position->getX()), 30 + (69 - 64), floor($position->getZ())));
				$position->getLevel()->setBlock(new Vector3(floor($position->getX()), 30 + (69 - 64), floor($position->getZ())), new Block(Block::CHEST), true, true);
				$nbt = new Compound("", [new Enum("Items", []),new String("id", Tile::CHEST),new Int("x", floor($position->getX())),new Int("y", floor($position->getY())),new Int("z", floor($position->getZ()))]);
				$nbt->Items->setTagType(NBT::TAG_Compound);
				$tile = Tile::createTile("Chest", $sender->getLevel()->getChunk(floor($position->getX()) >> 4, floor($position->getZ()) >> 4), $nbt);
				
				if(!($tile instanceof \pocketmine\tile\Chest)) continue;
				$tile->getInventory()->addItem(new Item(Item::ICE, 0, 2), new Item(Item::BUCKET, 10, 1), new Item(Item::MELON_SLICE, 0, 1), new Item(Item::CACTUS, 0, 1), new Item(Item::RED_MUSHROOM, 0, 1), new Item(Item::BROWN_MUSHROOM, 0, 1), new Item(Item::PUMPKIN_SEEDS, 0, 1), new Item(Item::SUGAR_CANE, 0, 1), new Item(Item::SIGN, 0, 1));
				$sender->sendTip(TextFormat::GREEN . TextFormat::BOLD . "A new SkyBlock\n" . TextFormat::GOLD . "by thebigsmileXD");
			}
		}
		else{
			$sender->sendMessage(TextFormat::RED . "Something went wrong");
		}
		return true;
	}
}