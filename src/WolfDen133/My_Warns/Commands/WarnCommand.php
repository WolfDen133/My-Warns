<?php


namespace WolfDen133\My_Warns\Commands;


use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\Player;

use pocketmine\plugin\Plugin;

use pocketmine\utils\TextFormat;

use WolfDen133\My_Warns\Main;


class WarnCommand extends Command implements PluginIdentifiableCommand
{

    /** @var Main */
    private Main $plugin;

    public function __construct(Main $main)
    {
        $this->plugin = $main;

        parent::__construct("warn");

        $this->setDescription("Give a player a warn for bad behaviour");
        $this->setUsage("Usage: /warn [target: string...] <warn: string> (-s: string)");
        $this->setPermissionMessage(TextFormat::RED . "Unknown Command. Try /help for a list of commands");
        $this->setPermission("command.warn.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission($this->getPermission())) {
            if (count($args) >= 2) {

                $targets = explode(",", $args[0]);
                $warned = [];

                foreach ($targets as $target) {

                    $name = $target;
                    $target = $this->plugin->getServer()->getPlayer($target);

                    if ($target instanceof Player) {

                        $warn = implode(" ", array_slice($args, 1));

                        $this->plugin->addWarn($target->getName(), str_replace("-s", "", $warn));

                        array_push($warned, $target->getName());

                        $target->sendMessage("You were warned by " . TextFormat::DARK_AQUA . $sender->getName() . TextFormat::RESET . ", for: " . TextFormat::AQUA . str_replace("-s", "", implode(" ", array_slice($args, 1))));


                    } else {

                        $target = $this->plugin->getServer()->getOfflinePlayer($args[0]);

                        if (file_exists($this->plugin->getDataFolder() . "warns/" . $target->getName() . ".yml")) {

                            $this->plugin->addWarn($target->getName(), implode(" ", array_slice($args, 1)));

                            array_push($warned, $target->getName());

                        } else {

                            $sender->sendMessage(TextFormat::RED . "The player " . $name . " has no history of logging on to this server");

                        }
                    }
                }

                if (strtolower($args[count($args)-1]) !== "-s") {
                    if (count($warned) > 0) $this->plugin->getServer()->broadcastMessage(TextFormat::DARK_GREEN . $sender->getName() . TextFormat::RESET . " warned " . TextFormat::DARK_AQUA . implode(", ", $warned) . TextFormat::RESET . ", for: " . TextFormat::AQUA . str_replace("-s", "",implode(" ", array_slice($args, 1))));
                    return;
                }

                if (count($warned) > 0) $sender->sendMessage("You warned " . TextFormat::DARK_AQUA . implode(", ", $warned) . TextFormat::RESET . ", for: " . TextFormat::AQUA . str_replace("-s", "",implode(" ", array_slice($args, 1))));

            } else {

                $sender->sendMessage($this->getUsage());
            }
        } else {

            $sender->sendMessage($this->getPermissionMessage());
        }
    }

    public function getPlugin() : Plugin
    {
        return $this->plugin;
    }
}