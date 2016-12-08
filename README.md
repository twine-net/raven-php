Raven PHP
=============

> Sentry (Raven) error monitoring for Laravel and Lumen with send in background via queues

<!-- [![Build Status](http://img.shields.io/travis/twineis/raven-php/master.svg?style=flat-square)](https://travis-ci.org/twineis/raven-php)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/twineis/raven-php/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/twineis/raven-php/)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/twineis/raven-php/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/twineis/raven-php/code-structure/master) -->
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)
[![Latest Version](http://img.shields.io/packagist/v/twineis/raven-php.svg?style=flat-square)](https://packagist.org/packages/twineis/raven-php)
[![Total Downloads](https://img.shields.io/packagist/dt/twineis/raven-php.svg?style=flat-square)](https://packagist.org/packages/twineis/raven-php)

Sentry (Raven) error monitoring for Laravel and Lumen with send in background via queues. This will add a listener to Laravel's existing log system. It makes use to Laravel's queues to push messages into the background without slowing down the application.

![rollbar](https://www.getsentry.com/_static/getsentry/images/hero.png)

## Installation

Install the latest version with:

```bash
$ composer require twineis/raven-php
```

For Laravel add the service provider in `config/app.php`:

```php
Twine\Raven\Providers\LaravelServiceProvider::class,
```

Register the Raven alias:

```php
'Raven' => Twine\Raven\Facades\Raven::class,
```

For Lumen add the following to `bootstrap/app.php`

```php
$app->register(Twine\Raven\Providers\LumenServiceProvider::class);
```

## Configuration

Publish the included configuration file **(Laravel only)**:

```bash
$ php artisan vendor:publish
```

Change the Sentry DSN by using the `RAVEN_DSN` env variable or changing the config file:

```php
RAVEN_DSN=your-raven-dsn
```

This library uses the queue system, make sure your `config/queue.php` file is configured correctly. You can also specify the connection and the queue to use in the raven config. The connection and queue must exist in `config/queue.php`. These can be set using the `RAVEN_QUEUE_CONNECTION` for connection and `RAVEN_QUEUE_NAME` for the queue.

```php
RAVEN_QUEUE_CONNECTION=redis
RAVEN_QUEUE_NAME=error
```

**The job data can be quite large, ensure you are using a queue that can support large data sets like `redis` or `sqs`**.
If the job fails to add into the queue, it will be sent directly to sentry, slowing down the request, so its not lost.

## Usage

To monitor exceptions, simply use the `Log` facade or helper:

```php
Log::error($exception->getMessage(), ['exception' => $exception]);
```

This can be done in the ```report``` method in ```app/Exceptions/Handler.php```

```php
/**
 * Report or log an exception.
 *
 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
 *
 * @param  \Exception  $e
 * @return void
 */
public function report(Exception $e)
{
    if ($this->shouldReport($e)) {
        \Log::error($e->getMessage(), ['exception' => $e]);
    }

    return parent::report($e);
}
```

You can change the logs used by changing the log level in the config by modifying the env var.

```php	
RAVEN_LEVEL=error
```

### Event Id

There is a method to get the **last** event id so it can be used on things like the error page.

```php
Raven::getLastEventId();
```

### Context information

You can pass additional information as context like this:

```php
Log::error('Oops, Something went wrong', [
    'user' => ['name' => $user->name, 'email' => $user->email]
]);
```

## Credits

This package was inspired [rcrowe/Raven](https://github.com/rcrowe/Raven).
