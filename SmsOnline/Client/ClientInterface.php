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

interface ClientInterface
{
    public function setUrl($url);
    public function resetParameters(array $params);
    public function getResponse();
}
