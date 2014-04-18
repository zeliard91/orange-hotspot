<?php
namespace Orange\Command;

use Orange\Command\FormattedCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Goutte\Client;

class StatusCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('orange:status')
            ->setDescription('Display connection status')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->outputMessage('Testing connection to www.google.com ...');

        $client = new Client();
        try {
            $crawler = $client->request('GET', 'http://www.google.com');
        } catch (\Exception $e) {
            $this->outputError('Connection error : '.$e->getMessage(), true);
            exit(1);
        }
        $input = $crawler->filterXPath("//input[@name='q']");
        if ($input->count() != 1) {
            $this->outputMessage('Looks like this is not Google', 'comment');
            return 1;
        } else {
            $this->outputMessage('Connection is alive !');
        }
    }
}
