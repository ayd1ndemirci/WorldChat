<?php

/*
                 _ __           _                _          _
                | /_ |         | |              (_)        (_)
  __ _ _   _  __| || |_ __   __| | ___ _ __ ___  _ _ __ ___ _
 / _` | | | |/ _` || | '_ \ / _` |/ _ \ '_ ` _ \| | '__/ __| |
| (_| | |_| | (_| || | | | | (_| |  __/ | | | | | | | | (__| |
 \__,_|\__, |\__,_||_|_| |_|\__,_|\___|_| |_| |_|_|_|  \___|_|
        __/ |
       |___/
 */

namespace ayd1ndemirci\WorldChat\command;

use ayd1ndemirci\WorldChat\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;

class WorldChatCommand extends Command
{
    public Main $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
        $config     = new Config($main->getDataFolder() . "command.yml", 2);
        parent::__construct($config->get("command"), $config->get("description"));
        $this->setAliases($config->get("aliases"));
        $this->setPermission("worldchat.perm");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!Server::getInstance()->isOp($sender->getName())) return;
        $lang_config = new Config($this->main->getDataFolder() . "lang_" . $this->main->lang . ".yml", 2);

        if (!isset($args[0])) {
            $sender->sendMessage("\n§a--------------- §l§8* §r§a---------------\n\n             §a§lWORLD CHAT§r      \n\n" . $lang_config->get("new-world-format") . ": §7/" . $this->getName() . " new worldName formatMessage\n" . $lang_config->get("set-format") . ": §/" . $this->getName() . " set worldName\n" . $lang_config->get("delete-format") . ": §7/" . $this->getName() . " delete worldName\n" . $lang_config->get("all-format") . ": §7/" . $this->getName() . " list\n" . $lang_config->get("see-author") . ": §7/" . $this->getName() . " author \n\n             §a§lWORLD CHAT§r      \n\n§a--------------- §l§8* §r§a---------------\n");
            return;
        }

        if ($args[0] === "author") {
            $sender->sendMessage($lang_config->get("author"));
            return;
        }

        if ($args[0] === "list") {
            $formats = "";
            foreach ($this->main->getData()->get("Format") as $worldName => $format) {
                $formats .= "§7" . $worldName . " §a format §7" . $this->main->getData()->get("Format")[$worldName]["Format"] . "§r\n";
            }
            $sender->sendMessage("\n§a--------------- §l§8* §r§a---------------\n\n             §a§lWORLD CHAT§r      \n\n{$formats}\n             §a§lWORLD CHAT§r      \n\n§a--------------- §l§8* §r§a---------------\n");
            return;
        }

        if ($args[0] === "new") {
            if (!isset($args[1], $args[2])) {
                $sender->sendMessage("§8» §cUsage: §7/" . $this->getName() . " new worldName formatMessage");
                return;
            }
            $worldName = $args[1];
            $format    = $args[2];
            if (!in_array($worldName, Server::getInstance()->getWorldManager()->getWorlds())) {
                if (!in_array($worldName, $this->main->worldChatMgr()->getFormats())) {
                    $this->main->worldChatMgr()->newFormat($worldName, $format);
                    $sender->sendMessage($lang_config->get("created-format"));
                } else $sender->sendMessage($lang_config->get("already-world-used"));
            } else $sender->sendMessage($lang_config->get("world-not-found"));
        }

        if ($args[0] === "set") {
            if (!isset($args[1], $args[2])) {
                $sender->sendMessage("§8» §cUsage: §/" . $this->getName() . " set worldName newFormat");
                return;
            }
            $worldName = $args[1];
            $newFormat = $args[2];
            if (in_array($worldName, $this->main->worldChatMgr()->getFormats())) {
                $this->main->worldChatMgr()->setFormat($worldName, $newFormat);
                $sender->sendMessage($lang_config->get("set-format-message"));
            } else $sender->sendMessage($lang_config->get("world-not-found"));
        }

        if ($args[0] === "delete") {
            if (!isset($args[1])) {
                $sender->sendMessage("§8» §cUsage: §7/" . $this->getName() . " delete worldName");
                return;
            }
            $worldName = $args[1];
            if (in_array($worldName, $this->main->worldChatMgr()->getFormats())) {
                $this->main->worldChatMgr()->deleteFormat($worldName);
                $sender->sendMessage($lang_config->get("removed-format"));
            } else $sender->sendMessage($lang_config->get("format-not-found"));
        }

    }
}