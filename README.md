# PHP OOP - Music Collection
This project is active to investigate and integrate the latest and greatest PHP
technologies. It is a result of giving the "Advanced PHP" course at the University
of Applied Sciences in Rotterdam. It challenges me to keep up to date on a yearly
basis with new PHP releases and implementing new cool stuff.

Without calling the current project "MVC", it is inspired on popular MVC frameworks
like Laravel, Symfony and Phalcon. Intentionally I try to avoid the MVC name 
conventions, but I might change this in the future. Looking at the code it's definitely
a base to build project upon so we could call it a framework.

The application itself has a simple (one might call it ugly) frontend to present
artists, albums and other stuff related to Music. The goal it not to make a perfect
music collection, but to implement awesome code and understand the core principles of
the language to make sure the next generation knows what a framework entails before
using one themselves.

## If you feel like checking this out (install prerequisites)
### Option 1 - Use the PHP7 vagrant box
As I've got a custom vagrant box ready for the same course, it's the easiest solution
to use this one. You can find all the details to install within the
[repository link](https://github.com/antwanvdm/php7-vagrant).

PS: I am well aware this repo name is not very helpful ones PHP8 arrives soon...

### Option 2 - Do it yourself
You are also free to copy paste the contents into your own local environment. Just be
careful to copy all the required contents (app/public/tests/composer/phpunit)

## Getting the application running
- Create a database called `music_collection`, in the `utf8_mb4_general_ci` encoding. You
can connect to the vagrant box to use the installed mariadb driver.
- Make sure to import the database tables from the [_resources](_resources) folder.
- Create a `settings.php` file in the `app/config` folder with the following contents:
    ```
    <?php
    //Define DB credentials
    define("DB_HOST", "192.168.50.5");
    define("DB_USER", "root");
    define("DB_PASS", "root");
    define("DB_NAME", "music_collection");
    
    //Paths
    define("BASE_PATH", "/");
    define("LOG_PATH", "../app/logs/");
    define("INCLUDES_PATH", __DIR__ . "/../");
    define("RESOURCES_PATH", BASE_PATH);
    
    //Custom error handler, so every error will throw a custom ErrorException
    set_error_handler(function ($severity, $message, $file, $line) {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    });
    ```
- In case you choose a different setup or IP, make sure to change the constants
to the correct settings.
- Run a `composer install` in the vagrant box (in the `/var/www/` folder)
- You can test the php unit test by running `composer run test`. The results can
be found in the `app/logs` folder.
- If you will ever run xDebug traces of profiles, the results can be found in
the `app/logs` folder.
- Any error in your application will be written to the `app/logs/error.log` file.
- On some pages (the add pages) you will be required to login. The database export
includes a `test@test.com` user with the password `test`.

## Roadmap
- ~~Routing class for named routes (to prevent refactoring urls in templates)~~
- ~~Provide a parameter in the route dynamically~~
- Extend Routing system with multiple parameters, get/post & api/non-api
- Add eager loading option for ORM
- Refactor some stuff in the ORM (see todos in code)
- Add some kind of event dispatching system
- ~~Enable system to also return JSON response next to HTML~~
- ~~Add a translator with translation files~~
- ~~Implement CLI bootstrap~~
- ~~Add cronjob support ("tasks")~~
- Make wrapper (request object?) for super globals $_GET/$_POST
- ~~Implement basic dependency injection system~~
- Configure psalm, phpunit & php-cs-fixer in automated script
- ~~Possibility to extend and re-use templates~~
- Extend the logging system to different logging levels (now only error)
- Rename namespaces to MVC terminology
- Make an actual composer package for this (separate music collection from the core)
- Ok... Add some basic CSS framework to prevent tears
- Add actual multilingual support
- Implement PHP8 Attributes (and other possible cool PHP8 stuff)

## Changelog
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
