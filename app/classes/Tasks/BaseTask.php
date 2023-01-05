<?php namespace MusicCollection\Tasks;

/**
 * Class BaseTask
 * @package MusicCollection\Tasks
 */
abstract class BaseTask
{
    /**
     * @param string $name
     * @param string[] $arguments
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
