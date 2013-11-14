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

/**
 * Class Curl
 *
 * @see ClientInterface
 */
class Curl implements ClientInterface
{
    /** @var resource */
    protected $instance;

    /**
     * Constructor
     *
     * @param array $opts (Default: array())
     * @throws \UnderflowException if no CURL extension found
     */
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

    /**
     * Destructor
     */
    public function __destruct()
    {
        \curl_close($this->instance);
    }

    /**
     * Sets url to the sms service
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        \curl_setopt($this->instance, CURLOPT_URL, $url);
        return $this;
    }

    /**
     * Resets curl instance
     *
     * @param array $params
     * @return $this
     */
    public function resetParameters(array $params)
    {
        \curl_setopt($this->instance, CURLOPT_POST, 1);
        \curl_setopt($this->instance, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($this->instance, CURLOPT_USERAGENT, 'sms-online-api (github.com)');
        \curl_setopt($this->instance, CURLOPT_POSTFIELDS, $params);
        return $this;
    }

    /**
     * Returns a new response object
     * @return Response
     */
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
