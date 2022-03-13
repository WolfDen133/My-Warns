<?php

namespace WolfDen133\My_Warns;

use pocketmine\command\Command;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use WolfDen133\My_Warns\Commands\MyWarnsCommand;
//use WolfDen133\My_Warns\Commands\PlayersCommand;
use WolfDen133\My_Warns\Commands\RemoveWarnCommand;
use WolfDen133\My_Warns\Commands\SeeWarnsCommand;
use WolfDen133\My_Warns\Commands\WarnCommand;

class Main extends PluginBase implements Listener {

    /**
     * @package MyWarns
     * @author WolfDen133
     */

    /** @var int[] */
    private $ids;

    private static $instance;
    
    public function onLoad() : void
    {
        $this->saveDefaultConfig();

        foreach ($this->getResources() as $resource) $this->saveResource($resource->getFilename());

    }

    public function onEnable() : void
    {

        $this->ids = [];

        $this->unregisterCommands();
        $this->registerCommands();
        $this->registerIDs();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        self::$instance = $this;
    }


    /** Sub-routines */

    private function registerIDs() : void
    {
        if (!is_dir($this->getDataFolder() . "warns/")) mkdir($this->getDataFolder() . "warns/");

        if (!is_file($this->getDataFolder() . "ids.yml")) {
            $config = new Config($this->getDataFolder() . "ids.yml", Config::YAML);

            $config->save();
        }
        $config = new Config($this->getDataFolder() . "ids.yml", Config::YAML);

        $ids = (array) $config->getAll();
        foreach ($ids as $id) {
            $this->ids[array_search($id, $ids)] = (int)$id;
        }

    }

    private function registerCommands () : void
    {
        $cmap = $this->getServer()->getCommandMap();

        $cmap->register("My-Warns", new WarnCommand($this));
        $cmap->register("My-Warns", new MyWarnsCommand($this));
        $cmap->register("My-Warns", new RemoveWarnCommand($this));
        $cmap->register("My-Warns", new SeeWarnsCommand($this));
    }

    private function unregisterCommands () : void
    {
        $cmap = $this->getServer()->getCommandMap();

        if ($cmap->getCommand("warn") instanceof Command) $cmap->unregister($cmap->getCommand("warn"));
        if ($cmap->getCommand("mywarns") instanceof Command) $cmap->unregister($cmap->getCommand("mywarns"));
        if ($cmap->getCommand("removewarn") instanceof Command) $cmap->unregister($cmap->getCommand("removewarn"));
        if ($cmap->getCommand("seewarns") instanceof Command) $cmap->unregister($cmap->getCommand("seewarns"));
    }

    private function genID () : int
    {
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        while (true) {
            $id = $array[mt_rand(0, 8)] . $array[mt_rand(0, 8)] . $array[mt_rand(0, 8)] . $array[mt_rand(0, 8)];

            if (!in_array($id, $this->ids)) {

                return (int) $id;
            }
        }
    }

    public function registerPlayer (Player $player) : void
    {
        if (!is_file($this->getDataFolder() . "warns/" . $player->getName() . ".yml")) {

            $config = new Config($this->getDataFolder() . "warns/" . $player->getName() . ".yml", Config::YAML);

            $id = $this->genID();

            $config->set("ID", $id);
            $config->save();

            $config = new Config($this->getDataFolder() . "ids.yml", Config::YAML);

            $config->set($player->getName(), $id);
            $config->save();

            $this->ids[$player->getName()] = $id;
        }
    }

    /** Listener */

    public function onJoin (PlayerJoinEvent $event) {
        $player = $event->getPlayer();

        $this->registerPlayer($player);
    }


    /**
     *     ###    ########  ####
     *    ## ##   ##     ##  ##
     *   ##   ##  ##     ##  ##
     *  ##     ## ########   ##
     *  ######### ##         ##
     *  ##     ## ##         ##
     *  ##     ## ##        ####
     *
     */

    /**
     * @param string $id
     * @return string
     * @example $this->getServer()->getPlugin("MyWarns")->getPlayerNameFromID($id);
     */
    public function getPlayerNameFromID(string $id) : string
    {
        $id = explode(":", $id);

        if (is_file($this->getDataFolder() . "ids.yml")) {
            $config = new Config($this->getDataFolder() . "ids.yml", Config::YAML);

            return (string) array_search($id[0], $config->getAll());
        }
        return "";
    }

    /**
     * @param string $playername
     * @return string[]
     * @example $this->getServer()->getPlugin("MyWarns")->getWarns("WolfDen133");
     */
    public function getWarns(string $playername) : array
    {
        if (is_file($this->getDataFolder() . "warns/" . $playername . ".yml")) {
            $config = new Config($this->getDataFolder() . "warns/" . $playername . ".yml", Config::YAML);

            if ($config->exists("Warns")) {

                return $config->get("Warns");

            }

            return [""];
        }

        return [""];
    }

    /**
     * @param string $playername
     * @return int
     * @example $this->getServer()->getPlugin("MyWarns")->getID("WolfDen133");
     */
    public function getID (string $playername) : int
    {
        if (is_file($this->getDataFolder() . "warns/" . $playername . ".yml")) {
            $config = new Config($this->getDataFolder() . "warns/" . $playername . ".yml", Config::YAML);

            return (int) $config->get("ID");
        }

        return 0;
    }


    /**
     * @param string $warnId
     * @return bool
     * @example $this->getServer()->getPlugin("MyWarns")->hasWarn($id);
     */
    public function hasWarn (string $warnId) : bool
    {
        $playername = $this->getPlayerNameFromID((explode(":", $warnId))[0]);

        if (is_file($this->getDataFolder() . "warns/" . $playername . ".yml")) {
            $config = new Config($this->getDataFolder() . "warns/" . $playername . ".yml", Config::YAML);

            if ($config->exists("Warns")) {

                $warns = (array)$config->get("Warns");

                $ids = [];
                foreach ($warns as $warn) {
                    if ($warn) {
                        $id = str_replace("#", "", (explode("#:", $warn))[0]);

                        array_push($ids, $config->get("ID") . ":" . $id);
                    }
                }

                if (in_array($warnId, $ids)) {
                    return true;
                }

                return false;
            }
        }

        return false;
    }

    /**
     * @param string $warnId
     * @example $this->getServer()->getPlugin("MyWarns")->removeWarn($id);
     */
    public function removeWarn (string $warnId) : void
    {
        $info = str_replace("#", "", explode(":", $warnId));

        $playername = $this->getPlayerNameFromID($info[0]);

        if (is_file($this->getDataFolder() . "warns/" . $playername . ".yml")) {
            $config = new Config($this->getDataFolder() . "warns/" . $playername . ".yml", Config::YAML);

            $warns = (array)$config->get("Warns");

            foreach ($warns as $warn) {

                if (!$warn) continue;

                $id = $info[1];

                $cid = (explode("#:", $warn))[0];

                if ($id === $cid) {

                    $key = array_search($warn, $warns);

                    unset($warns[$key]);

                }
            }

            $config->set("Warns", $warns);
            $config->save();
        }
    }


    /**
     * @param string $playername
     * @param string $warn
     * @example $this->getServer()->getPlugin("MyWarns")->addWarn("WolfDen133", "Profanity");
     */
    public function addWarn (string $playername, string $warn) : void
    {
        if (is_file($this->getDataFolder() . "warns/" . $playername . ".yml")) {
            $config = new Config($this->getDataFolder() . "warns/" . $playername . ".yml", Config::YAML);
            $warns = (array)$config->get("Warns");

            $id = count($warns);

            date_default_timezone_set($this->getConfig()->get("Time-Zone", "UTC"));
            $time = date($this->getConfig()->get("Time-Format", "d-m-y, h:m:s"));

            array_push($warns, $id . "#:" . $time . "#:" . $warn);

            $config->set("Warns", $warns);
            $config->save();
        }
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

}
