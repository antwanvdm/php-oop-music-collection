<?php namespace MusicCollection\Databases\Objects;

use MusicCollection\Databases\BaseObject;

/**
 * Class Genre
 * @package System\Databases\Objects
 * @property Album[] $albums
 * @method static Genre getById($id)
 * @method static Genre[] getAll()
 */
class Genre extends BaseObject
{
    protected static string $table = 'genres';

    public ?int $id = null;
    public string $name = '';

    /**
     * @return Album[]
     */
    public function albums(): array
    {
        return $this->belongsToMany(Album::class, ['genre_id', 'album_id'], 'album_genre');
    }
}
