<?php namespace MusicCollection\Validation;

use MusicCollection\Databases\Models\User;
use MusicCollection\Translation\Translator as T;

/**
 * Class LoginValidator
 * @package MusicCollection\Validation
 */
class LoginValidator implements Validator
{
    /**
     * @var string[]
     */
    private array $errorList = [];
    public array $errors {
        get {
            return $this->errorList;
        }
    }

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
     */
    public function validate(): void
    {
        //Go on if we got a user (which means email is correct)
        if (!empty($this->user->email)) {
            //Validate password
            if (!password_verify($this->password, $this->user->password)) {
                $this->errorList[] = T::__('account.validation.loginError');
            }
        } else {
            $this->errorList[] = T::__('account.validation.loginError');
        }
    }
}
