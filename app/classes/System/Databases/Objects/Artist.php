<?php namespace System\Databases\Objects;

use System\Databases\BaseObject;

/**
 * Class Artist
 * @package System\Databases\Objects
 * @property User $user
 * @property Album[] $albums
 * @method static Artist getById($id)
 */
class Artist extends BaseObject
{
    protected static string $table = 'artists';

    public ?int $id = null;
    public ?int $user_id = null;
    public string $name = '';

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->belongsTo('User', 'user_id');
    }

    /**
     * @return Album[]
     */
    public function albums(): array
    {
        return $this->hasMany('Album', 'artist_id');
    }
}
