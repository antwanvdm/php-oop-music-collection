<?php namespace MusicCollection\Databases\Objects;

use MusicCollection\Databases\BaseObject;

/**
 * Class Artist
 * @package MusicCollection\Databases\Objects
 * @method static Artist[] getAll()
 * @method static Artist getById(int $id)
 */
class Artist extends BaseObject
{
    protected static string $table = 'artists';
    protected static array $joinForeignKeys = [
        'user_id' => [
            'table' => 'users',
            'object' => User::class
        ]
    ];
    public User $user;

    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public string $name = ''
    ) {
        parent::__construct();
    }

    /**
     * @return Album[]
     */
    public function albums(): array
    {
        return $this->getOneToManyItems(Album::class, 'artist_id');
    }
}
