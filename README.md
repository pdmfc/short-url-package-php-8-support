## Installation
### Composer:
In your `composer.json` add the following to the `"repositories"` section:
```json
{
    "type": "vcs",
    "url": "https://gitlab.pdmfc.com/pdmfc/short-url-package.git"
}
```

Require the package:
```shell
composer require pdmfc/shorturl
```

### Configuration
- Publish the config file:
```shell
php artisan vendor:publish --tag="short_config"
```

- Add the following environment variables to your `.env` file with the necessary values defined for your current application:
```dotenv
SHORT_URL_API_ID=
SHORT_URL_API_TOKEN=  
SHORT_URL_API_BASE_URL=
```

## Api usage
If authentication is not correct, the response will be the following:
```json
{
    "message": "Access is not allowed"
}
```

### `getUrl(string $shortUrl)`
```php
use Pdmfc\Shorturl\Client\ShortUrlClient;

$client = new ShortUrlClient();

$response = $client->getUrl('Nc');

// If the code doesn't exist, the response will be the following:
/*
{
    "message": "shortUrls Nc not found"
}
*/

// If the code exists, the response will be the following:
/*
{
    "Id": 2,
    "shortUrl": "http://teste.ll/Nc",
    "qr_code": "<?xml version=\"1.0\" encodin..."
}
*/
```

### `createUrl(array $params)`
```php
use Pdmfc\Shorturl\Client\ShortUrlClient;

$client = new ShortUrlClient();

$params = [
    'domainUrl' => 's.pdm.pt', // Short url domain. (optional)
    'originalUrl' => 'www.original-url.com/long', // Url where you will be redirected. (required)
    'liveTime' => 0, // expiration time. (optional) [default: 0]
    'active' => true, // Define if the link is active. (optional) [default: true]
    'shortUrl' => 'some_custom_string', // Custom url. If none provided, it will automatically generated. (optional)
];

$response = $client->createUrl($params);

// The result of short url creation will be the following:
/*
{
    "Id": 26,
    "shortUrl": "http://teste.ll/1C",
    "qrCode": "<?xml version=\"1.0\" encoding..."
}
*/

// If something is wrong with methods, the response will be the following:
/*
{
    "message": "Is not possible create a short url"
}
*/
```

### `changeUrl(string $shortUrl, array $params)`
```php
use Pdmfc\Shorturl\Client\ShortUrlClient;

$client = new ShortUrlClient();

$params = [];

$response = $client->updateUrl('Nc', $params);

// The parameters are the same of the createUrl but none is mandatory

// The result of short url creation will be the following:
/*
{
    "Id": 26,
    "shortUrl": "http://teste.ll/1C",
    "qrCode": "<?xml version=\"1.0\" encoding..."
}
*/

// If something is wrong with methods, the response will be the following:
/*
{
    "message": "Is not possible update the short url"
}
*/
```

### `deleteUrl(string $shortUrl)`
```php
use Pdmfc\Shorturl\Client\ShortUrlClient;

$client = new ShortUrlClient();

$response = $client->deleteUrl('Nc');

// If the code doesn't exist, the response will be the following:
/*
{
    "message": "shortUrls Nc not found"
}
*/

// If the code exists, the response will be the following:
/*
{
    "message": "Code Nc was deleted with success"
}
*/

// If something is wrong with methods, the response will be the following:
/*
{
    "message": "Is not possible delete a short url"
}
*/
```
