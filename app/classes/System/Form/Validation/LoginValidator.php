<?php namespace System\Form\Validation;

use System\Databases\Objects\User;
use System\Translation\Translator;

/**
 * Class LoginValidator
 * @package System\Form\Validation
 */
class LoginValidator implements Validator
{
    private array $errors = [];
    private User $user;
    private string $password;

    /**
     * LoginValidator constructor.
     *
     * @param User $user
     * @param string $password
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
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
                $this->errors[] = $t->account->validation->loginError;
            }
        } else {
            $this->errors[] = $t->account->validation->loginError;
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
