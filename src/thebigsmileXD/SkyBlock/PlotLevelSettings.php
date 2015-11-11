<?php
namespace thebigsmileXD\SkyBlock;

use pocketmine\block\Block;
class PlotLevelSettings
{
    /** @var string */
    public $name;
    /** @var int */
    public $chunks, $maxPlotsPerPlayer, $claimPrice, $clearPrice,
            $disposePrice, $resetPrice;

    public function __construct($name, $settings = []) {
        $this->name = $name;
        if (!empty($settings)) {
            $this->chunks = self::parseNumber($settings, "Chunks", 8);
            $this->maxPlotsPerPlayer = self::parseNumber($settings, "MaxPlotsPerPlayer", 5);
            $this->claimPrice = self::parseNumber($settings, "ClaimPrice", 0);
            $this->clearPrice = self::parseNumber($settings, "ClearPrice", 0);
            $this->disposePrice = self::parseNumber($settings, "DisposePrice", 0);
            $this->resetPrice = self::parseNumber($settings, "ResetPrice", 0);
        }
    }

    private static function parseBlock(&$array, $key, $default) {
        if (isset($array[$key])) {
            $id = $array[$key];
            if (is_numeric($id)) {
                $block = new Block($id);
            } else {
                $split = explode(":", $id);
                if (count($split) === 2 and is_numeric($split[0]) and is_numeric($split[1])) {
                    $block = new Block($split[0], $split[1]);
                } else {
                    $block = $default;
                }
            }
        } else {
            $block = $default;
        }
        return $block;
    }

    private static function parseNumber(&$array, $key, $default) {
        if (isset($array[$key]) and is_numeric($array[$key])) {
            return $array[$key];
        } else {
            return $default;
        }
    }
}