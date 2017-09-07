# laravel-arrowdb

[![Build Status](https://travis-ci.org/Claymm/laravel-arrowdb.svg?branch=master)](https://travis-ci.org/Claymm/laravel-arrowdb)

A package to interface with [ArrowDB](http://docs.appcelerator.com/arrowdb/latest) - Appcelerator Cloud Services (ACS)

## Installation

To install the following to your composer.json

    "claymm/laravel-arrowdb" : "~1.0"

Add the service provider and alias to app/config/app.php:

    'Claymm\ArrowDB\ArrowDBServiceProvider',
    
    'ArrowDB' => 'Claymm\ArrowDB\Facades\ArrowDB',

To publish the configuration file you'll have to run:

   `$ php artisan vendor:publish --provider="Claymm\ArrowDB\ArrowDBServiceProvider"`


## Basic Usage

In your contoller or route:

    $result = ArrowDB::get('user/search.json');

    //do something with the returned object

### Using Authenticated API's or as an Authentication provider for ACS Users

To use API's that require user authentication you will need to use a third party authentication provider. I've chosen [Sentry](https://cartalyst.com/manual/sentry), if you'd like to use this and use a different Auth manager create a issue.

## Contributions

This is a fork from [h3r2on/laravel-acs](https://github.com/h3r2on/laravel-acs) which is getting pretty old now and outdated so thanks to h3r2on.
