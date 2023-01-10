<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class User
 * @package MusicCollection\Databases\Models
 * @method static User[] getAll()
 * @method static User getById($id)
 * @method static User getByEmail($email)
 */
class User extends BaseModel
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
