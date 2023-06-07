<?php

declare(strict_types=1);

namespace Rover17\PlayerReport;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\Server;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed; 

use EasyUI\element\Dropdown;
use EasyUI\element\Input;
use EasyUI\element\Label;
use EasyUI\element\Option;
use EasyUI\element\Toggle;
use EasyUI\utils\SubmitListener;
use EasyUI\element\Button;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use EasyUI\variant\SimpleForm;
use EasyUI\icon\ButtonIcon;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Main extends PluginBase {
	private static Main $instance;


	public function onEnable() : void{
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->config = $this->getConfig()->getAll();


	}


public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
	switch($command->getName()){
     case "report":
		$form = new CustomForm("Report a player!");
		$form->addElement("name", new Input("Player's Name"));
		
		$dropdown = new Dropdown("Reason of report");
		$dropdown->addOption(new Option("cheat", "Cheat"));
		$dropdown->addOption(new Option("spam", "Spam"));
		$dropdown->addOption(new Option("other", "Other"));

		$form->addElement("reason", $dropdown);
		
		$form->addElement("details", new Input("Details"));

		$form->setSubmitListener(function(Player $player, FormResponse $response) {
			$pseudo = $response->getInputSubmittedText("name");
			$reason= $response->getDropdownSubmittedOptionId("reason");
			$details = $response->getInputSubmittedText("details");
			$webHook = new Webhook($this->config["webhook"]);
			$msg = new Message();
					$embed = new Embed();
		$embed->setTitle("Report");
		$embed->setColor(0x0000FF);
		$embed->addField("Player", "$pseudo");
		$embed->addField("Reason", "$reason", true);
		$embed->addField("Details", "$details$", true);
		$embed->addField(":", "S'agit t'il d'une erreur ?", true);
		
		$embed->setFooter("Report - 2023","https://cortexpe.xyz/utils/kitsu.png");
			$msg->addEmbed($embed);
		
			$webHook->send($msg);
			});
			
		$sender->sendForm($form);

						return true;

	}
	     }

}











