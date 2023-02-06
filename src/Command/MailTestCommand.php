<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Mailer\Mailer;
use Cake\Mailer\MailerAwareTrait;

/**
 * MailTest command.
 */
class MailTestCommand extends Command
{
    use MailerAwareTrait;

    /**
     * 
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {

        // (new Mailer())->setTo('yg20298@gmail.com')
        //     ->setSubject('Test connection between CakePHP 4 and Mailhog')
        //     ->deliver('Connection working');

        $mailer = $this->getMailer('User');

        $mailer->send('welcome', ['yg20298@gmail.com', 'test']);
    }
}
