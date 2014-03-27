<?php

namespace Hexmedia\NewsletterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class SendCommand extends ContainerAwareCommand {

	protected function configure() {
		parent::configure();

		$this
			->setName('hexmedia:newsletter:send')
			->setDescription("Send Newsletter")
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
        $sendService = $this->getContainer()->get("hexmedia.newsletter.sender");

        $sendService->sendMany(20);
	}

    private function getDefaultInput(InputInterface $input) {
//        $newInput = new ArgvInput();

//        $newInput->setOption("env", $input->getOption("env"));

        return $input; //$newInput;
    }

}

