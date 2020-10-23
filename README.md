<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://github.com/yiisoft.png" height="100px">
    </a>
    <h1 align="center">Yii application demo for active record</h1>
    <br>
</p>

[![Total Downloads](https://img.shields.io/packagist/dt/yii-extension/demo)](https://packagist.org/packages/yii-extension/demo)
[![ci](https://github.com/yii-extension/demo/workflows/ci/badge.svg)](https://github.com/yii-extension/demo/actions)
[![codecov](https://codecov.io/gh/yii-extension/demo/branch/master/graph/badge.svg)](https://codecov.io/gh/yii-extension/demo)
[![static analysis](https://github.com/yii-extension/demo/workflows/static%20analysis/badge.svg)](https://github.com/yii-extension/demo/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yii-extension/demo/coverage.svg)](https://shepherd.dev/github/yii-extension/demo)

<p align="center">
    <a href="https://github.com/yii-extension/app" target="_blank">
        <img src="docs\images\home.png" >
    </a>
</p>

Yii demo application for active record with db-sqlite is best for rapidly creating projects.

## Directory structure

      config/             contains application configurations
      resources/layout    contains layout files for the web application
      resources/mail      contains layout and view files for mailer
      resources/view      contains view files for the web application
      src/                application directory
          Action          contains web action classes
          Asset           contains assets definition
          Form            contains form models
          Module          contains modules application
          Theme           contains theme application
          Service         contains web services
          Widget          continas widgets for web application

## Requirements

The minimum requirement by this project template that your Web server supports PHP 7.4.0.

## Installation

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer create-project --prefer-dist --stability dev yii-extension/demo <your project>
~~~

Now you should be able to access the application through the following URL, assuming `app` is the directory
directly under the `public` root.

## Configuring your application

All the configuration is in the `config directory` of the `application`.

## Run command console

~~~
./yii
~~~

## Run migration

~~~
./yii migrate/up
~~~

## Using PHP built-in server

~~~
php -S 127.0.0.1:8080 -t public
~~~

## Wait till it is up, then open the following URL in your browser

~~~
http://localhost:8080
~~~

## Includes the following features:

- [x] Admin Panel Dashboard.
    - [x] /index - Display index page.
    - [x] /about - Display about page.
    - [x] /contact - Display contact form page.

- [x] User module:
    - [x] /auth/login - Display login form.
    - [x] /auth/logout - Logs the user out.
    - [x] /recovery/request - Displays recovery request form.
    - [x] /recovery/reset[/{id}/{code}] - Displays password reset form (requires id and token query params).
    - [x] /registration/confirm[/{id}/{token}] - Confirms a user (requires id and token query params).
    - [x] /registration/register - Displays registration form.
    - [x] /registration/resend - Displays resend form.
    - [x] /admin/index - Display user management interface.
    - [x] /admin/create - Display form register user.
    - [x] /admin/block[/{id}] - Allows you to block or unblock a user.
    - [x] /admin/confirm[/{id}] - Confirms a user (requires id).
    - [x] /admin/delete[/{id}] - Delete a user (requires id).
    - [x] /admin/edit[/{id}] - Display form edit user (requires id).
    - [x] /admin/info[/{id}] - Display info user (requires id).
    - [x] /admin/reset[/{id}] - Send email resend password (requires id).

- [x] Rbac Module:
    - [x] /item/index - Display item management interface.
    - [x] /item/create - Display form create item.
    - [x] /item/edit[/{id}] - Display form edit item (requires id).
    - [x] /item/delete[/{id}] - Delete a item (requires id).

Note: check the directory `/runtime/mail`, the emails are stored in it.

## Codeception testing

The package is tested with [Codeception](https://github.com/Codeception/Codeception). To run tests:

~~~
php -S 127.0.0.1:8080 -t public > yii.log 2>&1 &
vendor/bin/codecept run
~~~

## Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/docs). To run static analysis:

```php
./vendor/bin/psalm
```
