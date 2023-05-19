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

namespace ayd1ndemirci\WorldChat\manager;

use ayd1ndemirci\WorldChat\Main;
use JsonException;

class WorldChat
{
    public Main $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }

    public function getFormats(): array
    {
        $result = [];
        foreach ($this->main->getData()->get("Format") as $worldName => $format) {
            $result[] = $worldName;
        }
        return $result;
    }

    /**
     * @throws JsonException
     */
    public function newFormat(string $worldName, string $format): void
    {
        $this->main->getData()->setNested("Format." . $worldName . ".Format", $format);
        $this->main->getData()->save();
    }

    public function setFormat(string $worldName, string $newFormat): void
    {
        $this->main->getData()->setNested("Format." . $worldName . ".Format", $newFormat);
        $this->main->getData()->save();
    }

    public function deleteFormat(string $worldName): void
    {
        $this->main->getData()->removeNested("Format." . $worldName);
        $this->main->getData()->save();
    }

    public function getFormat(string $worldName): string
    {
        return $this->main->getData()->get("Format")[$worldName]["Format"];
    }
}