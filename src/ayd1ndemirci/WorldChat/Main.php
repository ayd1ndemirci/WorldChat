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

namespace ayd1ndemirci\WorldChat;

use ayd1ndemirci\WorldChat\command\WorldChatCommand;
use ayd1ndemirci\WorldChat\event\ChatListener;
use ayd1ndemirci\WorldChat\manager\WorldChat;
use JsonException;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{

    public Main $main;

    public Config $config;

    public string $lang;

    private array $currentLangs = [
        "tr",
        "en"
    ];

    public function onLoad(): void
    {
        $this->main = $this;
        $this->saveResource("config.yml");
        $this->saveResource("command.yml");
        $this->saveResource("lang_en.yml");
        $this->saveResource("lang_tr.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
    }

    /**
     * @throws JsonException
     */
    public function onEnable(): void
    {
        $this->getLogger()->info("WorldChat active - https://github.com/ayd1ndemirci");
        if (!$this->getData()->exists("language")) {
            $this->getData()->set("language", "en");
            $this->getData()->save();
        }

        if (!in_array($this->getData()->get("language"), $this->currentLangs)) {
            $this->getData()->set("language", "en");
            $this->getData()->save();
        }

        $this->lang = $this->getData()->get("language");
        $config     = new Config($this->main->getDataFolder() . "command.yml", 2);
        $this->getServer()->getCommandMap()->register($config->get("command"), new WorldChatCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new ChatListener($this), $this);
    }

    public function getData(): Config
    {
        return $this->config;
    }

    public function worldChatMgr(): WorldChat
    {
        return new WorldChat($this);
    }
}