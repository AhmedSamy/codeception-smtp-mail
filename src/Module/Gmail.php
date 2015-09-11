<?php
namespace Codeception\Module;

use Codeception\Lib\Driver\SMTPDriver;
use Codeception\Module;

/**
 * @author Ahmed Samy <ahmed.samy.cs@gmail.com>
 */
class Gmail extends Module
{
    /** @var array */
    protected $requiredFields = ['username', 'password'];

    /** @var array */
    protected $config = [
        'username',
        'password',
        'imap_path' => '{imap.gmail.com:993/imap/ssl}INBOX',
        'attachments_dir' => '/tests/_data',
        'wait_interval' => 0.25, //by ms
        'retry_counts' => 3,
    ];

    /** @var  SMTPDriver */
    protected $driver;

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
        $this->driver = new SMTPDriver($this->config);
    }

    /**
     * @param $criteria
     */
    public function seeEmailBy($criteria)
    {
        $this->assertTrue($this->driver->seeEmailBy($criteria));
    }

    /**
     * @param $criteria
     */
    public function dontSeeEmailBy($criteria)
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

}
