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
            realpath($config['attachments_dir']),
            $config['charset']
        );

        $this->numberOfRetries = $config['retry_counts'];
        $this->waitIntervalInSeconds = $config['wait_interval'];
    }

    /**
     * @param $criteria
     *
     * @return IncomingMail
     * @throws \Exception
     */
    public function getEmailBy($criteria, $first)
    {
        $mailIds = $this->search($criteria);
        if (!$mailIds) {
            throw new \Exception(sprintf("No email found with given criteria %s", $criteria));
        }

        if ($first){
            $mailId = reset($mailIds);
        }else{
            $mailId = end($mailIds);
        }

        $mail = $this->mailbox->getMail($mailId);

        return $mail;
    }

    /**
     * @param $criteria
     *
     * @return array
     * @throws \Exception
     */
    /**
     * @param $criteria
     *
     * @return array
     * @throws \Exception
     */
    public function getEmailsBy($criteria, $count)
    {
        $mails = [];
        $mailIds = $this->search($criteria);

        if (!$count) {
            if (!$mailIds) {
                throw new \Exception(sprintf("No email found with given criteria %s", $criteria));
            }
        }

        foreach ($mailIds as $mailId) {
            $mails[] = $this->mailbox->getMail($mailId);

        }

        return $mails;
    }

    /**
     * @param $criteria
     *
     * @return bool
     */
    public function seeEmailBy($criteria)
    {
        $mailsIds = $this->search($criteria);

        return !empty($mailsIds);
    }

    /**
     * @param IncomingMail $mail
     *
     * @return mixed
     */
    public function getLinksByEmail(IncomingMail $mail)
    {
        $matches = [];

        preg_match_all('|href="([^\s"]+)|', $mail->textHtml, $matches);

        return $matches[1];
    }
    
    /**
     * @param IncomingMail $mail
     *
     * @return mixed
     */
    public function getStringsByEmail(IncomingMail $mail)
    {
        $matches = [];

        $matches = preg_split('/\n|\r\n?/', $mail->textHtml);

        return $matches;
    }
    /**
     * @param $criteria
     *
     * @return array
     */
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
