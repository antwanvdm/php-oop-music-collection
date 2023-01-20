<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Artist
 * @package MusicCollection\Databases\Models
 * @method static Artist[] getAll()
 * @method static Artist getById(int $id)
 * @property User $user
 * @property Album[] $albums
 */
class Artist extends BaseModel
{
    protected static string $table = 'artists';

    /**
     * @var array<string, string[]>
     */
    protected static array $belongsTo = [
        'user' => [
            'foreignKey' => 'user_id',
            'model' => User::class
        ]
    ];

    /**
     * @var array<string, string[]>
     */
    protected static array $hasMany = [
        'albums' => [
            'foreignKey' => 'artist_id',
            'model' => Album::class
        ]
    ];

    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public string $name = ''
    ) {
        parent::__construct();
    }
}
