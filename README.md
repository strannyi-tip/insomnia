# Small cURL based HTTP library
[![codeception](https://github.com/strannyi-tip/insomnia/actions/workflows/php.yml/badge.svg?event=pull_request)](https://github.com/strannyi-tip/insomnia/actions/workflows/php.yml)

## Examples

### Simple GET

```php
$insomnia = new Insomnia();

$result = $insomnia
            //Setup the address and port for connection
            ->connect('http://127.0.0.1?name=Nautilus', 8000)
            //Send GET with parameters
            ->get();

if ($result) {
  //Get result as array
  echo $insomnia->asArray();
  //Get result as Insomnia::Result object
  echo $insomnia->asObject()->get('response');
  //Get result as raw string
  echo $insomnia->asString();
}
```

### Simple POST
```php
$insomnia = new Insomnia();

//Result is a bool request status
$result = $insomnia
          //Setup the address and port for connection
          ->connect('http://127.0.0.1', 8000)
          //Send POST with parameters
          ->post(['name' => 'Nautilus']);
```

### Set headers

```php
$insomnia = new Insomnia();

//Result is a bool request status
$result = $insomnia
          //Setup the address and port for connection
          ->connect('http://127.0.0.1', 8000)
          //Add header what you need
          ->addHeader('Content-Type: application/json')
          ->post(['name' => 'SOAD']);
```

### Set cookies

```php
$insomnia = new Insomnia();

//Result is a bool request status
$result = $insomnia
          //Setup the address and port for connection
          ->connect('http://127.0.0.1', 8000)
          //Add cookie what you need
          ->addCookie('token', md5('strong'))
          ->post(['name' => 'Nickelback']);
```

### Use proxy

```php
$insomnia = new Insomnia();
//Create Proxy object
$proxy = new Proxy();

$proxy
    //Setup address
    ->setAddress('127.0.0.1')
    //Setup port
    ->setPort(33333)
    //Setup type HTTP|SOCKS4|SOCKS5. HTTP is default
    ->setType(Proxy::SOCKS5);

//Result is a bool request status
$result = $insomnia
          //Setup the address and port for connection
          ->connect('http://127.0.0.1', 8000)
          //Setup the proxy
          ->setProxy($proxy)
          //Send POST
          ->post(['name' => 'Three Days Grace']);
```


