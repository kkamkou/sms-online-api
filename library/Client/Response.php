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
}
