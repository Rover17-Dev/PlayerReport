<?php

declare(strict_types=1);

namespace Rover17\PlayerReport;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\Server;

use common\ServerComposer;
use ialdrich23xx\libasynwebhook\discord\body\Base;
use ialdrich23xx\libasynwebhook\discord\body\embed\components\Author;
use ialdrich23xx\libasynwebhook\discord\body\embed\components\Footer;
use ialdrich23xx\libasynwebhook\discord\body\embed\EmbedColors;
use ialdrich23xx\libasynwebhook\discord\body\embed\EmbedManager;
use ialdrich23xx\libasynwebhook\discord\WebHook;
use ialdrich23xx\libasynwebhook\Loader;

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
	

        WebHook::make($this->config["webhook"], Base::make()
            ->addEmbed(EmbedManager::make("Player REPORT", "Player $pseudo Reason $reason Details $details", EmbedColors::Red)
                ->setFooter(Footer::make("PlayerReport plugin")
                    ->setIcon("url_icon"))
                ->setAuthor(Author::make("test")
               //     ->setUrl("")
               //     ->setIcon(""))
            )
        )->send();
			});
			
		$sender->sendForm($form);

						return true;

	}
	     }

}











