# PHP OOP - Music Collection

This project is active to investigate and integrate the latest and greatest PHP
technologies. It is a result of giving the "Advanced PHP" course at the University
of Applied Sciences in Rotterdam. It challenges me to keep up to date on a yearly
basis with new PHP releases and implementing new cool stuff.

The project is inspired by popular MVC frameworks like Laravel (mostly), Symfony and 
Phalcon. In the first iterations I didn't use the MVC naming conventions but the current
version feels 'mature' enough to use MVC terminology. Based on what the application does,
you can safely call the core of the code a framework.

The application itself has a simple [bulma.css](https://bulma.io) frontend to present
artists, albums and other stuff related to Music. The goal it not to make a perfect
music collection, but to implement awesome code and understand the core principles of
the language to make sure the next generation knows what a framework entails before
using one themselves.

## Getting the application running

- Make sure to import the database from the [_resources](_resources) folder.
- Create a `settings.php` file in the `app/config` folder with the following contents:

```php
<?php
//Define DB credentials
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'music_collection';

//Paths (BASE_PATH is for referring to paths and resources, the other are for internal handlers)
const BASE_PATH = '/';
const INCLUDES_PATH = __DIR__ . '/../';
const LOG_PATH = INCLUDES_PATH . 'logs/';
const LANGUAGE_PATH = INCLUDES_PATH . 'languages/';
const LANGUAGES = ['nl' => 'Nederlands', 'en' => 'English'];
const DEFAULT_LANGUAGE = 'nl';

//Custom error handler, so every error will throw a custom ErrorException
set_error_handler(function (int $severity, string $message, string $file, int $line): bool|null {
    //Still respect the @ surpassing of errors. phpstan uses it and gave me weird caching errors
    if ((error_reporting() & $severity) === 0) {
        return null;
    }
    throw new ErrorException($message, $severity, $severity, $file, $line);
});
```

- In case you choose a different setup or IP, make sure to change the constants
  to the correct settings.
- Run `composer install` in the root folder to install dependencies and generate
  the autoloader.
- You can test the php unit test by running `composer run test`. You can check the
  `composer.json` for more scripts for `phpstan` and `php-cs-fixer`.
- Any log in your application will be written to the `app/logs/application.log` file.
- On some pages (album & artist forms) you will be required to login. The database
  export includes a `test@test.com` user with the password `test`.
- If you just want to start the server with basic PHP, you can run
  `php -S localhost:8888 server.php` in the public folder. This way, you don't need
  an apache or nginx server to try out this application.
- If you want to run the CLI, you can find 1 Task to register a user. You can run this
  example: `php -d xdebug.mode=off app/cli.php account register new@test.com New secret`.
- There is also 1 API endpoint included to add a favorite, you can find this example in
  `public/js/main.js`. Always make sure to set the `Content-Type` header to
  `application/json` when you use the API controller with JSON response.

## Roadmap

- ~~Routing class for named routes (to prevent refactoring urls in templates)~~
- ~~Provide a parameter in the route dynamically~~
- ~~Enable system to also return JSON response next to HTML~~
- ~~Add a translator with translation files~~
- ~~Implement CLI bootstrap~~
- ~~Add cronjob support ("tasks")~~
- ~~Implement basic dependency injection system~~
- ~~Configure psalm, phpunit & php-cs-fixer in automated script~~
- ~~Possibility to extend and re-use templates~~
- ~~Add option to separate get & post routes~~
- ~~Ok... Add some basic CSS framework to prevent tears~~
- ~~Implement PHP8 Attributes (and other possible cool PHP8 stuff)~~
- ~~Add option to use placeholders in translation strings~~
- ~~Rebuild the ORM system to be more flexible/readable~~
- ~~Implement Singleton patter for more objects that are needed throughout application,
  like DB, Logger & Translator~~
- ~~Extend the logging system to different logging levels (now only error)~~
- ~~Comply with phpstan level 6~~
- ~~Add actual multilingual support (EN/NL) with language switch~~
- ~~Extend Routing system with multiple parameters & better errors~~
- ~~Make wrapper (request object!) for super globals $_GET/$_POST. Current state of
  handlers is a mess due to many floating request/state code~~
- ~~Extend Routing system with namespaces like api/non-api~~
- ~~Add middleware option for routes~~
- ~~Rename Handlers & Objects to Controllers & Models~~
- ~~Make controller actions return something (View or JSON)~~
- ~~Implement the hasMany & manyToMany relations in a reusable way for future cases~~
- Refactor some stuff in the Template, Router & Controller (see TODO in code)
- Create something like flash messages for the session
- Implement a basic migrations system to create tables
- Add some kind of event dispatching system
- Make an actual composer package for this (separate music collection from the core)

## Changelog

### v2.5.0

- Implement an actual Task to register a new user via the CLI
- Implemented hasMany & manyToMany relations in the base classes. The Models are now
  very clean and easy to implement!
- Created a feature to add favorites on the album detail page via AJAX calls. This way
  we have an actual working scenario for the `api` with JSON response.
- Add some assert() functions to replace inline `@var` notations. This make the code
  more reliable as it will raise an Exception

### v2.4.0

- Major refactoring code of Router/Route. Added middleware, groups (with middleware 
  & prefix) & resource options for routes with clean notation in routes.php
- Fixed phpStan generating cache errors (See custom error handling change)
- Renamed Handlers to Controller & Objects to Models to act like a true MVC framework
- Implemented Response classes for View & Json (replaced the old Template class) to
  clean up the WebBootstrap and divide responsibilities

### v2.3.0

- Added support for multiple parameters in routes
- Improved error handling to catch "Throwable" top level
- Fixed all minor phpStorm warnings (typos, etc)
- Added a Request object to wrap all Super Globals (except Session, which has its
  own class). Removed the old Post Data object & Traits as they became irrelevant

### v2.2.0

- Updated composer packages
- Made the code 100% compliant with phpstan level 6
- Added multi-language toggle switch with working EN/NL feature
- Session is now available as Singleton as well

### v2.1.0

- Refactored the Translation strings to support parameters & better errror handling
- Made the generated name for image as unique as possible
- Refactored the "ORM" to be better readable and usable
- Fixed the forms so that data gets remembered on an error
- Added Singleton as standardised pattern for DB, Logger & Translator. They are now
  easily available through the application
- Added an extra level of logging besides 'error' (info)
- Updated documentation with CLI & API examples

### v2.0.0

- Removed vagrant from the repository (makes it better usable for everyone)
- Support the option to run the application with the built-in PHP webserver
- Added PHP8 supported features (promoted properties, readonly, better return types)
- Added bulma.css for templates
- Replaced psalm for phpstan
- Updated code style to (mostly..) PSR-12

### v1.3.0

- Added way more flexible template usage and child templates ($yield)
- Added psalm & php-cs-fixer configuration. Fixed all the code including some minor
  phpunit fixes
- Support for GET/POST which included major refactoring of Router/Handlers
- Added support for JSON response next to HTML fom the BaseHandler

### v1.2.0

- Implemented a very basis Dependency Injection system
- Implemented language translation (just for storing copy centralised). It's prepared
  for more languages for the system isn't multilingual (yet!)

### v1.1.0

- Implemented CLI bootstrap & corresponding tasks
- Implemented a more advanced routing system

### v1.0.0

- First version based on the outcome of my course.
- Includes templating, magic methods, interfaces, traits, abstract classes, statics,
  singleton database, basic ORM system, basic unit tests, small utils, form validation,
  routes (just an array), setting constants, composer autoloading, error logging,
  handlers (could be seen as controllers) & objects (could be seen as models)
