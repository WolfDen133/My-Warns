<?php


namespace WolfDen133\My_Warns\Commands;


use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use WolfDen133\My_Warns\Main;
use pocketmine\command\Command;

class RemoveWarnCommand extends Command implements PluginOwned
{
    /** @var Main */
    private $plugin;

    public function __construct(Main $main)
    {
        parent::__construct("removewarn");

        $this->setUsage("Usage: /removewarn <warnID: string>");
        $this->setPermissionMessage(TextFormat::RED . "Unknown command. Try /help for a list of commands");
        $this->setPermission("removewarn.command");
        $this->setDescription("Remove a false/old warn from a player");
        $this->setAliases(["rwarn", "rmwarn", "removew"]);

        $this->plugin = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission($this->getPermission())) {
            if (count($args) === 1) {
                if ($this->plugin->hasWarn($args[0])) {
                    $this->plugin->removeWarn($args[0]);
                    $sender->sendMessage("Successfully removed warn ID: " . TextFormat::AQUA . $args[0]);
                } else {
                    $sender->sendMessage(TextFormat::RED . "There are no logs of that player having a warn with the ID: " . $args[0]);
                }
            } else {
                $sender->sendMessage($this->getUsage());
            }
        } else {
            $sender->sendMessage($this->getPermission());
        }
    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}