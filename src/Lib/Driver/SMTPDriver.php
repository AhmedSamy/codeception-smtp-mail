<?php

namespace Codeception\Lib\Driver;

use PhpImap\IncomingMail;
use PhpImap\Mailbox;

/**
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */
class SMTPDriver
{
    /** @var Mailbox */
    private $mailbox;

    /** @var  int */
    private $numberOfRetries;

    /** @var  int */
    private $waitIntervalInSeconds;

    public function __construct($config)
    {
        $this->mailbox = new Mailbox(
            $config['imap_path'],
            $config['username'],
            $config['password'],
            realpath($config['attachments_dir'])
        );

        $this->numberOfRetries = $config['retry_counts'];
        $this->waitIntervalInSeconds = $config['wait_interval'];
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

    public function getLinksByEmail(IncomingMail $mail)
    {
        $matches = [];

        preg_match_all('|href="([^\s"]+)|', $mail->textHtml, $matches);

        return $matches[1];
    }

    protected function search($criteria)
    {
        return $this->retry(
            $criteria,
            $this->numberOfRetries,
            $this->waitIntervalInSeconds
        );
    }

    /**
     * @param string $criteria
     * @param int    $numberOfRetries
     * @param int    $waitInterval
     *
     * @return array
     **/
    protected function retry($criteria, $numberOfRetries, $waitInterval)
    {
        $mailIds = [];
        while ($numberOfRetries > 0) {
            sleep($waitInterval);
            $mailIds = $this->mailbox->searchMailBox($criteria);

            if (!empty($mailIds)) {
                break;
            }
            $numberOfRetries--;
            codecept_debug("Failed to find the email, retrying ... ({$numberOfRetries}) trie(s) left");
        }

        return $mailIds;
    }
}
