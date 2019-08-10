# Expanded Dates

[![Latest Version on Packagist](https://img.shields.io/packagist/v/permafrost-dev/expanded-packages.svg?style=flat-square)](https://packagist.org/packages/permafrost-dev/expanded-packages)
[![Build Status](https://travis-ci.org/permafrost-dev/expanded-dates.svg?branch=master)](https://travis-ci.org/permafrost-dev/expanded-packages)
[![Quality Score](https://img.shields.io/scrutinizer/g/permafrost-dev/expanded-packages.svg?style=flat-square)](https://scrutinizer-ci.com/g/permafrost-dev/expanded-packages)
[![Total Downloads](https://img.shields.io/packagist/dt/permafrost-dev/expanded-packages.svg?style=flat-square)](https://packagist.org/packages/permafrost-dev/expanded-packages)


This package takes an input string of a date (or a Carbon instance,) and expands it into an object containing several common formats, such as relative time and age in seconds, minutes, days, etc.  See below for an example.

## Installation

You can install the package via composer:

```bash
composer require permafrost-dev/expanded-dates
```

## Usage

``` php
    include __DIR__.'/../vendor/autoload.php';
    $dateOne = ExpandedDate::create(Carbon::now()->subSeconds(35));
    print_r($ed1);
```

Expands to an ExpandedDate object:

```
Permafrost\ExpandedDates\ExpandedDate Object
(
    [value] => 2019-08-09T19:43:10-04:00
    [relative] => 35 seconds ago
    [formatted] => Aug 9, 2019
    [time] => 7:43 PM
    [timestamp] => 1565394190
    [day] => Friday
    [age] => stdClass Object
        (
            [seconds] => 35
            [minutes] => 0
            [hours] => 0
            [days] => 0
            [weeks] => 0
        )

)

```

### Testing

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.