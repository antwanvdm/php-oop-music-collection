<?php namespace MusicCollection\Databases\Objects;

use MusicCollection\Databases\BaseObject;

/**
 * Class User
 * @package MusicCollection\Databases\Objects
 * @method static User[] getAll()
 * @method static User getById($id)
 * @method static User getByEmail($email)
 */
class User extends BaseObject
{
    protected static string $table = 'users';

    public function __construct(
        public ?int $id = null,
        public string $email = '',
        public ?string $password = '',
        public string $name = ''
    ) {
        parent::__construct();
    }
}
