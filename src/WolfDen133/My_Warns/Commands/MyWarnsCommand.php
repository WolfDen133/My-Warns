<?php

namespace WolfDen133\My_Warns\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

use WolfDen133\My_Warns\Main;

class MyWarnsCommand extends Command implements PluginIdentifiableCommand
{

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("mywarns");

        $this->setAliases(["myw", "mwarns"]);
        $this->setDescription("See your warns");
        $this->setPermission("mywarns.command.mywarns");
        $this->setPermissionMessage(TextFormat::RED . "Unknown command. Try /help for a list of commands");
        $this->setUsage("Usage: /mywarns");

        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
       if ($sender->hasPermission($this->getPermission())) {
           if ($sender instanceof Player) {
               $warns = $this->plugin->getWarns($sender->getName());

               if (count($warns) === 1 and $warns[0] == false or $warns === null) {
                   $sender->sendMessage("You have no warns, well done!");
                   return;
               }

               $sender->sendMessage("Your warns: ");
               foreach ($warns as $warn) {
                   if (!$warn) {
                       continue;
                   }

                   $warn = explode("#:", $warn);

                   $id = $this->plugin->getID($sender->getName()) . ":" . $warn[0];
                   $time = $warn[1];
                   $reason = $warn[2];

                   $sender->sendMessage("====================================");
                   $sender->sendMessage(TextFormat::WHITE . "#" . $id . TextFormat::RESET . ":" ."\n Time: " . TextFormat::DARK_AQUA . $time . TextFormat::RESET . "\n Reason: " . TextFormat::AQUA . $reason . "\n");
               }

               $sender->sendMessage("====================================");
               return;

           } else {
               $sender->sendMessage("This command is for players only.");
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