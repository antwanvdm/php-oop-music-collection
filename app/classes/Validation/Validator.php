<?php namespace MusicCollection\Validation;

/**
 * Interface Validator
 * @package MusicCollection\Validation
 */
interface Validator
{
    /**
     * @return void
     */
    public function validate(): void;

    /**
     * @var string[]
     */
    public array $errors { get; }
}
