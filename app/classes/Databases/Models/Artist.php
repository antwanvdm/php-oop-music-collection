<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Artist
 * @package MusicCollection\Databases\Models
 * @method static Artist[] getAll(string[] $with = [])
 * @method static Artist getById(int $id, string[] $with = [])
 * @property User $user
 * @property Album[] $albums
 */
class Artist extends BaseModel
{
    protected static string $table = 'artists';

    protected static array $belongsTo = [
        'user' => [
            'foreignKey' => 'user_id',
            'model' => User::class
        ]
    ];

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
