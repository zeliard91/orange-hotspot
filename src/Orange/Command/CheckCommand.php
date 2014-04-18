<?php
namespace Orange\Command;

use Orange\Command\FormattedCommand as Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class CheckCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('orange:check')
            ->setDescription('Check connection and submit credentials if needed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        // Check connection status
        $command = $this->getApplication()->find('orange:status');

        $input = new ArrayInput(array(
            'command' => 'orange:status'
            ));
        $returnCode = $command->run($input, $output);
        if ($returnCode != 0) {
            $this->loginToOrange();
        }
    }

    /**
     * Call orange portal and submit credentials
     * @return [type]      [description]
     */
    protected function loginToOrange()
    {
        $config = $this->getHelperSet()->get('config');

        $this->outputMessage('Login to orange wifi ...');
        // Forge form submit as there is no button or input
        $parameters = array(
            'username'          => $config['login'],
            'password'          => $config['pass'],
            'isCgu'             => 'true',
            'code'              => 0,
            'lang'              => 'fr',
            'auth'              => 1,
            'restrictedCode'    => '',
            'restrictedProfile' => 0,
            'restrictedRealm'   => '',
            'originForm'        => 'true',
            'tab'               => '1',
            );
        
        try {
            $client = new Client();
            $crawler = $client->request('POST', 'https://hautdebitmobile.orange.fr:8443/home/wassup', $parameters);
        } catch (\Exception $e) {
            $this->outputError('Connection error : '.$e->getMessage(), true);
            exit(1);
        }
        
        // If login is a success, we should have follow the redirect to orange home page
        if ($client->getRequest()->getUri() == 'http://www.orange.fr') {
            $this->outputMessage('Login success !');
        } else {
            $this->outputError('Login failed');
            return 1;
        }
    }
}
