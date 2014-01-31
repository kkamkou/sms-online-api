sms-online-api
==============
API for the [smsonline.ru](http://smsonline.ru/) messaging service


### Composer
Add ```"kkamkou/sms-online-api": "dev-master"``` to a ```composer.json``` file, to the ```require``` section
and execute ```composer update```

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

#### HowTo
##### SSL certificate problem
```php
$sms = new \SmsOnline\Api(
    array(
        'client' => array(
            'cainfo' => 'pathToCert'
            // or you can disable ssl verification (bad idea)
            //'ssl_verifyhost' => 0,
            //'ssl_verifypeer' => 0
        )
    )
);
```

### Tests
```sh
phpcs --standard=psr2 SmsOnline
phpunit tests
```

## License
The MIT License (MIT)

Copyright (c) 2013 Kanstantsin Kamkou

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
