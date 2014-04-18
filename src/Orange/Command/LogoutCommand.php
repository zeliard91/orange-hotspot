<?php
namespace Orange\Command;

use Orange\Command\FormattedCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class LogoutCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('orange:logout')
            ->setDescription('Logout from orange wifi hotspot')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->outputMessage('Logout from orange wifi ...');

        $client = new Client();
        try {
            $crawler = $client->request('GET', 'https://hautdebitmobile.orange.fr:8443/home/disconnect');
        } catch (\Exception $e) {
            $this->outputError('Connection error : '.$e->getMessage(), true);
            exit(1);
        }

        $this->outputMessage('done');
    }
}
