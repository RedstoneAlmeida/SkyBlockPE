<?php
namespace thebigsmileXD\SkyBlock\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use thebigsmileXD\SkyBlock\Plot;

class ListSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("skyblock.command.list");
    }

    public function getUsage() {
        return "";
    }

    public function getName() {
        return "list";
    }

    public function getDescription() {
        return "List all the islands you own";
    }

    public function getAliases() {
        return [];
    }

    public function execute(CommandSender $sender, array $args) {
        if (!empty($args)) {
            return false;
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $levelName = $player->getLevel()->getName();
        $plots = $this->getPlugin()->getProvider()->getPlotsByOwner($sender->getName());
        if (empty($plots)) {
            $sender->sendMessage("You do not own any islands");
            return true;
        }
        $sender->sendMessage("Islands you own:");
        for ($i = 0; $i < count($plots); $i++) {
            $plot = $plots[$i];
            $message = TextFormat::DARK_GREEN . ($i + 1) . ") ";
            $message .= TextFormat::WHITE . $levelName . ": " . $plot->X . ";" . $plot->Z;
            if ($plot->name !== "") {
                $message .= " aka " . $plot->name;
            }
            $sender->sendMessage($message);
        }
        return true;
    }
}