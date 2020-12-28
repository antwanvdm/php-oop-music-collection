<?php namespace System\Form\Validation;

use System\Translation\Translator;

/**
 * Interface Validator
 * @package System\Form\Validation
 */
interface Validator
{
    /**
     * Validate magic requires strings to we need the Translator object
     *
     * @TODO See if this (using parameter passing..) is the best option
     * @param Translator $t
     */
    public function validate(Translator $t): void;

    /**
     * @return array
     */
    public function getErrors(): array;
}
