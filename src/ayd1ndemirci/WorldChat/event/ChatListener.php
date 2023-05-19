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

namespace ayd1ndemirci\WorldChat\event;

use ayd1ndemirci\WorldChat\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Server;

class ChatListener implements Listener
{

    public Main $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }

    public function onChat(PlayerChatEvent $event): void
    {
        $player     = $event->getPlayer();
        $message    = $event->getMessage();
        $first_char = substr($message, 0, 1);
        $purechat   = Server::getInstance()->getPluginManager()->getPlugin("PureChat");
        $event->cancel();
        if ($first_char !== "!") {
            if (!in_array($player->getWorld()->getFolderName(), $this->main->worldChatMgr()->getFormats())) {
                if ($purechat != null) {
                    Server::getInstance()->broadcastMessage($this->main->worldChatMgr()->getFormat("default") . " " . $purechat->getChatFormat($player, $message), $player->getWorld()->getPlayers());
                } else {
                    Server::getInstance()->broadcastMessage($this->main->worldChatMgr()->getFormat("default") . " §r" . $player->getName() . " > " . $event->getMessage(), $player->getWorld()->getPlayers());
                }
            } else {
                if ($purechat != null) {
                    Server::getInstance()->broadcastMessage($this->main->worldChatMgr()->getFormat($player->getWorld()->getFolderName()) . " " . $purechat->getChatFormat($player, $message), $player->getWorld()->getPlayers());
                } else {
                    Server::getInstance()->broadcastMessage($this->main->worldChatMgr()->getFormat($player->getWorld()->getFolderName()) . " §r" . $player->getName() . " > " . $event->getMessage(), $player->getWorld()->getPlayers());
                }
            }
        } else {
            $message_delete_char = substr($message, 1);
            Server::getInstance()->broadcastMessage($this->main->worldChatMgr()->getFormat("global") . " " . ($purechat != null ? $purechat->getChatFormat($player, $message_delete_char) : "§r" . $player->getName() . " > " . $message_delete_char));
        }
    }
}