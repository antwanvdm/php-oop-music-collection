<?php namespace MusicCollection\Responses;

/**
 * Base class for every response
 * @package MusicCollection\Responses
 */
abstract class Response
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    abstract public function __toString(): string;
}
