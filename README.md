sms-online-api
==============


#### Example
```php
$sms = new \SmsOnline\Api(array('api' => ...));
$result = $sms->send('76543210987', 'TestMessage');
var_dump($result);
```

#### Your own client
```php
$client = new MyClient(array('timeout' => 40));
$sms = new \SmsOnline\Api(array('client' => $client));
```

### Tests
```sh
phpcs --standard=psr2 library
phpunit tests
```
