sms-online-api
==============
API for the [smsonline.ru](http://smsonline.ru/) messaging service


### Composer
tbd...

#### Example
```php
$sms = new \SmsOnline\Api(array('api' => array('user' => '', 'secret_key' => '')));
$sms->send('76543210987', 'TestMessage');
$sms->send('76543210987', 'TestMessage', array('from' => 'MySite'));
```

#### Your own client (Default is CURL)
```php
$client = new MyClient(array('timeout' => 40));
$sms = new \SmsOnline\Api(array('client' => $client));
```

#### Response
```php
$result = $sms->send('76543210987', 'TestMessage');
if ($result->isSuccessful()) {
  print_r($result->toArray());
  echo (string)$result;
}
```

### Tests
```sh
phpcs --standard=psr2 library
phpunit tests
```
