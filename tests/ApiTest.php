<?php
require __DIR__ . '/../library/Client/ClientInterface.php';
require __DIR__ . '/../library/Client/Zend1.php';
require __DIR__ . '/../library/Client/Curl.php';
require __DIR__ . '/../library/Api.php';

class ApiTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        // defaults
        $client = new \SmsOnline\Client\Curl();
        $opts = array('api' => array('user' => 'phpunit'), 'client' => $client);
        $class = new \SmsOnline\Api($opts);

        $currOptions = $class->getOptions();
        $this->assertEquals($currOptions['api']['user'], 'phpunit');
        $this->assertEquals($class->getClient(), $client);
    }
}
