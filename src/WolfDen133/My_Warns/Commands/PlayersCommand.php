<?php

namespace WolfDen133\My_Warns\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use WolfDen133\My_Warns\Main;

class PlayersCommand extends Command implements PluginIdentifiableCommand
{
    /** @var Main */
    private Main $plugin;

    public function __construct(Main $main)
    {
        parent::__construct("players");

        $this->setUsage("Usage: /players");
        $this->setPermissionMessage(TextFormat::RED . "Unknown command. Try /help for a list of commands");
        $this->setPermission("command.players.use");
        $this->setDescription("See all the current logged on players");
        $this->setAliases(["p"]);

        $this->plugin = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission($this->getPermission())) {

            $players = [];
            $ops = [];
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                $player->isOp() ? array_push($ops, $player->getName()) : array_push($players, $player->getName());
            }

            $sender->sendMessage("========================\nOnline players:\n" . TextFormat::AQUA . implode(TextFormat::RESET . ", " . TextFormat::AQUA, $players) . TextFormat::RESET . "\n\nOnline operators: \n"  . TextFormat::AQUA . implode(TextFormat::RESET. ", " . TextFormat::AQUA, $ops) . "\n========================");

        } else {
            $sender->sendMessage($this->getPermissionMessage());
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}