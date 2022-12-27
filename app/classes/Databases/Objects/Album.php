<?php namespace MusicCollection\Databases\Objects;

use MusicCollection\Databases\BaseObject;

/**
 * Class Album
 * @package System\Databases\Objects
 * @property User $user
 * @property Artist $artist
 * @property Genre[] $genres
 * @method static Album getById($id)
 */
class Album extends BaseObject
{
    protected static string $table = 'albums';

    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $artist_id = null;
    public string $name = '';
    public string $year = '';
    public int $tracks = 0;
    public string $image = '';

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return Artist
     */
    public function artist(): Artist
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    /**
     * @return Genre[]
     */
    public function genres(): array
    {
        return $this->belongsToMany(Genre::class, ['album_id', 'genre_id'], 'album_genre');
    }
}
