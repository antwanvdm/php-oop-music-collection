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
     * @return string[]
     */
    public function getErrors(): array;
}
