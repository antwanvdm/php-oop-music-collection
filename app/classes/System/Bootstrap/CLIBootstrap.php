<?php namespace System\Bootstrap;

use System\DI\Container;
use System\Tasks\BaseTask;
use System\Utils\Logger;

/**
 * Class CLIBootstrap
 * @package System\Bootstrap
 */
class CLIBootstrap implements BootstrapInterface
{
    private Container $di;
    private string $className = '';
    private string $action;
    private array $params;

    public function __construct()
    {
        $this->setup();
    }

    /**
     * Setup the params based on current cli call
     */
    public function setup(): void
    {
        //Use the Dependency Injector container for the classes we need throughout the application
        $this->di = new Container();
        $this->di->set('logger', new Logger());

        try {
            //Get dynamic arguments from command line
            global $argv;
            $dynamicArguments = $argv;
            array_shift($dynamicArguments);

            if (!isset($dynamicArguments[0]) || !isset($dynamicArguments[1])) {
                throw new \Exception('Not enough arguments passed to cli.php');
            }

            //Set first param the define called class & second for action
            $class = $dynamicArguments[0];
            $this->className = '\\System\\Tasks\\' . ucfirst($class) . 'Task';
            array_shift($dynamicArguments);
            $this->action = $dynamicArguments[0];

            //Set remaining arguments as params
            array_shift($dynamicArguments);
            $this->params = $dynamicArguments;
        } catch (\Exception $e) {
            $this->di->get('logger')->error($e);
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
            /** @var $task BaseTask */
            $task = $this->di->set('bootstrap', $this->className);

            return $task->{$this->action}(...$this->params);
        } catch (\Exception $e) {
            $this->di->get('logger')->error($e);

            return 'Oops, something went wrong, please contact the site administrator.';
        }
    }
}
