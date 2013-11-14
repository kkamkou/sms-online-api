<?php

namespace SmsOnline\Client;

class Curl implements ClientInterface
{
    protected $instance;

    public function __construct(array $opts = array())
    {
        // no curl extension here
        if (!extension_loaded('curl')) {
            throw new \UnderflowException('The "CURL" extension is not loaded');
        }

        // creating a new instance
        $this->instance = \curl_init();

        // we have options to apply
        foreach ($opts as $name => $value) {
            \curl_setopt($this->instance, constant('CURLOPT_' . strtoupper($name)), $value);
        }
    }

    public function __destruct()
    {
        \curl_close($this->instance);
    }

    public function setUrl($url)
    {
        \curl_setopt($this->instance, CURLOPT_URL, $url);
        return $this;
    }

    public function resetParameters(array $params)
    {
        \curl_setopt($this->instance, CURLOPT_POST, 1);
        \curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($this->instance, CURLOPT_USERAGENT, 'sms-online-api (github.com)');
        \curl_setopt($this->instance, CURLOPT_POSTFIELDS, $params);
        return $this;
    }

    public function getResponse()
    {
        $result = \curl_exec($this->instance);
        if ($result === false) {
            $result = new \UnexpectedValueException(
                'Request faild: ' . \curl_error($this->instance)
            );
        }

        return new Response($result);
    }
}
