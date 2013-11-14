<?php

namespace SmsOnline\Client;

final class Response
{
    private $error = null;
    private $body = null;
    private $xml = null;

    public function __construct($data)
    {
        if ($data instanceof Exception) {
            $this->error = $data;
            return;
        }

        $this->body = $data;
        $this->decodeResponse();
    }

    public function isSuccessful()
    {
        return !count($this->error);
    }

    public function toArray()
    {
        return (array)$this->xml;
    }

    public function __toString()
    {
        return $this->body;
    }

    private function decodeResponse()
    {
        $this->xml = simplexml_load_string($this->body);

        if (!isset($this->xml->code) || !isset($this->xml->tech_message)) {
            $this->error = new \UnexpectedValueException('Response code/tech_message is not found');
            return $this;
        }

        $code = (string)$this->xml->code;
        if ($code < 0) {
            $this->error = (string)$this->xml->tech_message;
            return $this;
        }
    }

    private function getStatusByCode($code)
    {
        $codes = array(
            -1 => 'not delivered',
            -2 => 'expired',
            -3 => 'rejected',
            -4 => 'temporary tech error',
            -5 => 'sms-limit reached',
            0  => 'delivered',
            1  => 'buffered',
            2  => 'absent',
            3  => 'preparing',
            4  => 'unknown'
        );
        return array_key_exists($code, $codes) ? $codes[$code] : 'see getRawResponse()';
    }
}
