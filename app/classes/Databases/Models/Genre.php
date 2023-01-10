<?php namespace MusicCollection\Databases\Models;

use MusicCollection\Databases\BaseModel;

/**
 * Class Genre
 * @package MusicCollection\Databases\Models
 * @method static Genre[] getAll()
 * @method static Genre getById(int $id)
 */
class Genre extends BaseModel
{
    protected static string $table = 'genres';

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

    /**
     * @return Album[]
     */
    public function albums(): array
    {
        return $this->getManyToManyItems(Album::class, 'album_genre', ['album_id', 'genre_id']);
    }
}
