<?php namespace MusicCollection\Tasks;

use MusicCollection\Databases\Models\User;

/**
 * Class AccountTask
 * @package MusicCollection\Tasks
 */
class AccountTask extends BaseTask
{
    /**
     * @param string[] $params
     * @return string
     * @noinspection PhpUnused
     */
    protected function register(array $params): string
    {
        if (count($params) !== 3) {
            return 'Missing required parameters in this order (email, name, password)';
        }

        list($email, $name, $password) = $params;

        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        return $user->save() ? "User $email is registered" : 'Something went wrong creating the user';
    }
}
