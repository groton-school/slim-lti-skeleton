# Slim Framework 4 Skeleton LTI Tool

[![Coverage Status](https://coveralls.io/repos/github/groton-school/slim-lti-skeleton/badge.svg?branch=master)](https://coveralls.io/github/groton-school/slim-lti-skeleton?branch=master)

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application. This application uses the latest Slim 4 with Slim PSR-7 implementation and PHP-DI container implementation. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application. You will require PHP 7.4 or newer.

```bash
composer create-project --stability dev groton-school/slim-lti-skeleton [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

**To complete the implementation of the LTI Tool, you must implement the IDatabase and ICache interfaces from [packbackbooks/lti-1p3-tool](https://github.com/packbackbooks/lti-1-3-php-library/wiki/Laravel-Implementation-Guide#cache) _AND_ autowire them in `/app/settings.php` (as has already been done with the ICookie interface).**

For an example of this, refer to either [groton-school/slim-lti-infrastructure-gae](https://github.com/groton-school/lti.slim-lti-infrastructure-gae) or the more-comprehensive [groton-school/slim-lti-gae-skeleton](https://github.com/groton-school/slim-lti-gae-skeleton) which expands on this skleton with an implementation of an LTI Tool to run on Google App Engine.

To run the application in development, you can run these commands 

```bash
cd [my-app-name]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd [my-app-name]
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it! Now go build something cool.
