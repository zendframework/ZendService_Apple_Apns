ZendService\Apple\Apns [![Build Status](https://travis-ci.org/zendframework/ZendService_Apple_Apns.png?branch=master)](https://travis-ci.org/zendframework/ZendService_Apple_Apns)
================================

Provides support for Apple push notifications.


## Requirements ##

* PHP >= 5.3.3

## Getting Started ##

The easiest way to work with this package is when it's installed as a
Composer package inside your project. Composer isn't strictly
required, but makes life a lot easier.

If you're not familiar with Composer, please see <http://getcomposer.org/>.

1. Install composer

        curl -s https://getcomposer.org/installer | php

2. Add the package to your application's composer.json.

        {
            ...
            "require": {
                "zendframework/zendservice-apple-apns": "1.*"
            },
            ...
        }

3. Run `php composer install`.

4. If you haven't already, add the Composer autoload to your project's
   initialization file.

        require 'vendor/autoload.php';

