<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Album
 * @package MusicCollection\Databases\Models
 * @method static Album[] getAll(string[] $with = [])
 * @method static Album getById(int $id, string[] $with = [])
 * @property Artist $artist
 * @property User $user
 * @property Genre[] $genres
 * @method bool saveGenres()
 * @method int[] getGenresIds()
 * @method bool setGenresIds(int[] $ids)
 */
class Album extends BaseModel
{
    protected static string $table = 'albums';

    protected static array $belongsTo = [
        'artist' => [
            'foreignKey' => 'artist_id',
            'model' => Artist::class
        ],
        'user' => [
            'foreignKey' => 'user_id',
            'model' => User::class
        ]
    ];

    protected static array $belongsToMany = [
        'genres' => [
            'pivotTable' => 'album_genre',
            'foreignKeys' => ['genre_id', 'album_id'],
            'model' => Genre::class
        ]
    ];

    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public ?int $artist_id = null,
        public string $name = '',
        public string $year = '',
        public int $tracks = 0,
        public string $image = ''
    ) {
        parent::__construct();
    }
}
