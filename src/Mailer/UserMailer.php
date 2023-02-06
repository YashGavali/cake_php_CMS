<?php

declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Queue\Mailer\QueueTrait;

/**
 * User mailer.
 */
class UserMailer extends Mailer
{
    use QueueTrait;
    /**
     * Mailer's name.
     *
     * @var string
     */
    public function welcome(string $emailAddress, string $username): void
    {
        $this
            ->setTo($emailAddress)
            ->setSubject(sprintf('Welcome %s', $username));
    }
}
