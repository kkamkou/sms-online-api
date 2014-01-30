<?php
/**
 * sms-online-api - API for the smsonline.ru messaging service
 *
 * @package  SmsOnline
 * @author   Kanstantsin A Kamkou (2ka.by)
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://github.com/kkamkou/sms-online-api
 */

namespace SmsOnline;

class Api
{
    /** @var Client\ClientInterface */
    private $client = null;

    /** @var array */
    protected $options = array(
        'api' => array(
            'user' => null,
            'secret_key' => null,
            'url' => 'https://bulk.sms-online.com/',
            'client' => 'curl'
        ),
        'msg' => array(
            'hex' => 0,
            'dlr' => 0,
            'delay' => 0,
            'charset' => 'UTF-8',
            'from' => 'SmsOnline'
        ),
        'client' => array(
            'connecttimeout' => 10,
            'timeout' => 10
        )
    );

    /**
     * Constructor
     * @param array $opts
     */
    public function __construct(array $opts)
    {
        // merging options
        $this->options = array_replace_recursive($this->options, $opts);

        // getting a client object
        $this->client = $this->getClient();
    }

    /**
     * Returns a set of options for the current object
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Returns a client instance
     *
     * @return Client\ClientInterface
     */
    public function getClient()
    {
        if ($this->options['client'] instanceof Client\ClientInterface) {
            $client = $this->options['client'];
        } else {
            // the client object itself (php < 5.4)
            $className = __NAMESPACE__ . '\\Client\\' .
                ucfirst(strtolower($this->options['api']['client']));
            $client = new $className($this->options['client']);
        }
        return $client;
    }

    /**
     * Sends message to a mobile device
     *
     * @param string $phone A phone number (765432123456)
     * @param string $txt A message to send
     * @param array $opts (Default: array())
     * @return Client\Response
     */
    public function send($phone, $txt, array $opts = array())
    {
        // the phone number cleanup
        $phone = preg_replace('~[^\d]~', '', $phone);

        // workaround for the "\n" char
        $txt = str_replace(array("\r\n", "\r", "\n"), ' ', $txt);

        // defaults
        $opts = array_replace($this->options['msg'], $opts);
        $sign = $this->getSign($phone, $txt, $opts['from']);
        $opts = array_replace(
            $opts,
            array('phone' => $phone, 'txt' => $txt, 'sign' => $sign),
            array('user' => $this->options['api']['user'])
        );

        // setting up the client
        return new Client\Response(
            $this->client->resetParameters($opts)
              ->setUrl($this->options['api']['url'])
              ->getResponse()
        );
    }

    /**
     * Generates auth signature
     *
     * @param string $phone
     * @param string $txt
     * @param string $from
     * @return string
     */
    protected function getSign($phone, $txt, $from)
    {
        return md5(
            $this->options['api']['user'] . $from . $phone . $txt .
            $this->options['api']['secret_key']
        );
    }
}
