<?php namespace MusicCollection\Form\Validation;

use MusicCollection\Translation\Translator;

/**
 * Interface Validator
 * @package System\Form\Validation
 */
interface Validator
{
    /**
     * Validate magic requires strings to we need the Translator object
     *
     * @TODO See if this (using parameter passing..) is the best option (DI?!)
     * @param Translator $t
     */
    public function validate(Translator $t): void;

    /**
     * @return array
     */
    public function getErrors(): array;
}
