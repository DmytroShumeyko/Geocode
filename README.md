# Google Geocoding API for Laravel

A simple [Laravel](http://laravel.com/) service provider for Google Geocoding API.

## Installation

This package can be installed via [Composer](http://getcomposer.org).

Run composer require command.

```sh
composer require shumex/geocode
```

### Laravel 5.5

Both the service provider and alias will be automatically installed by Laravel 5.5 package discovery.

## Configuration

Add the following line to the .env file:

```sh
GEOCODE_GOOGLE_APIKEY=<your_google_api_key>
```

You can optionally set the response language.

```sh
GEOCODE_GOOGLE_LANGUAGE=en # pt-BR, es, de, it, fr, en-GB

```

[Supported Languages](https://developers.google.com/maps/faq?hl=en#languagesupport) for Google Maps Geocoding API.


## Usage
You can find data from addresses:
```php
try {
    $response = Geocode::make()->address('Zaporizhzhia', 'en');
    if ($response) {
        echo $response->latitude();
        echo "<br>";
        echo $response->longitude();
        echo "<br>";
        echo $response->formattedAddress();
        echo "<br>";
    }
} catch (GeoException $exception) {
    echo $exception->getMessage();
}

// Output
// 35.139567
// 47.8388
// Zaporizhzhia, Zaporiz'ka oblast, Ukraine, 69061
```

Or from latitude/longitude:

```php
try {
    $response = Geocode::make()->latLng(47.850437, 35.135653, 'en');
    if ($response) {
        echo $response->latitude();
        echo "<br>";
        echo $response->longitude();
        echo "<br>";
        echo $response->formattedAddress();
        echo "<br>";
    }
} catch (GeoException $exception) {
    echo $exception->getMessage();
}

// Output
// 47.850437
// 35.135653
// Volhohrads'ka St, 27, Zaporizhzhia, Zaporiz'ka oblast, Ukraine, 69000

```

If you need other data rather than formatted address, latitude, longitude, you can use the `raw()` method:
```php
try {
    $response = Geocode::make()->latLng(40.7637931,-73.9722014);
    if ($response) {
        echo $response->raw()->address_components[8]['types'][0];
        echo $response->raw()->address_components[8]['long_name'];
    }
} catch (GeoException $exception) {
    echo $exception->getMessage();
}

// Output
// postal_code
// 10153
```

