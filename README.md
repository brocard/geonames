# geonames library

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/brocard/geonames/master/LICENSE.md)

[![Total Downloads](https://img.shields.io/packagist/dt/brocard/geolocation.svg?style=flat-square)](https://packagist.org/packages/brocard/geolocation)

Add the Geoname service provider to the <code>config/app.php</code> file in the <code>providers</code> array:

```php
'providers' => [
    // Laravel Framework Service Providers...
    //...

    // Package Service Providers
    BrocardJr\Geo\GeoServiceProvider::class,
    // ...

    // Application Service Providers
    // ...
],
```

For first instance you only need set enviroment variable intro <code> .env </code> file in root directoy
```
example:  GEONAMES_USERNAME=your_username
```

You may easily access your configuration values using the global config helper function only run the next artisan command
```
php artisan vendor:publish --provider="BrocardJr\Geo\GeoServiceProvider"
```

Reload de config for some changes
```
php artisan vendor:publish --provider="BrocardJr\Geo\GeoServiceProvider" --tag=config --force
```

Install migrations
```
php artisan geo:install
```

Get countries from api.geonames.org
```
php artisan geo:import-countries
```

Get states from api.geonames.org
```
php artisan geo:import-states
```
