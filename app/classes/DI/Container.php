<?php namespace MusicCollection\DI;

use MusicCollection\DI\Exceptions\ContainerException;
use MusicCollection\DI\Exceptions\NotFoundException;

/**
 * Class Container inspired by Phalcon framework & Matthew Daly (see link)
 * @package MusicCollection\DI
 * @see https://phalcon.io/
 * @see https://matthewdaly.co.uk/blog/2019/02/02/creating-your-own-dependency-injection-container-in-php/
 */
class Container
{
    private array $entries = [];

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key): mixed
    {
        if (!$this->has($key)) {
            throw new \Exception("Key '$key' is not set in container");
        }

        return $this->entries[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->entries[$key]);
    }

    /**
     * @param string $key
     * @param object|string $value
     * @return mixed
     * @throws ContainerException|NotFoundException
     */
    public function set(string $key, mixed $value): mixed
    {
        if (is_object($value)) {
            $this->entries[$key] = $value;
        } elseif (!$this->has($key)) {
            $this->entries[$key] = $this->resolve($value);
        }

        return $this->entries[$key];
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ContainerException|NotFoundException
     */
    private function resolve(string $key): mixed
    {
        //First try and see if the given key resolved to a class
        try {
            $item = new \ReflectionClass($key);
        } catch (\ReflectionException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }

        //Return a new instance of the new class
        try {
            return $this->getInstance($item);
        } catch (\ReflectionException $e) {
            throw new ContainerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get instance of item
     *
     * @param \ReflectionClass $item
     * @return mixed
     * @throws \ReflectionException|NotFoundException|ContainerException
     */
    private function getInstance(\ReflectionClass $item): mixed
    {
        //Check if we need to worry about an actual constructor
        $constructor = $item->getConstructor();
        if (is_null($constructor) || $constructor->getNumberOfRequiredParameters() == 0) {
            return $item->newInstance();
        }
        $params = [];
        foreach ($constructor->getParameters() as $param) {
            if ($type = $param->getType()) {
                $params[] = $this->set($param->name, $type->getName());
            }
        }

        return $item->newInstanceArgs($params);
    }
}
