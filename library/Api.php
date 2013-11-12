<?php

namespace SmsOnline;

class Api
{
    private $client = null;

    protected $options = array(
        'api' => array(
            'user' => null,
            'secret_key' => null,
            'from' => 'sms-online-api',
            'client' => 'curl',
            'url' => 'https://bulk.sms-online.com/'
        ),
        'msg' => array(
            'hex' => 0,
            'dlr' => 0,
            'charset' => 'UTF-8'
        ),
        'client' => array(
            'timeout' => 10,
            'method' => 'POST'
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
            $client = new $className();
        }
        $client->setUrl($this->options['api']['url']);
        return $client;
    }

    public function send($phone, $txt, array $opts = array())
    {
        // defaults
        $sign = $this->getSign($phone, $txt);
        $opts = array_merge(
            $this->options['msg'],
            array('phone' => $phone, $txt => $txt),
            $opts
        );

        // setting up the client
        $client->resetParameters($opts);
    }

    protected function getSign($phone, $txt)
    {
        return md5(
            $this->options['api']['user'] . $this->options['api']['from'] .
            $phone . $txt . $this->options['api']['secret_key']
        );
    }
}
