<?php

namespace Codeception\Lib\Driver;

use PhpImap\Mailbox;

/**
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */
class SMTPDriver
{
    private $mailbox;

    public function __construct($username, $password, $imapPath, $attachmentsDir)
    {
        $this->mailbox = new Mailbox(
            $imapPath,
            $username,
            $password,
            $attachmentsDir
        );
    }

    public function getEmailBy($criteria)
    {
        $mailsIds = $this->search($criteria);
        if (!$mailsIds) {
            throw new \Exception(sprintf("No email found with given criteria %s", $criteria));
        }

        $mailId = reset($mailsIds);
        $mail = $this->mailbox->getMail($mailId);

        return $mail;
    }

    public function seeEmailBy($criteria)
    {
        $mailsIds = $this->search($criteria);

        return !empty($mailsIds);
    }

    protected function search($criteria)
    {
        return $this->mailbox->searchMailBox($criteria);
    }
}
