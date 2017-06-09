<?php
namespace Codeception\Module;

use Codeception\Exception\ModuleException;
use Codeception\Lib\Driver\SMTPDriver;
use Codeception\Module;
use PhpImap\IncomingMail;

/**
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */
class Smtp extends Module
{
    /** @var array */
    protected $requiredFields = ['username', 'password'];

    /** @var array */
    protected $config = [
        'username',
        'password',
        'imap_path' => '{imap.gmail.com:993/imap/ssl}INBOX',
        'wait_interval' => 1, //in seconds
        'retry_counts' => 3,
        'attachments_dir' => 'tests/_data',
        'auto_clear_attachments' => true,
        'charset' => 'UTF-8',
    ];

    /** @var  SMTPDriver */
    protected $driver;

    /** @var  IncomingMail */
    protected $mail;

    /**
     * {@inheritdoc}
     */
    public function _initialize()
    {
        if (!function_exists('imap_search')) {
            throw new \Exception(
                "imap is not installed, check http://php.net/manual/en/imap.setup.php for more information"
            );
        }
        //pre-pending folder name to the path
        $this->config['attachments_dir'] = $this->config['attachments_dir'].'/mail_attachments';
        //clearing attachments
        if ($this->config['auto_clear_attachments']) {
            $this->clearAttachments($this->config['attachments_dir']);
        }

        $this->driver = new SMTPDriver($this->config);
        $this->mail = null;
    }


    /**
     * @param string $criteria
     */
    public function seeEmail($criteria)
    {
        $this->assertTrue($this->driver->seeEmailBy($criteria));
    }

    /**
     * @param string $criteria
     */
    public function dontSeeEmail($criteria)
    {
        $this->assertFalse($this->driver->seeEmailBy($criteria));
    }

    /**
     * @param string $link
     *
     * @throws ModuleException
     */
    public function seeLinkInEmail($link)
    {
        $this->assertTrue($this->contains($link, $this->driver->getLinksByEmail($this->getCurrentMail())));
    }

    /**
     * @param string $link
     *
     * @throws ModuleException
     */
    public function dontSeeLinkInEmail($link)
    {
        $this->assertFalse($this->contains($link, $this->driver->getLinksByEmail($this->getCurrentMail())));
    }

    /**
     * @param string $url
     *
     * @throws ModuleException
     */
    public function clickInEmail($url)
    {
        $urlFound = $this->searchForLink($url, $this->driver->getLinksByEmail($this->getCurrentMail()));
        if (null == $urlFound) {
            throw new ModuleException($this, sprintf("can't find %s in the current email", $url));
        }
        if ($this->hasModule('WebDriver')) {
            $this->getModule('WebDriver')->amOnUrl($urlFound);
        } elseif ($this->hasModule('PhpBrowser')) {
            $this->getModule('PhpBrowser')->amOnUrl($urlFound);
        } else {
            throw new ModuleException(
                $this,
                "In order to click on links, you need to enable either `WebDriver` or `PhpBrowser` module"
            );
        }
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws ModuleException
     */
    public function grabLinkFromEmail($url)
    {
        $urlFound = $this->searchForLink($url, $this->driver->getLinksByEmail($this->getCurrentMail()));
        if (null == $urlFound) {
            throw new ModuleException($this, sprintf("can't find %s in the current email", $url));
        }

        return $urlFound;
    }

    /**
     * @param string $criteria
     *
     * @throws \Exception
     */
    public function openEmail($criteria, $first = true)
    {
        $this->mail = $this->driver->getEmailBy($criteria, $first);
    }

    /**
     * @param $criteria
     *
     * @return int
     * @throws \Exception
     */
    public function countEmailsByCriteria($criteria)
    {
        $mails = $this->driver->getEmailsBy($criteria);

        return count($mails);
    }

    /**
     * @param int $count
     *
     * @throws ModuleException
     */
    public function canSeeEmailAttachmentsCount($count)
    {
        $this->assertEquals($count, count($this->getCurrentMail()->getAttachments()));
    }

    /**
     * @param string $name
     *
     * @throws ModuleException
     */
    public function canSeeEmailAttachment($name)
    {
        $names = [];

        foreach ($this->getCurrentMail()->getAttachments() as $attachment) {
            $names[] = $attachment->name;
        }

        $this->assertTrue($this->contains($name, $names));
    }

    /**
     * @return IncomingMail
     * @throws ModuleException
     */
    public function grabEmail()
    {
        return $this->getCurrentMail();
    }

    /**
     * @param string $str
     * @param array  $arr
     *
     * @return bool
     */
    private function contains($str, array $arr)
    {
        foreach ($arr as $a) {
            if (stripos($a, $str) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $str
     * @param array  $arr
     *
     * @return null
     */
    private function searchForLink($str, array $arr)
    {
        foreach ($arr as $a) {
            if (stripos($a, $str) !== false) {
                return $a;
            }
        }

        return null;
    }

    /**
     * @return IncomingMail
     * @throws ModuleException
     */
    private function getCurrentMail()
    {
        if (null == $this->mail) {
            throw new ModuleException(
                $this,
                "There's no open email, may be you forgot to ,`\$I->openEmail` to open it"
            );
        }

        return $this->mail;
    }

    /**
     * Clear all previous email attachments
     */
    private function clearAttachments($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $files = glob($dir.'/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
