<?php namespace MusicCollection\Form\Validation;

/**
 * Interface Validator
 * @package MusicCollection\Form\Validation
 */
interface Validator
{
    /**
     * Validate magic requires strings to we need the Translator object
     */
    public function validate(): void;

    /**
     * @return string[]
     */
    public function getErrors(): array;
}
