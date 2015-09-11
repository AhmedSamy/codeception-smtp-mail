<?php

namespace Codeception\Lib\Driver;

use PhpImap\Mailbox;

/**
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */
class SMTPDriver
{
    private $mailbox;

    public function __construct($config)
    {
        $this->mailbox = new Mailbox(
            $config['imap_path'],
            $config['username'],
            $config['password'],
            realpath($config['attachments_dir'])
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

    /**
     * @param string $criteria
     * @param int    $numberOfRetries
     * @param int    $waitInterval
     *
     * @return null|\PhpImap\IncomingMail
     * @throws \Exception
     */
    protected function retry($criteria, $numberOfRetries, $waitInterval)
    {
        while ($numberOfRetries > 0) {
            $mailIds = $this->search($criteria);
            codecept_debug("Failed to find the email, Retrying ... ({$numberOfRetries}) tries left");
            if (empty($mailsIds)) {
                return $mailIds;
            }
            $numberOfRetries--;
            sleep($waitInterval);
        }

        return [];
    }
}
