<?php


namespace WolfDen133\My_Warns\Commands;


use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use WolfDen133\My_Warns\Main;

class SeeWarnsCommand extends Command implements PluginIdentifiableCommand
{

    /** @var Main */
    private Main $plugin;


    public function __construct(Main $main)
    {
        parent::__construct("seewarns");

        $this->setAliases(["seew", "swarns"]);
        $this->setDescription("See a players current warns");
        $this->setPermission("command.seewarns.use");
        $this->setPermissionMessage(TextFormat::RED . "Unknown command. Try /help for a list of commands");
        $this->setUsage("Usage: /seewarns <playername>");

        $this->plugin = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission($this->getPermission())) {
            if (count($args) === 1){
                $target = $args[0];

                if (is_file($this->plugin->getDataFolder() . "warns/" . $target . ".yml")) {
                    $warns = $this->plugin->getWarns($target);
                    
                    if (count($warns) === 1 and $warns[0] === false) {
                        $sender->sendMessage("$target has no warns.");
                        return;
                    }
                    
                    if ($warns === null) {
                        $sender->sendMessage("$target has no warns.");
                        return;
                    }

                    $sender->sendMessage("$target's warns: ");
                    foreach ($warns as $warn) {
                        if ($warn === false) {
                            continue;
                        }

                        $warn = explode("#:", $warn);

                        $id = $this->plugin->getID($target) . ":" . $warn[0];
                        $time = $warn[1] . "";
                        $reason = $warn[2];

                        $sender->sendMessage("====================================");
                        $sender->sendMessage(TextFormat::WHITE . "#" . $id . TextFormat::RESET . ":" ."\n Time: " . TextFormat::DARK_AQUA . $time . TextFormat::RESET . "\n Reason: " . TextFormat::AQUA . $reason . "\n");
                    }

                    $sender->sendMessage("====================================");
                    return;
                }

                $sender->sendMessage(TextFormat::RED . "There is no history of $target logging on this server");
            } else {
                $sender->sendMessage($this->getUsage());
            }
        } else {
            $sender->sendMessage($this->getPermissionMessage());
        }
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}
