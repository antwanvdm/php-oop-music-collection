<?php namespace System\Databases\Objects;

use System\Databases\BaseObject;

/**
 * Class Genre
 * @package System\Databases\Objects
 * @property Album[] $albums
 */
class Genre extends BaseObject
{
    protected static string $table = 'genres';

    public ?int $id = null;
    public string $name = "";

    /**
     * @return Album[]
     */
    public function albums(): array
    {
        return $this->belongsToMany('albums', 'Album', ['genre_id', 'album_id'] ,'album_genre');
    }
}
