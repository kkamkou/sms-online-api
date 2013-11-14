<?php

namespace SmsOnline;

class Api
{
    private $client = null;

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

    public function __construct(array $opts)
    {
        // merging options
        $this->options = array_replace_recursive($this->options, $opts);

        // getting a client object
        $this->client = $this->getClient();
    }

    public function getOptions()
    {
        return $this->options;
    }

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

    public function send($phone, $txt, array $opts = array())
    {
        // defaults
        $opts = array_replace($this->options['msg'], $opts);
        $sign = $this->getSign($phone, $txt, $opts['from']);
        $opts = array_replace(
            $opts,
            array('phone' => $phone, 'txt' => $txt, 'sign' => $sign),
            array('user' => $this->options['api']['user'])
        );

        // setting up the client
        return $this->client->resetParameters($opts)
            ->setUrl($this->options['api']['url'])
            ->getResponse();
    }

    protected function getSign($phone, $txt, $from)
    {
        return md5(
            $this->options['api']['user'] . $from . $phone . $txt .
            $this->options['api']['secret_key']
        );
    }
}
