<?php

namespace Joram\SyliusNewsletterPlugin\Command;

use Joram\SyliusNewsletterPlugin\Service\NewsletterSendService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class NewsletterSendCommand extends Command
{
    protected static $defaultName = 'joram:newsletter:send';
    private NewsletterSendService $newsletterSendService;

    public function __construct(NewsletterSendService $newsletterSendService)
    {
        $this->newsletterSendService = $newsletterSendService;
        parent::__construct();
    }

    /**
     * @param OutputInterface $output
     * @param int $lines
     */
    public static function clearLine(OutputInterface $output, int $lines = 1): void
    {
        // move cursor up n lines

        $output->write("\x1b[{$lines}A");
        // erase to end of screen
        $output->writeln("\x1b[0J");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        if(!ctype_digit($id)) {
            echo "Error sending newsletter. Newsletter id must be a numeric value.\n";
            return Command::FAILURE;
        }
        $this->newsletterSendService->send($id, $output);
        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'The newsletter ID.');
        parent::configure();
    }

}
