<?php namespace MusicCollection\Form\Validation;

use MusicCollection\Databases\Objects\User;
use MusicCollection\Translation\Translator;

/**
 * Class LoginValidator
 * @package System\Form\Validation
 */
class LoginValidator implements Validator
{
    private array $errors = [];

    /**
     * LoginValidator constructor.
     *
     * @param User $user
     * @param string $password
     */
    public function __construct(private readonly User $user, private readonly string $password)
    {
    }

    /**
     * Validate the data
     *
     * @param Translator $t
     */
    public function validate(Translator $t): void
    {
        //Go on if we got an user (which means email is correct)
        if (!empty($this->user->email)) {
            //Validate password
            if (!password_verify($this->password, $this->user->password)) {
                $this->errors[] = $t->_('account.validation.loginError');
            }
        } else {
            $this->errors[] = $t->_('account.validation.loginError');
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
