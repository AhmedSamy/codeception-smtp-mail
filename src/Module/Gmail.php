<?php
namespace Codeception\Module;

use Codeception\Lib\Driver\SMTPDriver;
use Codeception\Module as CodeceptionModule;

class Gmail extends CodeceptionModule
{
    protected $requiredFields = ['username', 'password'];

    protected $config = [
        'username',
        'password',
        'imap_path' => '{imap.gmail.com:993/imap/ssl}INBOX',
        'attachments_dir' => '/tests/_data',
    ];

    /** @var  SMTPDriver */
    protected $driver;

    /**
     * {@inheritdoc}
     */
    public function _initialize()
    {
        $this->driver = new SMTPDriver(
            $this->config['username'],
            $this->config['password'],
            $this->config['imap_path'],
            realpath($this->config['attachments_dir'])
        );
    }

    /**
     * @param $criteria
     */
    public function seeInEmailBy($criteria)
    {
        $this->assertTrue($this->driver->seeEmailBy($criteria));
    }

    /**
     * @param $criteria
     */
    public function dontSeeInEmailBy($criteria)
    {
        $this->assertFalse($this->driver->seeEmailBy($criteria));
    }

    /**
     * @param $criteria
     *
     * @return \PhpImap\IncomingMail
     * @throws \Exception
     */
    public function grabEmailBy($criteria)
    {
        return $this->driver->getEmailBy($criteria);
    }

    public function wait($seconds)
    {
        //@TODO implement wait method
    }
}
