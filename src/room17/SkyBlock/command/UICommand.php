<?php

namespace room17\SkyBlock\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use room17\SkyBlock\SkyBlock;
use pocketmine\utils\TextFormat as TF;

class UICommand extends Command
{
    public function __construct(SkyBlock $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("sbui", "SkyBlock UI Command", "Usage: /sbui", ["skyblockui"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $playerSession = $this->plugin->getSessionManager()->getSession($sender);
        if (!$playerSession->hasIsle()) {
            $this->newUI($sender);
        } else {
            $this->oldUI($sender);
        }
    }

    public function runCommand(Player $player, $command)
    {
        $this->plugin->getServer()->dispatchCommand($player, $command);

    }

    public function newUI(Player $player)
    {
        $formapi = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $player, ?int $data) {
            if (!is_null($data)) {
                switch ($data) {
                    case 0:
                        $this->runCommand($player, "is create");
                        $this->oldUI($player);
                        break;
                }
            }
        });
        $form->setTitle(TF::DARK_GREEN . "");
        $form->setContent("Welcome to SkyBlockPE! Press Create to make your island.");
        $form->addButton("Create");
        $form->sendToPlayer($player);
    }

    public function oldUI(Player $player)
    {
        $formapi = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $player, ?int $data) {
            if (!is_null($data)) {
                switch ($data) {
                    case 0:
                        $this->runCommand($player, "is join");
                        break;
                    case 1:
                        $this->manageUI($player);
                        break;
                }
            }
        });
        $form->setTitle(TF::DARK_GREEN . "");
        $form->setContent("Tap on a option below.");
        $form->addButton("Join your island");
        $form->addButton("Manage you island");
        $form->sendToPlayer($player);
    }

    public function manageUI(Player $player)
    {
        $formapi = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $player, ?int $data) {
            if (!is_null($data)) {
                switch ($data) {
                    case 0:
                        $this->runCommand($player, "is disband");
                        break;
                    case 1:
                        $this->oldUI($player);
                        break;
                }
            }
        });
        $form->setTitle(TF::DARK_GREEN . "SkyBlockPE - Manage");
        $form->setContent("Tap on a option below.");
        $form->addButton("Delete");
        $form->addButton("Back");
        $form->sendToPlayer($player);
    }
}