# geonames library


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

For first time
```
php artisan vendor:publish --provider="BrocardJr\Geo\GeoServiceProvider"
```

Reload de config for some changes
```
php artisan vendor:publish --provider="BrocardJr\Geo\GeoServiceProvider" --tag=config --force
```
