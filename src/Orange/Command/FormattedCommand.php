<?php
namespace Orange\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FormattedCommand extends Command
{

    private $output;
    private $formatter;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output     = $output;
        $this->formatter  = $this->getHelperSet()->get('formatter');
        $this->is_verbose = ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE);
    }

    /**
     * Display banner
     * @param  string|array  $text          [description]
     * @param  boolean $force_display [description]
     * @return [type]                 [description]
     */
    public function banner($text, $force_display = false)
    {
        if ($this->is_verbose || $force_display == true)
        {
            $formattedBlock = $this->formatter->formatBlock($text, 'bg=blue');
            $this->output->writeln($formattedBlock);
        }
    }

    /**
     * Output message in a section
     * @param  [type] $section_name [description]
     * @param  [type] $message      [description]
     * @return [type]               [description]
     */
    protected function outputSection($section_name, $message)
    {
        $formattedLine = $this->formatter->formatSection(
            $section_name,
            $message
        );
        if ($this->is_verbose)
        {
            $this->output->writeln($formattedLine);
        }
        $this->syslog($message, 'info');
    }

    /**
     * Affiche un block dans la commande
     * @param  string  $message       [description]
     * @param  [string]  $type          [description]
     * @param  [boolean] $force_display [description]
     * @return [type]                 [description]
     */
    protected function outputMessage($message, $type = 'info', $force_display = false )
    {
        if ($this->is_verbose || $force_display == true)
        {
            $messages = array($message);
            $formattedBlock = $this->formatter->formatBlock($messages, $type);
            $this->output->writeln($formattedBlock);
        }
        $this->syslog($message, $type);
    }

    /**
     * Output error block message
     * @param  string $message [description]
     * @param  boolean $force_display      [description]
     * @return [type]          [description]
     */
    protected function outputError($message, $force_display = false)
    {
        $this->outputMessage($message, 'error', $force_display);
    }

    /**
     * Send message to syslog
     * @param  string $message [description]
     * @param  string $type    [description]
     * @return [type]          [description]
     */
    protected function syslog($message, $type)
    {
        switch($type) {
            case 'comment':
                $priority = LOG_NOTICE;
                break;
            case 'error':
                $priority = LOG_ERR;
                break;
            case 'info':
            default:
                $priority = LOG_INFO;
                break;
        }
        syslog(LOG_INFO, $message);
    }
}
