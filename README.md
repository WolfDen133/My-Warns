# My-Warns
A simple, easy-to-use, warn system for pocketmine 3.0.0

 This plugin is under General Public Copyright, feel free to look at the code.
 If you are a beginner in php and PocketMineMP, I highly suggest you join my discord server @ https://discord.gg/XJ6CPpTDZE
 I also please advice you don't redistribute this plugin, all of this plugin was hand coded and would be a real shame if it was copied and redistributed.
 How ever if you feel like using this plugin as a start for your coding project feel free.
 Have fun with the plugin. You can access me on my discord server above, good luck.
 
## Info

IDs are provided in "user:warn" format, for remove command, all the other commands use a player name basis.
Feel free to join my server, ask questions, report bugs and suggest features. Feel free to look through the [WiKi](https://github.com/WolfDen133/My-Warns/wiki)!

## Commands

| Command | Permission | Aliases | Description |
| --------- | --------- | --------- | --------- |
| `warn` | `command.warn.use` | ~ | Warn a player for bad behaviour |
| `removewarn` | `command.removewarn.use` | `rwarn`, `rmwarn`, `removew` | Remove a false or old warn |
| `mywarns` | `command.mywarns.use` | `myw`, `mwarns` | See your own warns |
| `seewarns` | `command.seewarns.use` | `seew`, `swarns` | See a players current warns |
| `players` | `command.players.use` | `p` | See a list of current online players |

## Permissions
| Permission | Default |
| --------- | --------- |
| `command.warn.use` | `op` |
| `command.removewarn.use` | `op` |
| `command.seewarns.use` | `op` |
| `command.mywarns.use` | `true` |
| `command.players.use` | `true` |

## API

ID format comes in "playerid:warnid" 

Add warn:

  Adds a warn to a spasific player
  ```php 
  $this->getServer()->getPlugin("MyWarns")->addWarn("WolfDen133", "Profanity");
  ```
  
Remove warn: 

  Removes the warn with that ID if valid
  ```php
  $this->getServer()->getPlugin("MyWarns")->removeWarn($id);
  ```

Has warn:

  Returns true, if the warn ID is valid, else returns false
  ```php
  $this->getServer()->getPlugin("MyWarns")->hasWarn($id);
  ```
  
Get ID:

  Gets the players ID
  ```php
  $this->getServer()->getPlugin("MyWarns")->getID("WolfDen133");
  ```
  
Get warns:

  Gets the warns of a player by name
  ```php
  $this->getServer()->getPlugin("MyWarns")->getWarns("WolfDen133");
  ```
  
Get player name by ID:

  Returns the player name from the ID if valid
  ```php
  $this->getServer()->getPlugin("MyWarns")->getPlayerNameFromID($id);
  ```
  
## TODO

Nothing please let me know if you need anything else :)
  

