<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Album
 * @package MusicCollection\Databases\Models
 * @method static Album[] getAll()
 * @method static Album getById(int $id)
 * @property Genre[] $genres
 * @method bool saveGenres()
 * @method int[] getGenresIds()
 * @method bool setGenresIds(int[] $ids)
 */
class Album extends BaseModel
{
    protected static string $table = 'albums';

    /**
     * @var array<string, string[]>
     */
    protected static array $belongsTo = [
        'artist_id' => [
            'table' => 'artists',
            'model' => Artist::class
        ],
        'user_id' => [
            'table' => 'users',
            'model' => User::class
        ]
    ];

    /**
     * @var array<string, array<string, string|string[]>>
     */
    protected static array $manyToMany = [
        'genres' => [
            'pivotTable' => 'album_genre',
            'foreignKeys' => ['genre_id', 'album_id'],
            'model' => Genre::class
        ]
    ];

    public Artist $artist;
    public User $user;

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
