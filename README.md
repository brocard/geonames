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

Install migrations
```
php artisan geo:import-countries
```

Get countries from api.geonames.org
```
php artisan geo:import-countries
```

Get states from api.geonames.org
```
php artisan geo:import-states
```