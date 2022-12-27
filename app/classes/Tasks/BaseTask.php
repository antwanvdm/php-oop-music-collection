<?php namespace MusicCollection\Tasks;

use MusicCollection\Utils\Logger;

/**
 * Class BaseTask
 * @package System\Tasks
 * @property Logger $logger
 */
abstract class BaseTask
{
    protected array $properties = [];

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value): void
    {
        $this->properties[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->properties[$name];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func([$this, $name], $arguments);
        }

        throw new \Exception("Route does not exist or invalid function ($name) was called");
    }
}
