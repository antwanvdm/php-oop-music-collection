<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Genre
 * @package MusicCollection\Databases\Models
 * @method static Genre[] getAll()
 * @method static Genre getById(int $id)
 * @property Album[] $albums
 * @method bool saveAlbums()
 * @method int[] getAlbumsIds()
 * @method bool setAlbumsIds(int[] $ids)
 */
class Genre extends BaseModel
{
    protected static string $table = 'genres';

    /**
     * @var array<string, array<string, string|string[]>>
     */
    protected static array $manyToMany = [
        'albums' => [
            'pivotTable' => 'album_genre',
            'foreignKeys' => ['album_id', 'genre_id'],
            'model' => Album::class
        ]
    ];

    public function __construct(
        public ?int $id = null,
        public string $name = ''
    ) {
        parent::__construct();
    }

    /**
     * As Genre is used on many-to-many related scenarios, we need a simple string when printing the object
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
