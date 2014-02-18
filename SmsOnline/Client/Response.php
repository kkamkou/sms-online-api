<?php
/**
 * sms-online-api - API for the smsonline.ru messaging service
 *
 * @package  SmsOnline
 * @author   Kanstantsin A Kamkou (2ka.by)
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://github.com/kkamkou/sms-online-api
 */

namespace SmsOnline\Client;

final class Response
{
    /** @var null|Exception */
    private $error = null;

    /** @var null|string */
    private $body = null;

    /** @var null|\SimpleXMLElement */
    private $xml = null;

    /**
     * Constructor
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        if ($data instanceof Exception) {
            $this->error = $data;
            return;
        }

        $this->body = $data;
        $this->decodeResponse();
    }

    /**
     * Returns true if a response contains positive status code
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return !count($this->error);
    }

    /**
     * Converts the current object to an array
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this->xml;
    }

    /**
     * Returns the raw body of a response
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->body;
    }

    /**
     * Response parsing method
     *
     * @return $this
     */
    private function decodeResponse()
    {
        // storing libxml state
        $oldState = libxml_use_internal_errors(true);

        // errors cleanup
        libxml_clear_errors();

        // response has XML format, we have to parse it
        $this->xml = simplexml_load_string($this->body);

        // restoring default state
        libxml_use_internal_errors($oldState);

        // we have xml error
        if (libxml_get_last_error()) {
            $this->error = new \Exception('XML error: ' . libxml_get_last_error()->message);
            return $this;
        }

        // we have format error
        if (!isset($this->xml->code) || !isset($this->xml->tech_message)) {
            $this->error = new \UnexpectedValueException('Response code/tech_message is not found');
            return $this;
        }

        $code = (string)$this->xml->code;
        if ($code < 0) {
            $this->error = (string)$this->xml->tech_message;
        }

        return $this;
    }
}
