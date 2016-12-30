# geonames library


Add the Geoname service provider to the config/app.php file in the providers array:

```
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

For first time
```php
php artisan vendor:publish --provider="BrocardJr\Geo\GeoServiceProvider"
```

Reload de config for some changes
```php
php artisan vendor:publish --provider="BrocardJr\Geo\GeoServiceProvider" --tag=config --force
```
