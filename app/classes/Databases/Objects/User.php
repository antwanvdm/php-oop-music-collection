<?php namespace MusicCollection\Databases\Objects;

use MusicCollection\Databases\BaseObject;
use MusicCollection\Databases\Database;

/**
 * Class User
 * @package System\Databases\Objects
 * @property Album[] $albums
 * @property Artist[] $artists
 */
class User extends BaseObject
{
    protected static string $table = 'users';

    public ?int $id = null;
    public string $email = '';
    public ?string $password = '';
    public string $name = '';

    /**
     * @return Album[]
     */
    public function albums(): array
    {
        return $this->hasMany(Album::class, 'user_id');
    }

    /**
     * @return Artist[]
     */
    public function artists(): array
    {
        return $this->hasMany(Artist::class, 'user_id');
    }

    /**
     * Get a specific user by its email
     *
     * @param string $email
     * @return User
     * @throws \Exception
     */
    public static function getByEmail(string $email): User
    {
        $db = Database::getInstance();
        $statement = $db->prepare('SELECT * FROM users WHERE email = :email');
        $statement->execute([':email' => $email]);

        if (($user = $statement->fetchObject(get_called_class())) === false) {
            throw new \Exception('User email is not available in the database');
        }

        return $user;
    }
}
