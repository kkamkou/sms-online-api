<?php
require __DIR__ . '/../SmsOnline/Client/ClientInterface.php';
require __DIR__ . '/../SmsOnline/Client/Response.php';
require __DIR__ . '/../SmsOnline/Client/Curl.php';
require __DIR__ . '/../SmsOnline/Api.php';

class ApiTest extends PHPUnit_Framework_TestCase
{
    /** @var \SmsOnline\Api */
    protected $sms;
    protected $mobileNumber = '+(123) 45 678-90-12';
    protected $secretKey = 'secretKey';
    protected $user = 'user';

    public function setUp()
    {
        $this->sms = new \SmsOnline\Api(array(
            'api' => array('user' => $this->user, 'secret_key' => $this->secretKey),
            'client' => new \SmsOnline\Client\Curl()
        ));
    }

    public function testConstructor()
    {
        // defaults
        $client = new \SmsOnline\Client\Curl();
        $opts = array('api' => array('user' => 'phpunit'), 'client' => $client);
        $class = new \SmsOnline\Api($opts);

        $currOptions = $class->getOptions();
        $this->assertEquals($currOptions['api']['user'], 'phpunit');
        $this->assertEquals($class->getClient(), $client);

        $this->assertInstanceOf('\\SmsOnline\\Client\\ClientInterface', $class->getClient());
    }

    public function testSendResponse()
    {
        $result = $this->sms->send($this->mobileNumber, 'SmsOnline TestMessage');
        $this->assertInstanceOf('\\SmsOnline\\Client\\Response', $result);
    }

    public function testRealSend()
    {
        if ($this->secretKey == 'secretKey') {
            return $this->markTestSkipped('To run this test I need the real information');
        }

        $msg = <<<EOF
Test Message
Goes here
EOF;
        $result = $this->sms->send($this->mobileNumber, $msg);
        $this->assertTrue($result->isSuccessful());
    }

    public function testResponseToStringEmpty()
    {
        $result = new \SmsOnline\Client\Response(0);
        $this->assertInternalType('string', (string)$result);
    }

    /**
     * @dataProvider responseProviderOk
     */
    public function testResponseOk($fixtureName)
    {
        $fixture = $this->loadFixture($fixtureName);
        $result = new \SmsOnline\Client\Response($fixture);

        $this->assertTrue($result->isSuccessful());
        $this->assertEquals($fixture, (string)$result);
        $this->assertArrayHasKey('code', $result->toArray());
        $this->assertArrayHasKey('tech_message', $result->toArray());
    }

    /**
     * @dataProvider responseProviderFailed
     */
    public function testResponseFailed($fixtureName)
    {
        $fixture = $this->loadFixture($fixtureName);
        $result = new \SmsOnline\Client\Response($fixture);

        $this->assertFalse($result->isSuccessful());
        $this->assertEquals($fixture, (string)$result);
        $this->assertArrayHasKey('code', $result->toArray());
        $this->assertArrayHasKey('tech_message', $result->toArray());
    }

    public function responseProviderOk()
    {
        return array(array('ok'));
    }

    public function responseProviderFailed()
    {
        return array(array('no-credits', 'auth-error', 'syntax'));
    }

    protected function loadFixture($name)
    {
        return file_get_contents(__DIR__ . "/fixtures/{$name}.xml");
    }
}
