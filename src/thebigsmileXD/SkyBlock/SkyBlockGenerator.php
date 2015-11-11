<?php

/**
 *
 *
 **/
namespace thebigsmileXD\SkyBlock;

use pocketmine\level\generator\Generator;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\format\FullChunk;
use pocketmine\level\generator\populator\Populator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\level\generator\biome\Biome;

class SkyBlockGenerator extends Generator{
	const bedrockheight = 30;
	/**
	 *
	 * @var GenerationChunkManager
	 */
	private $level;
	/**
	 *
	 * @var FullChunk
	 */
	private $chunk1, $chunk2;
	/**
	 *
	 * @var Random
	 */
	private $random;
	/**
	 *
	 * @var Populator[]
	 */
	private $populators = [];

	public function getSettings(){
		return [];
	}

	public function getName(){
		return "skyblock";
	}

	public function __construct(array $settings = []){
		if(isset($settings["preset"])){
			$settings = json_decode($settings["preset"], true);
			if($settings === false){
				$settings = [];
			}
		}
		else{
			$settings = [];
		}
		$this->chunks = $this->parseNumber($settings, "Chunks", 8);
		
		$this->settings = [];
		$this->settings["preset"] = json_encode(["Chunks" => $this->chunks]);
	}

	private function parseBlock($array, $key, $default){
		if(isset($array[$key])){
			$id = $array[$key];
			if(is_numeric($id)){
				$block = new Block($id);
			}
			else{
				$split = explode(":", $id);
				if(count($split) === 2 and is_numeric($split[0]) and is_numeric($split[1])){
					$block = new Block($split[0], $split[1]);
				}
				else{
					$block = $default;
				}
			}
		}
		else{
			$block = $default;
		}
		return $block;
	}

	private function parseNumber($array, $key, $default){
		if(isset($array[$key]) and is_numeric($array[$key])){
			return $array[$key];
		}
		else{
			return $default;
		}
	}

	public function init(ChunkManager $level, Random $random){
		$this->level = $level;
		$this->random = $random;
	}

	public function generateChunk($chunkX, $chunkZ){
		$CX = ($chunkX % 5) < 0?(($chunkX % 5) + 5):($chunkX % 5);
		$CZ = ($chunkZ % 5) < 0?(($chunkZ % 5) + 5):($chunkZ % 5);
		switch($CX . ":" . $CZ){
			case '0:0':
				{
					if($this->chunk1 === null){
						$this->chunk1 = clone $this->level->getChunk($chunkX, $chunkZ);
						
						$c = Biome::getBiome(1)->getColor();
						$R = $c >> 16;
						$G = ($c >> 8) & 0xff;
						$B = $c & 0xff;
						for($x = 0; $x < 16; $x++){
							for($z = 0; $z < 16; $z++){
								$this->chunk1->setBiomeColor($x, $z, $R, $G, $B);
							}
						}
						for($x = 4; $x < 11; $x++){
							for($z = 4; $z < 11; $z++){
								$this->chunk1->setBlockId($x, self::bedrockheight + (68 - 64), $z, Block::GRASS);
							}
						}
						for($x = 5; $x < 10; $x++){
							for($z = 5; $z < 10; $z++){
								$this->chunk1->setBlockId($x, self::bedrockheight + (67 - 64), $z, Block::DIRT);
								$this->chunk1->setBlockId($x, self::bedrockheight + (72 - 64), $z, Block::LEAVES); // 72
							}
						}
						for($x = 6; $x < 9; $x++){
							for($z = 6; $z < 9; $z++){
								$this->chunk1->setBlockId($x, self::bedrockheight + (73 - 64), $z, Block::LEAVES); // 73
								$this->chunk1->setBlockId($x, self::bedrockheight + (66 - 64), $z, Block::DIRT); // 66
							}
						}
						$this->chunk1->setBlockId(7, self::bedrockheight + (64 - 64), 7, Block::BEDROCK); // 0
						$this->chunk1->setBlockId(7, self::bedrockheight + (65 - 64), 7, Block::SAND); // 1
						$this->chunk1->setBlockId(7, self::bedrockheight + (66 - 64), 7, Block::SAND); // 2
						$this->chunk1->setBlockId(7, self::bedrockheight + (67 - 64), 7, Block::SAND); // 3
						$this->chunk1->setBlockId(7, self::bedrockheight + (69 - 64), 7, Block::LOG); // 5
						$this->chunk1->setBlockId(7, self::bedrockheight + (70 - 64), 7, Block::LOG); // 6
						$this->chunk1->setBlockId(7, self::bedrockheight + (71 - 64), 7, Block::LOG); // 7
						$this->chunk1->setBlockId(7, self::bedrockheight + (72 - 64), 7, Block::LOG); // 8
						$this->chunk1->setBlockId(7, self::bedrockheight + (73 - 64), 7, Block::LOG); // 9
						$this->chunk1->setBlockId(4, self::bedrockheight + (68 - 64), 4, Block::AIR); // 68
						$this->chunk1->setBlockId(4, self::bedrockheight + (68 - 64), 10, Block::AIR);
						$this->chunk1->setBlockId(10, self::bedrockheight + (68 - 64), 4, Block::AIR);
						$this->chunk1->setBlockId(10, self::bedrockheight + (68 - 64), 10, Block::AIR);
						$this->chunk1->setBlockId(5, self::bedrockheight + (72 - 64), 5, Block::AIR); // 72
						$this->chunk1->setBlockId(5, self::bedrockheight + (72 - 64), 9, Block::AIR);
						$this->chunk1->setBlockId(9, self::bedrockheight + (72 - 64), 5, Block::AIR);
						$this->chunk1->setBlockId(9, self::bedrockheight + (72 - 64), 9, Block::AIR);
						$this->chunk1->setBlockId(5, self::bedrockheight + (73 - 64), 7, Block::LEAVES); // 73
						$this->chunk1->setBlockId(7, self::bedrockheight + (73 - 64), 5, Block::LEAVES);
						$this->chunk1->setBlockId(9, self::bedrockheight + (73 - 64), 7, Block::LEAVES);
						$this->chunk1->setBlockId(7, self::bedrockheight + (73 - 64), 9, Block::LEAVES);
						$this->chunk1->setBlockId(7, self::bedrockheight + (74 - 64), 6, Block::LEAVES); // 74
						$this->chunk1->setBlockId(6, self::bedrockheight + (74 - 64), 7, Block::LEAVES);
						$this->chunk1->setBlockId(8, self::bedrockheight + (74 - 64), 7, Block::LEAVES);
						$this->chunk1->setBlockId(7, self::bedrockheight + (74 - 64), 8, Block::LEAVES);
						$this->chunk1->setBlockId(7, self::bedrockheight + (75 - 64), 7, Block::LEAVES); // 75
						                                                                                 // $this->chunk1->setBlockId(7, self::bedrockheight + (69 - 64), 8, Block::CHEST);
						$this->chunk1->setBlockId(7, self::bedrockheight + (65 - 64), 8, Block::DIRT); // 65
						$this->chunk1->setBlockId(8, self::bedrockheight + (65 - 64), 7, Block::DIRT);
						$this->chunk1->setBlockId(7, self::bedrockheight + (65 - 64), 6, Block::DIRT);
						$this->chunk1->setBlockId(6, self::bedrockheight + (65 - 64), 7, Block::DIRT);
						$this->chunk1->setBlockId(5, self::bedrockheight + (66 - 64), 7, Block::DIRT); // 66
						$this->chunk1->setBlockId(7, self::bedrockheight + (66 - 64), 5, Block::DIRT);
						$this->chunk1->setBlockId(9, self::bedrockheight + (66 - 64), 7, Block::DIRT);
						$this->chunk1->setBlockId(7, self::bedrockheight + (66 - 64), 9, Block::DIRT);
						$this->chunk1->setBlockId(4, self::bedrockheight + (67 - 64), 7, Block::DIRT); // 67
						$this->chunk1->setBlockId(7, self::bedrockheight + (67 - 64), 4, Block::DIRT);
						$this->chunk1->setBlockId(7, self::bedrockheight + (67 - 64), 10, Block::DIRT);
						$this->chunk1->setBlockId(10, self::bedrockheight + (67 - 64), 7, Block::DIRT);
					}
					$chunk = clone $this->chunk1;
					$chunk->setX($chunkX);
					$chunk->setZ($chunkZ);
					$this->level->setChunk($chunkX, $chunkZ, $chunk);
					break;
				}
			
			default:
				{
					if($this->chunk2 === null){
						$this->chunk2 = clone $this->level->getChunk($chunkX, $chunkZ);
						
						$c = Biome::getBiome(1)->getColor();
						$R = $c >> 16;
						$G = ($c >> 8) & 0xff;
						$B = $c & 0xff;
						for($x = 0; $x < 16; $x++){
							for($z = 0; $z < 16; $z++){
								$this->chunk2->setBiomeColor($x, $z, $R, $G, $B);
							}
						}
						$chunk = clone $this->chunk2;
						$chunk->setX($chunkX);
						$chunk->setZ($chunkZ);
						$this->level->setChunk($chunkX, $chunkZ, $chunk);
						break;
					}
				}
		}
	}

	public function populateChunk($chunkX, $chunkZ){}

	public function getSpawn(){
		return new Vector3(151, self::bedrockheight + 10, 151);
	}
}