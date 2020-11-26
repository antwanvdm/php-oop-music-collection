<?php namespace System\Databases\Objects;

use System\Databases\BaseObject;

/**
 * Class Album
 * @package System\Databases\Objects
 * @property User $user
 * @property Artist $artist
 * @property Genre[] $genres
 */
class Album extends BaseObject
{
    protected static string $table = 'albums';

    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $artist_id = null;
    public string $name = "";
    public string $year = "";
    public int $tracks = 0;
    public string $image = "";

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     * @return Artist
     */
    public function artist(): Artist
    {
        return $this->belongsTo('Artist', 'artist_id');
    }

    /**
     * @return Genre[]
     */
    public function genres(): array
    {
        return $this->belongsToMany('genres','Genre', ['album_id', 'genre_id'], 'album_genre');
    }
}
