<?php
namespace thebigsmileXD\SkyBlock\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class NameSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("skyblock.command.name");
    }

    public function getUsage() {
        return "<name>";
    }

    public function getName() {
        return "name";
    }

    public function getDescription() {
        return "Names the island that you are standing on";
    }

    public function getAliases() {
        return [];
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) !== 1) {
            return false;
        }
        
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . "You are not standing on an island");
            return true;
        }
        if ($plot->owner !== $sender->getName() and !$sender->hasPermission("skyblock.admin.name")) {
            $sender->sendMessage(TextFormat::RED . "You are not the owner of this island");
            return true;
        }
        
        $name = $args[0];
        $plot->name = $name;
        if ($this->getPlugin()->getProvider()->savePlot($plot)) {
            $sender->sendMessage(TextFormat::GREEN . "Changed the name of " . TextFormat::WHITE . $plot .
                                 TextFormat::GREEN . " to " . TextFormat::WHITE . $name);
        } else {
            $sender->sendMessage(TextFormat::RED . "Could not change the name.");
        }
        return true;
    }
}
