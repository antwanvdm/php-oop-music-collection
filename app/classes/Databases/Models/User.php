<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class User
 * @package MusicCollection\Databases\Models
 * @method static User[] getAll(string[] $with = [])
 * @method static User getById(int $id, string[] $with = [])
 * @method static User getByEmail(string $email, string[] $with = [])
 * @property Album[] $favoriteAlbums
 * @method bool saveFavoriteAlbums()
 * @method int[] getFavoriteAlbumsIds()
 * @method bool setFavoriteAlbumsIds(int[] $ids)
 */
class User extends BaseModel
{
    protected static string $table = 'users';

    protected static array $belongsToMany = [
        'favoriteAlbums' => [
            'pivotTable' => 'album_user_favorites',
            'foreignKeys' => ['album_id', 'user_id'],
            'model' => Album::class
        ]
    ];

    public function __construct(
        public ?int $id = null,
        public string $email = '',
        public ?string $password = '',
        public string $name = ''
    ) {
        parent::__construct();
    }
}
