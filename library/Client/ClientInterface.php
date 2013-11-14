<?php

namespace SmsOnline\Client;

interface ClientInterface
{
    public function setUrl($url);
    public function resetParameters(array $params);
    public function getResponse();
}
