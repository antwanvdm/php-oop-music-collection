<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Artist
 * @package MusicCollection\Databases\Models
 * @method static Artist[] getAll()
 * @method static Artist getById(int $id)
 */
class Artist extends BaseModel
{
    protected static string $table = 'artists';
    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $joinForeignKeys = [
        'user_id' => [
            'table' => 'users',
            'model' => User::class
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
