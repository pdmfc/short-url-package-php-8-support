## Installation
Add this on composer repositories section:
```json
{
    "type": "vcs",
    "url": "https://gitlab.pdmfc.com/pdmfc/short-url-package.git"
}
```


Require this package with composer:

```shell
composer require pdmfc/shorturl:*
```

# Configuration

```shell
php artisan vendor:publish --tag="short_config"
```

# .env

API_ID=  
API_TOKEN=  
API_BASE_URL=
