<?php namespace MusicCollection\Bootstrap;

use MusicCollection\DI\Container;
use MusicCollection\Tasks\BaseTask;
use MusicCollection\Utils\Logger;
use MusicCollection\Translation\Translator as T;

/**
 * Class CLIBootstrap
 * @package MusicCollection\Bootstrap
 */
class CLIBootstrap implements BootstrapInterface
{
    private Container $di;
    private string $className = '';
    private string $action;
    /**
     * @var string[]
     */
    private array $params;

    public function __construct()
    {
        $this->setup();
    }

    /**
     * Set up the params based on current cli call
     */
    public function setup(): void
    {
        try {
            //@see https://www.php.net/manual/en/reserved.variables.argv.php
            global $argv;

            //Use the Dependency Injector container for the classes we need throughout the application
            $this->di = new Container();

            //Get dynamic arguments from command line
            $dynamicArguments = $argv;
            array_shift($dynamicArguments);

            if (!isset($dynamicArguments[0]) || !isset($dynamicArguments[1])) {
                throw new \Exception('Not enough arguments passed to cli.php');
            }

            //Set first param to define called class & second for action
            $class = $dynamicArguments[0];
            $this->className = '\\MusicCollection\\Tasks\\' . ucfirst($class) . 'Task';
            array_shift($dynamicArguments);
            $this->action = $dynamicArguments[0];

            //Set remaining arguments as params
            array_shift($dynamicArguments);
            $this->params = $dynamicArguments;
        } catch (\Throwable $e) {
            Logger::error($e);
        }
    }

    /**
     * Initialize controller, set dynamic properties, call current action & get the rendered data
     *
     * @return string
     */
    public function render(): string
    {
        try {
            if (!class_exists($this->className)) {
                throw new \Exception('Class ' . $this->className . ' does not exist!');
            }
            $task = $this->di->set('bootstrap', $this->className);
            assert($task instanceof BaseTask);

            return $task->{$this->action}(...$this->params);
        } catch (\Throwable $e) {
            Logger::error($e);

            return T::__('general.errors.die');
        }
    }
}
